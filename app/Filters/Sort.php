<?php

namespace App\Filters;

use App\Exceptions\SortTypeDoesNotSupprot;
use Closure;
use Illuminate\Support\Facades\Log;

class Sort
{
    /**
     * @throws SortTypeDoesNotSupprot
     */
    public function handle($request, Closure $next)
    {
        if (!request()->has('sortColumn')) {
            return $next($request);
        }

        $sortDirection = request()->has('sortDirection') ? request()->input('sortDirection') : 'asc';

        switch (request()->input('sortColumn')) {
            case 'title':
                return $next($request)->orderBy('title', $sortDirection);
                break;

            case 'avg_review':
                return $next($request)->orderBy('reviews_avg_review', $sortDirection);
                break;

            default:
                Log::error(request()->input('sortColumn') . 'does not support');
                throw new SortTypeDoesNotSupprot(request()->input('sortColumn') . 'does not support');
        }
    }
}
