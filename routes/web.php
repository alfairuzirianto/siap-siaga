<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dashboard\Index as Dashboard;
use App\Livewire\Peralatan\Index as PeralatanIndex;
use App\Livewire\Peralatan\Form as PeralatanForm;
use App\Livewire\Peralatan\Show as PeralatanShow;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::middleware(['role:Admin'])->group(function () {
        Route::prefix('peralatan')->name('peralatan.')->group(function () {
            Route::get('/', PeralatanIndex::class)->name('index');
            Route::get('create', PeralatanForm::class)->name('create');
            Route::get('{peralatan}/edit', PeralatanForm::class)->name('edit');
            Route::get('{peralatan}', PeralatanShow::class)->name('show');
        });
    });
});

require __DIR__.'/auth.php';
