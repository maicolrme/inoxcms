<?php

namespace App\Livewire\Admin;

use App\Core\TemplateRegistry\TemplateRegistry;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class PostForm extends Component
{
    public ?int $postId = null;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public string $excerpt = '';
    public string $status = 'draft';
    public string $type = 'post';
    public string $template = '';
    public ?int $parentId = null;
    public array $selectedCategories = [];
    public array $selectedTags = [];
    public bool $autoSlug = true;

    protected function rules(): array
    {
        return [
            'title' => 'required|min:2|max:255',
            'slug' => 'required|max:255|unique:posts,slug,' . $this->postId,
            'content' => 'nullable',
            'excerpt' => 'nullable|max:500',
            'status' => 'required|in:draft,published,archived',
            'type' => 'required|in:post,page',
            'template' => 'nullable|max:255',
            'parentId' => 'nullable|exists:posts,id',
            'selectedCategories' => 'array',
            'selectedTags' => 'array',
        ];
    }

    public function mount(?int $id = null): void
    {
        if ($id) {
            $post = Post::findOrFail($id);
            $this->postId = $post->id;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->content = $post->content;
            $this->excerpt = $post->excerpt;
            $this->status = $post->status;
            $this->type = $post->type;
            $this->template = $post->template ?? '';
            $this->parentId = $post->parent_id;
            $this->selectedCategories = $post->categories->pluck('id')->map(fn($id) => (string) $id)->toArray();
            $this->selectedTags = $post->tags->pluck('id')->map(fn($id) => (string) $id)->toArray();
        }
    }

    public function updatedTitle(): void
    {
        if ($this->autoSlug) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'type' => $this->type,
            'template' => $this->template ?: null,
            'parent_id' => $this->parentId ?: null,
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update($data);
        } else {
            $data['author_id'] = auth()->id();
            $post = Post::create($data);
        }

        $post->categories()->sync($this->selectedCategories);
        $post->tags()->sync($this->selectedTags);

        $route = $this->type === 'page' ? 'admin.pages.index' : 'admin.posts.index';
        session()->flash('message', $this->postId ? 'Saved.' : 'Created.');
        $this->redirect(route($route), navigate: true);
    }

    #[Computed]
    public function templates(): array
    {
        return app(TemplateRegistry::class)->all();
    }

    public function render()
    {
        return view('livewire.admin.post-form', [
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'parents' => Post::pages()->where('id', '!=', $this->postId)->orderBy('title')->get(),
        ]);
    }
}
