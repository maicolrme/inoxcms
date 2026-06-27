<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class CategoryManager extends Component
{
    public ?int $editingId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public ?int $parentId = null;
    public bool $autoSlug = true;

    protected function rules(): array
    {
        return [
            'name' => 'required|min:2|max:255',
            'slug' => 'required|max:255|unique:categories,slug,' . $this->editingId,
            'description' => 'nullable|max:500',
            'parentId' => 'nullable|exists:categories,id',
        ];
    }

    public function updatedName(): void
    {
        if ($this->autoSlug) {
            $this->slug = \Illuminate\Support\Str::slug($this->name);
        }
    }

    public function startEdit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->parentId = $category->parent_id;
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'name', 'slug', 'description', 'parentId']);
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description ?: null,
            'parent_id' => $this->parentId ?: null,
        ];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Category updated.');
        } else {
            Category::create($data);
            session()->flash('message', 'Category created.');
        }

        $this->cancelEdit();
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted.');
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categories' => Category::with('children')->whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }
}
