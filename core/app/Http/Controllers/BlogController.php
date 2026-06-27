<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::posts()
            ->with('author', 'categories', 'tags')
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('blog.index', ['posts' => $posts]);
    }

    public function show(string $slug)
    {
        $post = Post::posts()
            ->with('author', 'categories', 'tags')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        return view('blog.show', ['post' => $post]);
    }
}
