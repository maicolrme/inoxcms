<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Manage Settings',    'slug' => 'settings.manage',    'group' => 'Core'],
            ['name' => 'Manage Modules',     'slug' => 'modules.manage',     'group' => 'Core'],
            ['name' => 'Manage Roles',       'slug' => 'roles.manage',       'group' => 'Core'],
            ['name' => 'Manage Users',       'slug' => 'users.manage',       'group' => 'Core'],
            ['name' => 'View Dashboard',     'slug' => 'dashboard.view',     'group' => 'Core'],

            ['name' => 'Create Posts',       'slug' => 'content.create',     'group' => 'Content'],
            ['name' => 'Edit Posts',         'slug' => 'content.edit',       'group' => 'Content'],
            ['name' => 'Delete Posts',       'slug' => 'content.delete',     'group' => 'Content'],
            ['name' => 'Publish Posts',      'slug' => 'content.publish',    'group' => 'Content'],
            ['name' => 'Manage Categories',  'slug' => 'content.categories', 'group' => 'Content'],
            ['name' => 'Manage Tags',        'slug' => 'content.tags',       'group' => 'Content'],

            ['name' => 'Upload Media',       'slug' => 'media.upload',       'group' => 'Media'],
            ['name' => 'Edit Media',         'slug' => 'media.edit',         'group' => 'Media'],
            ['name' => 'Delete Media',       'slug' => 'media.delete',       'group' => 'Media'],
            ['name' => 'Manage Storage',     'slug' => 'media.storage',      'group' => 'Media'],

            ['name' => 'Manage API Tokens',  'slug' => 'api.tokens',         'group' => 'API'],
            ['name' => 'Manage API Settings', 'slug' => 'api.settings',      'group' => 'API'],
            ['name' => 'View API Logs',      'slug' => 'api.logs',           'group' => 'API'],

            ['name' => 'Manage Schemas',     'slug' => 'schema.manage',      'group' => 'Schema Studio'],
            ['name' => 'Manage Dynamic Models', 'slug' => 'schema.models',   'group' => 'Schema Studio'],

            ['name' => 'Manage Blog',        'slug' => 'blog.manage',        'group' => 'Blog'],
            ['name' => 'Manage Comments',    'slug' => 'blog.comments',      'group' => 'Blog'],

            ['name' => 'Manage SEO',         'slug' => 'seo.manage',         'group' => 'SEO'],
        ];

        foreach ($permissions as $data) {
            Permission::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Full access to all features', 'guard_name' => 'web']
        );
        $superAdmin->permissions()->sync(Permission::all()->pluck('id'));

        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Administrative access', 'guard_name' => 'web']
        );
        $admin->permissions()->sync(
            Permission::whereNotIn('slug', ['modules.manage'])->pluck('id')
        );

        $editor = Role::firstOrCreate(
            ['slug' => 'editor'],
            ['name' => 'Editor', 'description' => 'Can manage content and media', 'guard_name' => 'web']
        );
        $editor->permissions()->sync(
            Permission::whereIn('group', ['Content', 'Media'])->pluck('id')
        );

        $author = Role::firstOrCreate(
            ['slug' => 'author'],
            ['name' => 'Author', 'description' => 'Can create and edit own posts', 'guard_name' => 'web']
        );
        $author->permissions()->sync(
            Permission::whereIn('slug', ['content.create', 'content.edit', 'media.upload'])->pluck('id')
        );

        $user = User::first();
        if ($user && !$user->roles()->exists()) {
            $user->roles()->attach($superAdmin->id);
        }
    }
}
