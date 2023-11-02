<?php

namespace App\Http\Controllers;

use App\Models\Post;

class HomePageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return view('welcome', [
            'posts' => Post::query()
                ->select([
                    'id', 'title', 'slug', 'excerpt', 'cover', 'published_at', 'category_id', 'user_id',
                ])
                ->with([
                    'category:id,name',
                    'user:id,name',
                    'tags:id,name',
                    'photos:post_id,path,alt_text',
                ])
                ->published()
                ->latest('published_at')
                ->paginate(5),
        ]);
    }
}
