<?php

namespace App\Filters;

use Closure;

class SearchTitle
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('title')) {
            return $next($request);
        }

        return $next($request)->where('title', 'LIKE', '%' . request()->input('title') . '%');
    }
}
