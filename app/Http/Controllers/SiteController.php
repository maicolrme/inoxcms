<?php

namespace App\Http\Controllers;

use App\Core\ThemeEngine\ThemeEngine;
class SiteController extends Controller
{
    public function index(ThemeEngine $engine)
    {
        $type = config('inox.project_type', 'website');

        $view = match ($type) {
            'ecommerce' => 'site.store',
            'api' => 'site.api',
            default => 'site.home',
        };

        return $engine->template($view);
    }
}
