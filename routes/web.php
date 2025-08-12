<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('dashboard/{category}', [DashboardController::class, 'indexCategory'])
            ->name('dashboard.category');

        Route::get('explore', [ContentController::class, 'explore'])
            ->name('explore');

        Route::get('content/{id}', [ContentController::class, 'detail'])->name('content.detail');
        Route::post('content/{id}', [ContentController::class, 'storeReview'])->name('content.store-review');

        Route::delete('review/{review}', [ReviewController::class, 'delete'])->name('review.delete');
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
