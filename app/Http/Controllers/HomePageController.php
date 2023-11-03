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
        $data = Post::all();
        return view('blogs', compact('data'));
    }
}
