<?php

use App\Http\Controllers\HomePageController;
use App\Http\Controllers\PostPageController;
use Illuminate\Support\Facades\Http;
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

Route::get('/', HomePageController::class)->name('home');
Route::get('/blog/{post:slug}', PostPageController::class)->name('post');

Route::get('/test', function () {
    $html = '<p>asdfasdfadfa</p><p><figure data-trix-attachment="{&quot;contentType&quot;:&quot;image/jpeg&quot;,&quot;filename&quot;:&quot;photo_2023-10-19_14-19-28.jpg&quot;,&quot;filesize&quot;:158472,&quot;height&quot;:720,&quot;href&quot;:&quot;http://127.0.0.1:8000/storage/posts/soaiIbgVTHbR3GKkiY1WEFR4boOkMuEd6dkTGGLg.jpg&quot;,&quot;url&quot;:&quot;http://127.0.0.1:8000/storage/posts/soaiIbgVTHbR3GKkiY1WEFR4boOkMuEd6dkTGGLg.jpg&quot;,&quot;width&quot;:1280}" data-trix-content-type="image/jpeg" data-trix-attributes="{&quot;presentation&quot;:&quot;gallery&quot;}" class="attachment attachment--preview attachment--jpg"><a href="http://127.0.0.1:8000/storage/posts/soaiIbgVTHbR3GKkiY1WEFR4boOkMuEd6dkTGGLg.jpg"><img src="http://127.0.0.1:8000/storage/posts/soaiIbgVTHbR3GKkiY1WEFR4boOkMuEd6dkTGGLg.jpg" width="1280" height="720"><figcaption class="attachment__caption"><span class="attachment__name">photo_2023-10-19_14-19-28.jpg</span> <span class="attachment__size">154.76 KB</span></figcaption></a></figure></p><p>asdfa</p><p>sdf</p><p><figure data-trix-attachment="{&quot;contentType&quot;:&quot;image/jpeg&quot;,&quot;filename&quot;:&quot;photo_2023-10-19_14-19-28.jpg&quot;,&quot;filesize&quot;:158472,&quot;height&quot;:720,&quot;href&quot;:&quot;http://127.0.0.1:8000/storage/posts/iuhUJKcG7yVYupYaSrozkrRFAE6IP9raZfvETttW.jpg&quot;,&quot;url&quot;:&quot;http://127.0.0.1:8000/storage/posts/iuhUJKcG7yVYupYaSrozkrRFAE6IP9raZfvETttW.jpg&quot;,&quot;width&quot;:1280}" data-trix-content-type="image/jpeg" data-trix-attributes="{&quot;presentation&quot;:&quot;gallery&quot;}" class="attachment attachment--preview attachment--jpg"><a href="http://127.0.0.1:8000/storage/posts/iuhUJKcG7yVYupYaSrozkrRFAE6IP9raZfvETttW.jpg"><img src="http://127.0.0.1:8000/storage/posts/iuhUJKcG7yVYupYaSrozkrRFAE6IP9raZfvETttW.jpg" width="1280" height="720"><figcaption class="attachment__caption"><span class="attachment__name">photo_2023-10-19_14-19-28.jpg</span> <span class="attachment__size">154.76 KB</span></figcaption></a></figure></p>';; // Ваш HTML-код
    $pattern = '/src="([^"]+)"/';
    preg_match_all($pattern, $html, $matches);
    $srcValues = $matches[1];

    $files = [];

    foreach ($srcValues as $pattern) {
        $storageString = '/storage/';
        $attachments = strpos($pattern, $storageString);
        $data_new = substr($pattern, $attachments + strlen($storageString));

        if (Storage::disk('public')->exists($data_new)) {
            $fileContent = Storage::disk('public')->get($data_new);

            $files[] = [
                'name' => 'images[]',
                'contents' => $fileContent,
                'filename' => basename($data_new)
            ];
        }
    }

    $targetUrl = 'http://127.0.0.1:8001/save_file';
    $response = Http::attach($files)->post($targetUrl);

    if ($response->successful()) {
        echo 'Files successfully sent!';
        echo 'Server response: ' . $response->body();
    } else {
        echo 'Error: ' . $response->status();
    }
});
