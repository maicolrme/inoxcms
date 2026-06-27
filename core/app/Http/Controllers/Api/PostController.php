<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $posts = Post::query()
            ->with('author', 'categories', 'tags')
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->type, fn($q, $v) => $q->where('type', $v))
            ->when($request->search, fn($q, $v) => $q->where('title', 'like', "%{$v}%"))
            ->orderBy($request->sort ?? 'created_at', $request->direction ?? 'desc')
            ->paginate($request->per_page ?? 15);

        return PostResource::collection($posts);
    }

    public function show(Post $post): JsonResource
    {
        $post->load('author', 'categories', 'tags');

        return new PostResource($post);
    }

    public function store(Request $request): JsonResource
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:posts',
            'content' => 'nullable',
            'excerpt' => 'nullable|max:500',
            'status' => 'in:draft,published,archived',
            'type' => 'in:post,page',
        ]);

        $validated['author_id'] = $request->user()->id;

        $post = Post::create($validated);

        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        $post->load('author', 'categories', 'tags');

        return new PostResource($post);
    }

    public function update(Request $request, Post $post): JsonResource
    {
        $validated = $request->validate([
            'title' => 'sometimes|max:255',
            'slug' => 'sometimes|max:255|unique:posts,slug,' . $post->id,
            'content' => 'nullable',
            'excerpt' => 'nullable|max:500',
            'status' => 'in:draft,published,archived',
            'type' => 'in:post,page',
        ]);

        $post->update($validated);

        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        $post->load('author', 'categories', 'tags');

        return new PostResource($post);
    }

    public function destroy(Post $post): \Illuminate\Http\JsonResponse
    {
        $post->delete();

        return response()->json(['message' => 'Post deleted.']);
    }
}
