<?php

namespace App\Providers;

use App\Core\SettingRegistry\SettingRegistry;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingRegistry::class, function () {
            return new SettingRegistry();
        });
        $this->app->alias(SettingRegistry::class, 'setting.registry');
    }

    public function boot(): void
    {
        $registry = app(SettingRegistry::class);

        $registry->register('core', [
            'app_name' => [
                'label' => 'Site Name',
                'type' => 'text',
                'default' => config('app.name', 'INOX'),
                'tab' => 'general',
                'rules' => 'required|max:255',
            ],
            'site_description' => [
                'label' => 'Tagline',
                'type' => 'text',
                'default' => config('app.description', ''),
                'tab' => 'general',
                'rules' => 'nullable|max:500',
            ],
            'project_type' => [
                'label' => 'Project Type',
                'type' => 'select',
                'options' => ['website' => 'Website / Blog', 'ecommerce' => 'E-commerce', 'api' => 'API / Headless'],
                'default' => config('inox.project_type', 'website'),
                'tab' => 'general',
                'rules' => 'in:website,ecommerce,api',
            ],
            'default_post_status' => [
                'label' => 'Default Post Status',
                'type' => 'select',
                'options' => ['draft' => 'Draft', 'published' => 'Published'],
                'default' => env('INOX_DEFAULT_POST_STATUS', 'draft'),
                'tab' => 'content',
                'rules' => 'in:draft,published',
            ],
            'posts_per_page' => [
                'label' => 'Posts Per Page',
                'type' => 'number',
                'default' => env('INOX_POSTS_PER_PAGE', 15),
                'tab' => 'content',
                'rules' => 'integer|min:5|max:100',
            ],
            'enable_excerpts' => [
                'label' => 'Enable excerpts',
                'type' => 'boolean',
                'default' => env('INOX_ENABLE_EXCERPTS', true),
                'tab' => 'content',
            ],
            'cache_page_enabled' => [
                'label' => 'Page cache',
                'type' => 'boolean',
                'default' => false,
                'tab' => 'cache',
            ],
            'cache_object_enabled' => [
                'label' => 'Object cache',
                'type' => 'boolean',
                'default' => false,
                'tab' => 'cache',
            ],
            'cache_fragment_enabled' => [
                'label' => 'Fragment cache',
                'type' => 'boolean',
                'default' => false,
                'tab' => 'cache',
            ],
            'cache_driver' => [
                'label' => 'Cache Driver',
                'type' => 'select',
                'options' => ['file' => 'File', 'database' => 'Database', 'redis' => 'Redis'],
                'default' => 'file',
                'tab' => 'cache',
                'rules' => 'in:file,database,redis',
            ],
            'feature_realtime' => [
                'label' => 'Realtime',
                'type' => 'boolean',
                'default' => false,
                'tab' => 'features',
            ],
            'feature_ai' => [
                'label' => 'AI Layer',
                'type' => 'boolean',
                'default' => false,
                'tab' => 'features',
            ],
        ]);

        $registry->autoload();
    }
}
