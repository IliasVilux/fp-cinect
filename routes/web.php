<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('dashboard/{category}', [DashboardController::class, 'indexCategory'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.category');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
