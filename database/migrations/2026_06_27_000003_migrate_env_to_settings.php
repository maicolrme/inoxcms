<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $existing = [
            'app_name' => env('APP_NAME', 'INOX'),
            'site_description' => env('APP_DESCRIPTION', ''),
            'project_type' => env('INOX_PROJECT_TYPE', 'website'),
            'default_post_status' => env('INOX_DEFAULT_POST_STATUS', 'draft'),
            'posts_per_page' => env('INOX_POSTS_PER_PAGE', 15),
            'enable_excerpts' => env('INOX_ENABLE_EXCERPTS', true),
            'cache_page_enabled' => env('INOX_CACHE_PAGE', false),
            'cache_object_enabled' => env('INOX_CACHE_OBJECT', false),
            'cache_fragment_enabled' => env('INOX_CACHE_FRAGMENT', false),
            'cache_driver' => env('INOX_CACHE_DRIVER', 'file'),
            'feature_realtime' => env('INOX_FEATURE_REALTIME', false),
            'feature_ai' => env('INOX_FEATURE_AI', false),
        ];

        foreach ($existing as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value,
                    'module' => 'core',
                    'group' => 'general',
                    'autoload' => true,
                ]
            );
        }

        // Migrate existing theme settings to use module column
        Setting::where('key', 'like', 'theme.%')
            ->whereNull('module')
            ->get()
            ->each(function (Setting $setting) {
                $parts = explode('.', $setting->key);
                if (count($parts) >= 4) {
                    $vendor = $parts[1];
                    $name = $parts[2];
                    $setting->module = 'theme.' . $vendor . '.' . $name;
                    $setting->save();
                }
            });
    }

    public function down(): void
    {
        Setting::where('module', 'core')->whereIn('key', [
            'app_name', 'site_description', 'project_type',
            'default_post_status', 'posts_per_page', 'enable_excerpts',
            'cache_page_enabled', 'cache_object_enabled', 'cache_fragment_enabled', 'cache_driver',
            'feature_realtime', 'feature_ai',
        ])->delete();
    }
};
