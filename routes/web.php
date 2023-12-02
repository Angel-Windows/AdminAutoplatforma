<?php

use App\Http\Controllers\HomePageController;
use App\Http\Controllers\PostPageController;
use App\Jobs\MyTask;
use App\Models\Blog\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
})->name('home');
Route::get('/blog/{post:slug}', PostPageController::class)->name('post');
Route::get('/test_https', function () {

})->name('test-https');


Route::get('/test', function () {
//    $model = DB::connection('blog_db')->table('posts')->find(10);
//    $filePath = $model->cover;
//    $files = [];
//    if (Storage::disk('public')->exists($filePath ?? "ase")) {
//        $imagePath = Storage::disk('public')->get($filePath);
//        $originalFileName = basename($filePath);
//                $files[] = [
//                    'name' => 'images[]',
//                    'contents' => $imagePath,
//                    'filename' => $originalFileName,
//                ];
//    }
//    $data_content_arr = [];
//    foreach (json_decode($model->content) as $key => $item_content) {
//        $pattern = '/src="([^"]+)"/';
//        preg_match_all($pattern, $item_content, $matches);
//        $oldBaseUrl = env('APP_URL') . '/storage/';
//        $newBaseUrl = env('BLOG_URL') ;
//        $newHtml = str_replace($oldBaseUrl, $newBaseUrl, $item_content);
//        $data_content_arr[$key] = $newHtml;
//        $srcValues = $matches[1];
//        foreach ($srcValues as $pattern) {
//            $storageString = '/storage/';
//            $attachments = strpos($pattern, $storageString);
//            $data_new = substr($pattern, $attachments + strlen($storageString));
//            if (Storage::disk('public')->exists($data_new) ?? "asr") {
//                $fileContent = Storage::disk('public')->get($data_new);
//                $files[] = [
//                    'name' => 'images[]',
//                    'contents' => $fileContent,
//                    'filename' => basename($data_new),
//                ];
//            }
//        }
//    }
//    foreach ($files as $item) {
//
//        Storage::disk('posts_logo')->put('posts/' . $item['filename'], $item['contents']);
//    }
//    DB::connection('blog_db')->table('posts')
//        ->where('id', $model->id)
//        ->update(['content_blog' => json_encode($data_content_arr)]);

//    MyTask::dispatch(10)->delay(now()->addSeconds(10));




//    $html = '<p>asdfasdfadfa</p><p><figure data-trix-attachment="{&quot;contentType&quot;:&quot;image/jpeg&quot;,&quot;filename&quot;:&quot;photo_2023-10-19_14-19-28.jpg&quot;,&quot;filesize&quot;:158472,&quot;height&quot;:720,&quot;href&quot;:&quot;http://127.0.0.1:8000/storage/posts/soaiIbgVTHbR3GKkiY1WEFR4boOkMuEd6dkTGGLg.jpg&quot;,&quot;url&quot;:&quot;http://127.0.0.1:8000/storage/posts/soaiIbgVTHbR3GKkiY1WEFR4boOkMuEd6dkTGGLg.jpg&quot;,&quot;width&quot;:1280}" data-trix-content-type="image/jpeg" data-trix-attributes="{&quot;presentation&quot;:&quot;gallery&quot;}" class="attachment attachment--preview attachment--jpg"><a href="http://127.0.0.1:8000/storage/posts/soaiIbgVTHbR3GKkiY1WEFR4boOkMuEd6dkTGGLg.jpg"><img src="http://127.0.0.1:8000/storage/posts/soaiIbgVTHbR3GKkiY1WEFR4boOkMuEd6dkTGGLg.jpg" width="1280" height="720"><figcaption class="attachment__caption"><span class="attachment__name">photo_2023-10-19_14-19-28.jpg</span> <span class="attachment__size">154.76 KB</span></figcaption></a></figure></p><p>asdfa</p><p>sdf</p><p><figure data-trix-attachment="{&quot;contentType&quot;:&quot;image/jpeg&quot;,&quot;filename&quot;:&quot;photo_2023-10-19_14-19-28.jpg&quot;,&quot;filesize&quot;:158472,&quot;height&quot;:720,&quot;href&quot;:&quot;http://127.0.0.1:8000/storage/posts/iuhUJKcG7yVYupYaSrozkrRFAE6IP9raZfvETttW.jpg&quot;,&quot;url&quot;:&quot;http://127.0.0.1:8000/storage/posts/iuhUJKcG7yVYupYaSrozkrRFAE6IP9raZfvETttW.jpg&quot;,&quot;width&quot;:1280}" data-trix-content-type="image/jpeg" data-trix-attributes="{&quot;presentation&quot;:&quot;gallery&quot;}" class="attachment attachment--preview attachment--jpg"><a href="http://127.0.0.1:8000/storage/posts/iuhUJKcG7yVYupYaSrozkrRFAE6IP9raZfvETttW.jpg"><img src="http://127.0.0.1:8000/storage/posts/iuhUJKcG7yVYupYaSrozkrRFAE6IP9raZfvETttW.jpg" width="1280" height="720"><figcaption class="attachment__caption"><span class="attachment__name">photo_2023-10-19_14-19-28.jpg</span> <span class="attachment__size">154.76 KB</span></figcaption></a></figure></p>';; // Ваш HTML-код
//    $pattern = '/src="([^"]+)"/';
//    preg_match_all($pattern, $html, $matches);
//    $srcValues = $matches[1];
//
//    $files = [];
//
//    foreach ($srcValues as $pattern) {
//        $storageString = '/storage/';
//        $attachments = strpos($pattern, $storageString);
//        $data_new = substr($pattern, $attachments + strlen($storageString));
//
//        if (Storage::disk('public')->exists($data_new)) {
//            $fileContent = Storage::disk('public')->get($data_new);
//
//            $files[] = [
//                'name' => 'images[]',
//                'contents' => $fileContent,
//                'filename' => basename($data_new)
//            ];
//        }
//    }
//
//    $targetUrl = 'http://127.0.0.1:8001/save_file';
//    $response = Http::attach($files)->post($targetUrl);
//
//    if ($response->successful()) {
//        echo 'Files successfully sent!';
//        echo 'Server response: ' . $response->body();
//    } else {
//        echo 'Error: ' . $response->status();

});
