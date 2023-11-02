<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostPageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Post $post)
    {
        $post->loadMissing([
            'category:id,name',
            'user:id,name',
            'tags:id,name',
            'photos:post_id,path,alt_text',
        ]);

        return view('post', compact('post'));
    }
}
