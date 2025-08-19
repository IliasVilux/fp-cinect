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
            Route::get('/', 'show')->name('show');
            Route::get('/{type}', 'showType')->name('showType');
        });

        Route::controller(ContentController::class)
            ->prefix('content')
            ->as('content.')
            ->group(function () {
                Route::get('/explore', 'showExplore')->name('explore');
                Route::get('/{id}', 'showDetail')->name('showDetail');
                Route::post('/{id}', 'storeReview')->name('storeReview');
            });

        Route::controller(ReviewController::class)
            ->prefix('review')
            ->as('review.')
            ->group(function () {
                Route::delete('/{review}', 'delete')->name('delete');
            });

        Route::controller(FavoriteListController::class)
            ->prefix('favorite-lists')
            ->as('favoriteLists.')
            ->group(function () {
                Route::get('my-lists', 'show')->name('show');
                Route::post('/', 'store')->name('store');
                Route::put('/{list}', 'update')->name('update');
                Route::delete('/{list}', 'delete')->name('delete');

                Route::post('/{list}/toggle-content/{content}', 'toggleContent')->name('toggleContent');
            });
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
