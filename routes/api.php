<?php

use App\Http\Controllers\BooksController;
use Illuminate\Support\Facades\Route;

Route::prefix('books')
    ->controller(BooksController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store')->middleware('auth.admin');
        Route::post('/{book}/reviews', 'storeReview')->whereNumber('book')->middleware('auth');
    });



