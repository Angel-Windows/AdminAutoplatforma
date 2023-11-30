<?php

namespace App\Models\Blog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Blog\BlogUser as User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasFactory;
    use HasTranslations;
    protected $connection = 'blog_db';
    public $translatable = [ 'content','title', 'excerpt'];
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover',
        'cover_url',
        'published_at',
        'reading_time',
        'alt_image'
    ];
    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'array',
        'title' => 'array',
        'excerpt' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function publishedFormat(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->published_at < now()->subDays()
            ? $this->published_at->format('F j, Y')
            : $this->published_at->diffForHumans(),
        );
    }

    public function scopePublished(Builder $query): void
    {
        $query
            ->whereNotNull('published_at')
            ->whereDate('published_at', '<', now());
    }

    protected static function boot()
    {
        parent::boot();

//        static::deleted(fn(Post $post) => Storage::disk('public')->delete($post->cover));
    }

    protected static function booted()
    {

        static::saved(function ($model) {
            Log::debug('An informational message.');
            $filePath = $model->cover;
            $files = [];
            $targetUrl = env('BLOG_URL') . 'save_file';

//            $targetUrl = env('blog_url') . '/save_file';
            if (Storage::disk('public')->exists($filePath ?? "ase")) {
                $imagePath = Storage::disk('public')->get($filePath);
                $originalFileName = basename($filePath);
                Storage::disk('upload_logo_post')->put('posts/' . $originalFileName, $imagePath);
//                $files[] = [
//                    'name' => 'images[]',
//                    'contents' => $imagePath,
//                    'filename' => $originalFileName,
//                ];
            }
            $pattern = '/src="([^"]+)"/';

            preg_match_all($pattern, $model->content, $matches);
            $oldBaseUrl = '../../../storage/';
            $newBaseUrl = env('BLOG_URL') ;
            $newHtml = str_replace($oldBaseUrl, $newBaseUrl, $model->content);


//            dd($oldBaseUrl . '\n /n' . $newBaseUrl . '\n /n' . $newHtml);
            $srcValues = $matches[1];
//            dd($srcValues);
            foreach ($srcValues as $pattern) {

                $storageString = '../../../storage/';
                $attachments = strpos($pattern, $storageString);

                $data_new = substr($pattern, $attachments + strlen($storageString));

                if (Storage::disk('public')->exists($data_new) ?? "asr") {
//                    dd($attachments, $data_new, $files);
                    $fileContent = Storage::disk('public')->get($data_new);
                    $files[] = [
                        'name' => 'images[]',
                        'contents' => $fileContent,
                        'filename' => basename($data_new),
                    ];
                }
//
            }

            foreach ($files as $item){
                Storage::disk('posts')->put('posts/' . $item['filename'], $item['contents']);
            }
            DB::connection('blog_db')->table('posts')
                ->where('id', $model->id)
                ->update(['content_blog' => $newHtml]);
//            DB::connection('blog_db')->table('posts')
//                ->where('id', $model->id)
//                ->update(['content' => $newHtml]);
            $response = Http::attach($files)->post($targetUrl);
        });

    }

}
