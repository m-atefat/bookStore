<?php

namespace App\Filters;

use Closure;

class SearchAuthors
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('authors')) {
            return $next($request);
        }

        return $next($request)->whereHas('authors', function ($query) {
            $authorIds = explode(',', request()->input('authors'));
            $query->whereIn('id', $authorIds);
        });
    }
}
