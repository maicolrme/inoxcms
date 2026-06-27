<?php

namespace App\Http\Controllers;

use App\Core\TemplateRegistry\TemplateRegistry;
use App\Models\Post;

class PageController extends Controller
{
    public function show(string $path)
    {
        $segments = array_filter(explode('/', $path));
        $slug = end($segments);

        $page = Post::pages()
            ->with('children')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        if ($page->template && app(TemplateRegistry::class)->exists($page->template)) {
            $view = 'pages.' . $page->template;
            if (view()->exists($view)) {
                return view($view, ['page' => $page]);
            }
            $themeView = 'theme::' . $page->template;
            if (view()->exists($themeView)) {
                return view($themeView, ['page' => $page]);
            }
        }

        return view('pages.default', ['page' => $page]);
    }
}
