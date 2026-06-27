<?php

namespace App\Livewire\Admin;

use App\Core\TemplateRegistry\TemplateRegistry;
use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class PageForm extends Component
{
    public ?int $pageId = null;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public string $excerpt = '';
    public string $status = 'draft';
    public string $template = '';
    public ?int $parentId = null;
    public bool $autoSlug = true;

    protected function rules(): array
    {
        return [
            'title' => 'required|min:2|max:255',
            'slug' => 'required|max:255|unique:posts,slug,' . $this->pageId,
            'content' => 'nullable',
            'excerpt' => 'nullable|max:500',
            'status' => 'required|in:draft,published,archived',
            'template' => 'nullable|max:255',
            'parentId' => 'nullable|exists:posts,id',
        ];
    }

    public function mount(?int $id = null): void
    {
        if ($id) {
            $post = Post::pages()->findOrFail($id);
            $this->pageId = $post->id;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->content = $post->content;
            $this->excerpt = $post->excerpt;
            $this->status = $post->status;
            $this->template = $post->template ?? '';
            $this->parentId = $post->parent_id;
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
            'type' => 'page',
            'template' => $this->template ?: null,
            'parent_id' => $this->parentId ?: null,
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->pageId) {
            $post = Post::pages()->findOrFail($this->pageId);
            $post->update($data);
        } else {
            $data['author_id'] = auth()->id();
            $post = Post::create($data);
        }

        session()->flash('message', $this->pageId ? 'Page saved.' : 'Page created.');
        $this->redirect(route('admin.pages.index'), navigate: true);
    }

    #[Computed]
    public function templates(): array
    {
        return app(TemplateRegistry::class)->all();
    }

    public function render()
    {
        return view('livewire.admin.page-form', [
            'parents' => Post::pages()->where('id', '!=', $this->pageId)->orderBy('title')->get(),
        ]);
    }
}
