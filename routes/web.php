<?php

use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\LaporanController;
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
use App\Livewire\Laporan\Index as LaporanIndex;
use App\Livewire\Laporan\Show as LaporanShow;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {    
    Route::get('/', fn () => redirect()->route('dashboard'));

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

    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', PeminjamanIndex::class)->name('index');
        Route::get('create', PeminjamanForm::class)->name('create');
        Route::get('{peminjaman}', PeminjamanShow::class)->name('show');
    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', LaporanIndex::class)->name('index');
        Route::get('{jenis}', LaporanShow::class)->name('show');
        Route::get('{jenis}/download', [LaporanController::class, 'download'])->name('download');
    });

    Route::get('/berita-acara/{ba}/download', [BeritaAcaraController::class, 'download'])->name('ba.download');
});

Route::get('verify/{token}', [BeritaAcaraController::class, 'verify'])->name('ba.verify');

require __DIR__.'/auth.php';
