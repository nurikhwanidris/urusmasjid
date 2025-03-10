<?php

use App\Http\Controllers\MosqueCommitteeController;
use App\Http\Controllers\MosqueCommunityMemberController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Masjid routes
    Route::resource('masjid', MosqueController::class, ['parameters' => ['masjid' => 'mosque']])->names([
        'index' => 'masjid.index',
        'create' => 'masjid.create',
        'store' => 'masjid.store',
        'show' => 'masjid.show',
        'edit' => 'masjid.edit',
        'update' => 'masjid.update',
        'destroy' => 'masjid.destroy',
    ]);
    Route::patch('/masjid/{mosque}/verify', [MosqueController::class, 'verify'])->name('masjid.verify');

    // Masjid Committee routes
    Route::resource('masjid.jawatankuasa', MosqueCommitteeController::class, ['parameters' => ['masjid' => 'mosque', 'jawatankuasa' => 'committee']])->names([
        'index' => 'masjid.jawatankuasa.index',
        'create' => 'masjid.jawatankuasa.create',
        'store' => 'masjid.jawatankuasa.store',
        'show' => 'masjid.jawatankuasa.show',
        'edit' => 'masjid.jawatankuasa.edit',
        'update' => 'masjid.jawatankuasa.update',
        'destroy' => 'masjid.jawatankuasa.destroy',
    ]);

    // Masjid Community Member (Ahli Kariah) routes
    Route::resource('masjid.kariah', MosqueCommunityMemberController::class, ['parameters' => ['masjid' => 'mosque', 'kariah' => 'kariah']])->names([
        'index' => 'masjid.kariah.index',
        'create' => 'masjid.kariah.create',
        'store' => 'masjid.kariah.store',
        'show' => 'masjid.kariah.show',
        'edit' => 'masjid.kariah.edit',
        'update' => 'masjid.kariah.update',
        'destroy' => 'masjid.kariah.destroy',
    ]);

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('can:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/mosques/pending', [AdminController::class, 'pendingMosques'])->name('mosques.pending');
        Route::get('/mosques/all', [AdminController::class, 'allMosques'])->name('mosques.all');
        Route::get('/mosques/{mosque}/verify', [AdminController::class, 'verifyMosque'])->name('mosques.verify');
    });
});

require __DIR__.'/auth.php';
