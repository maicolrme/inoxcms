<?php

namespace App\Livewire\Admin;

use App\Models\Tag;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class TagManager extends Component
{
    public ?int $editingId = null;
    public string $name = '';
    public string $slug = '';
    public bool $autoSlug = true;

    protected function rules(): array
    {
        return [
            'name' => 'required|min:2|max:255',
            'slug' => 'required|max:255|unique:tags,slug,' . $this->editingId,
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
        $tag = Tag::findOrFail($id);
        $this->editingId = $tag->id;
        $this->name = $tag->name;
        $this->slug = $tag->slug;
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'name', 'slug']);
    }

    public function save(): void
    {
        $this->validate();

        $data = ['name' => $this->name, 'slug' => $this->slug];

        if ($this->editingId) {
            Tag::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Tag updated.');
        } else {
            Tag::create($data);
            session()->flash('message', 'Tag created.');
        }

        $this->cancelEdit();
    }

    public function delete(int $id): void
    {
        Tag::findOrFail($id)->delete();
        session()->flash('message', 'Tag deleted.');
    }

    public function render()
    {
        return view('livewire.admin.tag-manager', [
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }
}
