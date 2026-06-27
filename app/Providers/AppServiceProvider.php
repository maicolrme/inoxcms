<?php

namespace App\Providers;

use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\PageForm;
use App\Livewire\Admin\PageList;
use App\Livewire\Admin\PostForm;
use App\Livewire\Admin\PostList;
use App\Livewire\Admin\TagManager;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerPermissionGates();
        $this->registerLivewireComponents();
        $this->registerNav();
        $this->registerDashboardWidget();
    }

    protected function registerPermissionGates(): void
    {
        try {
            Permission::all()->each(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermission($permission->slug);
                });
            });
        } catch (\Throwable) {
            // DB not ready yet (migrations haven't run)
        }
    }

    protected function registerLivewireComponents(): void
    {
        if (! class_exists('\Livewire\Livewire')) return;

        Livewire::component('admin-post-list', PostList::class);
        Livewire::component('admin-post-form', PostForm::class);
        Livewire::component('admin-page-list', PageList::class);
        Livewire::component('admin-page-form', PageForm::class);
        Livewire::component('admin-category-manager', CategoryManager::class);
        Livewire::component('admin-tag-manager', TagManager::class);
    }

    protected function registerNav(): void
    {
        $engine = app('module.engine');
        if (! $engine) return;

        $engine->registerNav('core', [
            ['label' => 'Posts',      'route' => 'admin.posts.index',      'active' => 'admin.posts.*',      'subheading' => 'Content'],
            ['label' => 'Pages',      'route' => 'admin.pages.index',      'active' => 'admin.pages.*'],
            ['label' => 'Categories', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*'],
            ['label' => 'Tags',       'route' => 'admin.tags.index',       'active' => 'admin.tags.*'],
        ]);
    }

    protected function registerDashboardWidget(): void
    {
        $engine = app('module.engine');
        if (! $engine) return;

        $engine->registerDashboardWidget('core', 'recent-posts', 'Recent Posts', 'md:col-span-2', function () {
            $posts = \App\Models\Post::latest()->take(5)->get();
            $html = '<table class="w-full text-sm text-left">';
            $html .= '<thead><tr class="border-b"><th class="pb-2 font-medium text-gray-500">Title</th><th class="pb-2 font-medium text-gray-500">Status</th><th class="pb-2 font-medium text-gray-500">Date</th></tr></thead><tbody>';
            foreach ($posts as $post) {
                $badge = $post->status === 'published'
                    ? '<span class="inline-block px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">Published</span>'
                    : '<span class="inline-block px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Draft</span>';
                $html .= '<tr class="border-b last:border-0">';
                $html .= '<td class="py-2 text-gray-900">' . e($post->title) . '</td>';
                $html .= '<td class="py-2">' . $badge . '</td>';
                $html .= '<td class="py-2 text-gray-500">' . $post->created_at->format('M j, Y') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
            $html .= '<div class="mt-3"><a href="' . route('admin.posts.index') . '" class="text-sm text-blue-600 hover:underline">View all posts →</a></div>';
            return $html;
        });
    }
}
