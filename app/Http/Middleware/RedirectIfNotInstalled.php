<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('inox.installer.completed')) {
            return $next($request);
        }

        if ($request->is('install*')) {
            return $next($request);
        }

        return redirect()->route('installer.welcome');
    }
}
