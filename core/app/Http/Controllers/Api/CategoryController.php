<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function index(): JsonResource
    {
        return CategoryResource::collection(Category::withCount('posts')->orderBy('name')->get());
    }

    public function show(Category $category): JsonResource
    {
        $category->loadCount('posts');

        return new CategoryResource($category);
    }

    public function store(Request $request): JsonResource
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:categories',
        ]);

        $category = Category::create($validated);

        return new CategoryResource($category);
    }

    public function update(Request $request, Category $category): JsonResource
    {
        $validated = $request->validate([
            'name' => 'sometimes|max:255',
            'slug' => 'sometimes|max:255|unique:categories,slug,' . $category->id,
        ]);

        $category->update($validated);

        return new CategoryResource($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }
}
