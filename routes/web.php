<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__.'/auth.php';
