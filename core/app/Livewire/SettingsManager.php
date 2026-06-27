<?php

namespace App\Livewire;

use App\Core\SettingRegistry\SettingRegistry;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class SettingsManager extends Component
{
    public string $tab = 'general';

    public array $moduleTabs = [];

    // General
    public string $siteName = '';
    public string $siteDescription = '';
    public string $projectType = 'website';

    // Content
    public string $defaultStatus = 'draft';
    public int $perPage = 15;
    public bool $enableExcerpts = true;

    // Cache
    public bool $pageCache = false;
    public bool $objectCache = false;
    public bool $fragmentCache = false;
    public string $cacheDriver = 'file';

    // Features
    public bool $featureRealtime = false;
    public bool $featureAi = false;

    // Mail
    public string $mailDriver = 'log';
    public string $mailHost = '';
    public int $mailPort = 587;
    public string $mailUsername = '';
    public string $mailPassword = '';
    public string $mailEncryption = 'tls';
    public string $mailFromAddress = '';
    public string $mailFromName = '';

    protected SettingRegistry $registry;

    public function boot(SettingRegistry $registry): void
    {
        $this->registry = $registry;
    }

    public function mount(): void
    {
        $this->siteName = setting('app_name', config('app.name', 'INOX'));
        $this->siteDescription = setting('site_description', config('app.description', ''));
        $this->projectType = setting('project_type', config('inox.project_type', 'website'));
        $this->defaultStatus = setting('default_post_status', 'draft');
        $this->perPage = (int) setting('posts_per_page', 15);
        $this->enableExcerpts = (bool) setting('enable_excerpts', true);
        $this->pageCache = (bool) setting('cache_page_enabled', false);
        $this->objectCache = (bool) setting('cache_object_enabled', false);
        $this->fragmentCache = (bool) setting('cache_fragment_enabled', false);
        $this->cacheDriver = setting('cache_driver', 'file');
        $this->featureRealtime = (bool) setting('feature_realtime', false);
        $this->featureAi = (bool) setting('feature_ai', false);

        $this->mailDriver = env('MAIL_MAILER', 'log') ?? 'log';
        $this->mailHost = env('MAIL_HOST', '') ?? '';
        $this->mailPort = (int) (env('MAIL_PORT', 587) ?? 587);
        $this->mailUsername = env('MAIL_USERNAME', '') ?? '';
        $this->mailPassword = env('MAIL_PASSWORD', '') ?? '';
        $this->mailEncryption = env('MAIL_ENCRYPTION', 'tls') ?? 'tls';
        $this->mailFromAddress = env('MAIL_FROM_ADDRESS', '') ?? '';
        $this->mailFromName = env('MAIL_FROM_NAME', '') ?? '';

        $this->moduleTabs = collect(app('module.engine')->getSettingsTabs())
            ->map(fn($t) => ['key' => $t['key'], 'label' => $t['label']])
            ->toArray();
    }

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function save(): void
    {
        match ($this->tab) {
            'general' => $this->saveGeneral(),
            'content' => $this->saveContent(),
            'cache' => $this->saveCache(),
            'features' => $this->saveFeatures(),
            'mail' => $this->saveMail(),
            default => null,
        };

        session()->flash('message', 'Settings saved.');
    }

    protected function saveGeneral(): void
    {
        $this->validate([
            'siteName' => 'required|max:255',
            'projectType' => 'in:website,ecommerce,api',
        ]);

        $this->registry->set('app_name', $this->siteName, 'core');
        $this->registry->set('site_description', $this->siteDescription, 'core');
        $this->registry->set('project_type', $this->projectType, 'core');

        config(['app.name' => $this->siteName]);
        config(['app.description' => $this->siteDescription]);
        config(['inox.project_type' => $this->projectType]);

        $this->registry->flushCache();
        $this->registry->autoload();
    }

    protected function saveContent(): void
    {
        $this->validate([
            'defaultStatus' => 'in:draft,published',
            'perPage' => 'integer|min:5|max:100',
        ]);

        $this->registry->set('default_post_status', $this->defaultStatus, 'core');
        $this->registry->set('posts_per_page', (string) $this->perPage, 'core');
        $this->registry->set('enable_excerpts', $this->enableExcerpts ? '1' : '0', 'core');

        $this->registry->flushCache();
        $this->registry->autoload();
    }

    protected function saveCache(): void
    {
        $this->registry->set('cache_page_enabled', $this->pageCache ? '1' : '0', 'core');
        $this->registry->set('cache_object_enabled', $this->objectCache ? '1' : '0', 'core');
        $this->registry->set('cache_fragment_enabled', $this->fragmentCache ? '1' : '0', 'core');
        $this->registry->set('cache_driver', $this->cacheDriver, 'core');

        config(['inox.cache.page.enabled' => $this->pageCache]);
        config(['inox.cache.object.enabled' => $this->objectCache]);
        config(['inox.cache.fragment.enabled' => $this->fragmentCache]);

        $this->registry->flushCache();
        $this->registry->autoload();
    }

    protected function saveFeatures(): void
    {
        $this->registry->set('feature_realtime', $this->featureRealtime ? '1' : '0', 'core');
        $this->registry->set('feature_ai', $this->featureAi ? '1' : '0', 'core');

        config(['inox.features.realtime' => $this->featureRealtime]);
        config(['inox.features.ai' => $this->featureAi]);

        $this->registry->flushCache();
        $this->registry->autoload();
    }

    protected function saveMail(): void
    {
        $this->validate([
            'mailDriver' => 'in:log,smtp,sendmail,ses,postmark,mailgun',
            'mailHost' => 'nullable|max:255',
            'mailPort' => 'integer|min:1|max:65535',
            'mailEncryption' => 'in:tls,ssl,null',
        ]);

        $this->writeEnv('MAIL_MAILER', $this->mailDriver);
        $this->writeEnv('MAIL_HOST', $this->mailHost);
        $this->writeEnv('MAIL_PORT', (string) $this->mailPort);
        $this->writeEnv('MAIL_USERNAME', $this->mailUsername);
        $this->writeEnv('MAIL_PASSWORD', $this->mailPassword);
        $this->writeEnv('MAIL_ENCRYPTION', $this->mailEncryption === 'null' ? '' : $this->mailEncryption);
        $this->writeEnv('MAIL_FROM_ADDRESS', $this->mailFromAddress);
        $this->writeEnv('MAIL_FROM_NAME', $this->mailFromName);
    }

    protected function writeEnv(string $key, string $value): void
    {
        $envPath = cms_path('.env');
        if (!\Illuminate\Support\Facades\File::exists($envPath)) return;

        $env = \Illuminate\Support\Facades\File::get($envPath);

        if (str_contains($env, "$key=")) {
            $env = preg_replace("/^$key=.*/m", "$key=$value", $env);
        } else {
            $env .= "\n$key=$value";
        }

        \Illuminate\Support\Facades\File::put($envPath, $env);
    }

    public function render()
    {
        return view('livewire.settings-manager');
    }
}
