<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dashboard\Index as Dashboard;
use App\Livewire\Peralatan\Index as PeralatanIndex;
use App\Livewire\Peralatan\Form as PeralatanForm;
use App\Livewire\Peralatan\Show as PeralatanShow;
use App\Livewire\Pemeliharaan\Index as PemeliharaanIndex;
use App\Livewire\Pemeliharaan\Form as PemeliharaanForm;
use App\Livewire\Pemeliharaan\Show as PemeliharaanShow;
use App\Livewire\User\Index as UserIndex;
use App\Livewire\User\Form as UserForm;
use App\Livewire\ActivityLog\Index as ActivityLogIndex;
use App\Livewire\ActivityLog\Show as ActivityLogShow;
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

        Route::prefix('pemeliharaan')->name('pemeliharaan.')->group(function () {
            Route::get('/', PemeliharaanIndex::class)->name('index');
            Route::get('create', PemeliharaanForm::class)->name('create');
            Route::get('{pemeliharaan}/edit', PemeliharaanForm::class)->name('edit');
            Route::get('{pemeliharaan}', PemeliharaanShow::class)->name('show');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', UserIndex::class)->name('index');
            Route::get('create', UserForm::class)->name('create');
            Route::get('{user}/edit', UserForm::class)->name('edit');
        });

        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', ActivityLogIndex::class)->name('index');
            Route::get('{activityLog}', ActivityLogShow::class)->name('show');
        });

    });
});

require __DIR__.'/auth.php';
