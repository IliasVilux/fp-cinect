<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteListController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::controller(DashboardController::class)
        ->prefix('dashboard')
        ->as('dashboard.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{type}', 'showType')
                ->whereIn('type', ['movies', 'series', 'animes'])
                ->name('type');
        });

        Route::controller(ContentController::class)
            ->prefix('contents')
            ->as('contents.')
            ->group(function () {
                Route::get('/explore', 'explore')->name('explore');
                Route::get('/{id}', 'show')->name('show');
                Route::post('/{id}', 'storeReview')->name('storeReview');
            });

        Route::controller(ReviewController::class)
            ->prefix('reviews')
            ->as('reviews.')
            ->group(function () {
                Route::delete('/{review}', 'destroy')->name('destroy');
            });

        Route::controller(FavoriteListController::class)
            ->prefix('favorite-lists')
            ->as('favoriteLists.')
            ->group(function () {
                Route::get('my', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{list}', 'update')->name('update');
                Route::delete('/{list}', 'destroy')->name('destroy');

                Route::post('/{list}/toggle-content/{content}', 'toggleContent')->name('toggleContent');
            });
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
