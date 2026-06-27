<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SiteController;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\PageForm;
use App\Livewire\Admin\PageList;
use App\Livewire\Admin\PostForm;
use App\Livewire\Admin\PostList;
use App\Livewire\Admin\TagManager;
use App\Livewire\ModulesManager;
use App\Livewire\SettingsManager;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'index']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('install')->name('installer.')->group(function () {
    Route::get('/', [InstallerController::class, 'welcome'])->name('welcome');
    Route::get('/type', [InstallerController::class, 'type'])->name('type');
    Route::post('/type', [InstallerController::class, 'postType'])->name('type.post');
    Route::get('/database', [InstallerController::class, 'database'])->name('database');
    Route::post('/database', [InstallerController::class, 'postDatabase'])->name('database.post');
    Route::get('/features', [InstallerController::class, 'features'])->name('features');
    Route::post('/features', [InstallerController::class, 'postFeatures'])->name('features.post');
    Route::get('/admin', [InstallerController::class, 'admin'])->name('admin');
    Route::post('/admin', [InstallerController::class, 'postAdmin'])->name('admin.post');
    Route::get('/complete', [InstallerController::class, 'complete'])->name('complete');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', SettingsManager::class)->name('settings');
    Route::get('/modules', ModulesManager::class)->name('modules');
    Route::get('/roles', \App\Livewire\RoleManager::class)->name('roles');
    Route::get('/users', \App\Livewire\UserRoleManager::class)->name('users');
    Route::get('/themes', \App\Livewire\ThemeManager::class)->name('themes');

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', PostList::class)->name('index');
        Route::get('/create', PostForm::class)->name('create');
        Route::get('/{id}/edit', PostForm::class)->name('edit');
    });

    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', PageList::class)->name('index');
        Route::get('/create', PageForm::class)->name('create');
        Route::get('/{id}/edit', PageForm::class)->name('edit');
    });

    Route::get('/categories', CategoryManager::class)->name('categories.index');
    Route::get('/tags', TagManager::class)->name('tags.index');
});

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/{path}', [PageController::class, 'show'])
    ->where('path', '^(?!login|install|admin|blog|api|storage|livewire|up$).*')
    ->name('pages.show');
