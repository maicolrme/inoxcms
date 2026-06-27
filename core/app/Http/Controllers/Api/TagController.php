<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagController extends Controller
{
    public function index(): JsonResource
    {
        return TagResource::collection(Tag::withCount('posts')->orderBy('name')->get());
    }

    public function show(Tag $tag): JsonResource
    {
        $tag->loadCount('posts');

        return new TagResource($tag);
    }

    public function store(Request $request): JsonResource
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:tags',
        ]);

        $tag = Tag::create($validated);

        return new TagResource($tag);
    }

    public function update(Request $request, Tag $tag): JsonResource
    {
        $validated = $request->validate([
            'name' => 'sometimes|max:255',
            'slug' => 'sometimes|max:255|unique:tags,slug,' . $tag->id,
        ]);

        $tag->update($validated);

        return new TagResource($tag);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json(['message' => 'Tag deleted.']);
    }
}
