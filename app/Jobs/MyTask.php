<?php

namespace App\Jobs;

use App\Models\Blog\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MyTask implements ShouldQueue
{
    use Dispatchable, Queueable;

    private $model_id;
    public function __construct($model)
    {
        $this->model_id = $model;
    }

    public function handle()
    {
        $this->processPost($this->model_id);
    }
    public function processPost($postId)
    {
        $model = DB::connection('blog_db')->table('posts')->find($postId);
        $files = [];

        $files = $this->processCoverAndContentImages($model, $files);

        foreach ($files as $item) {
            Storage::disk('posts_logo')->put('posts/' . $item['filename'], $item['contents']);
        }

        DB::connection('blog_db')->table('posts')
            ->where('id', $model->id)
            ->update(['content_blog' => json_encode($this->replaceContentUrls($model))]);

        return response()->json(['message' => 'Пост успешно обработан']);
    }

    private function processCoverAndContentImages($model, $files)
    {
        $filePath = $model->cover;

        if (Storage::disk('public')->exists($filePath ?? "ase")) {
            $imagePath = Storage::disk('public')->get($filePath);
            $originalFileName = basename($filePath);
            $files[] = [
                'name' => 'images[]',
                'contents' => $imagePath,
                'filename' => $originalFileName,
            ];
        }

        $data_content_arr = [];
        foreach (json_decode($model->content) as $key => $item_content) {
            $pattern = '/src="([^"]+)"/';
            preg_match_all($pattern, $item_content, $matches);
            $oldBaseUrl = env('APP_URL') . '/storage/';
            $newBaseUrl = env('BLOG_URL');
            $newHtml = str_replace($oldBaseUrl, $newBaseUrl, $item_content);
            $data_content_arr[$key] = $newHtml;
            $srcValues = $matches[1];
            foreach ($srcValues as $pattern) {
                $storageString = '/storage/';
                $attachments = strpos($pattern, $storageString);
                $data_new = substr($pattern, $attachments + strlen($storageString));
                if (Storage::disk('public')->exists($data_new) ?? "asr") {
                    $fileContent = Storage::disk('public')->get($data_new);
                    $files[] = [
                        'name' => 'images[]',
                        'contents' => $fileContent,
                        'filename' => basename($data_new),
                    ];
                }
            }
        }

        return $files;
    }

    private function replaceContentUrls($model)
    {
        $data_content_arr = [];
        foreach (json_decode($model->content) as $key => $item_content) {
            $pattern = '/src="([^"]+)"/';
            preg_match_all($pattern, $item_content, $matches);
            $oldBaseUrl = env('APP_URL') . '/storage/';
            $newBaseUrl = env('BLOG_URL');
            $newHtml = str_replace($oldBaseUrl, $newBaseUrl, $item_content);
            $data_content_arr[$key] = $newHtml;
        }

        return $data_content_arr;
    }
}
