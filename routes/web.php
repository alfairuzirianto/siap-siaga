<?php

use App\Http\Controllers\BeritaAcaraController;
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
use App\Livewire\Peminjaman\Index as PeminjamanIndex;
use App\Livewire\Peminjaman\Form as PeminjamanForm;
use App\Livewire\Peminjaman\Show as PeminjamanShow;
use App\Livewire\Peminjaman\Validasi as Validasi;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {    
    Route::get('/', function () {
        if (auth()->user()->isAdmin()) return redirect()->route('dashboard');
        if (auth()->user()->isSupervisor()) return redirect()->route('validasi');
        if (auth()->user()->isPengguna()) return redirect()->route('peminjaman.index');
    });

    Route::middleware(['role:Admin'])->group(function () {
        Route::get('dashboard', Dashboard::class)->name('dashboard');
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

    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', PeminjamanIndex::class)->middleware(['role:Admin,Pengguna'])->name('index');
        Route::get('create', PeminjamanForm::class)->middleware(['role:Pengguna'])->name('create');
        Route::get('{peminjaman}', PeminjamanShow::class)->middleware(['can:view,peminjaman'])->name('show');
    });
    
    Route::get('validasi', Validasi::class)->middleware(['role:Supervisor'])->name('validasi');
    Route::get('/berita-acara/{ba}/download', [BeritaAcaraController::class, 'download'])
        ->middleware(['role:Admin,Supervisor'])->name('ba.download');
});

Route::get('verify/{token}', [BeritaAcaraController::class, 'verify'])->name('ba.verify');

require __DIR__.'/auth.php';
