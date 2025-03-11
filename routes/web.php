<?php

use App\Http\Controllers\MosqueCommitteeController;
use App\Http\Controllers\MosqueCommunityMemberController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Middleware\EnsurePhoneIsVerified;
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

// Routes that only require authentication
Route::middleware(['auth'])->group(function () {
    // Mosque creation route - accessible after phone verification
    Route::get('/masjid/create', [MosqueController::class, 'create'])->name('masjid.create');
    Route::post('/masjid', [MosqueController::class, 'store'])->name('masjid.store');
});

// Routes that require phone verification
Route::middleware(['auth', 'verified', EnsurePhoneIsVerified::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Masjid routes (except create and store)
    Route::resource('masjid', MosqueController::class, ['parameters' => ['masjid' => 'mosque'], 'except' => ['create', 'store']])->names([
        'index' => 'masjid.index',
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

    // Masjid Event routes
    Route::resource('masjid.acara', EventController::class, ['parameters' => ['masjid' => 'mosque', 'acara' => 'acara']])->names([
        'index' => 'masjid.acara.index',
        'create' => 'masjid.acara.create',
        'store' => 'masjid.acara.store',
        'show' => 'masjid.acara.show',
        'edit' => 'masjid.acara.edit',
        'update' => 'masjid.acara.update',
        'destroy' => 'masjid.acara.destroy',
    ]);

    // Masjid Event Registration routes
    Route::resource('masjid.acara.pendaftaran', EventRegistrationController::class, ['parameters' => ['masjid' => 'mosque', 'acara' => 'acara', 'pendaftaran' => 'pendaftaran']])->names([
        'index' => 'masjid.acara.pendaftaran.index',
        'create' => 'masjid.acara.pendaftaran.create',
        'store' => 'masjid.acara.pendaftaran.store',
        'show' => 'masjid.acara.pendaftaran.show',
        'edit' => 'masjid.acara.pendaftaran.edit',
        'update' => 'masjid.acara.pendaftaran.update',
        'destroy' => 'masjid.acara.pendaftaran.destroy',
    ]);
    Route::patch('/masjid/{mosque}/acara/{acara}/pendaftaran/{pendaftaran}/attendance', [EventRegistrationController::class, 'markAttendance'])->name('masjid.acara.pendaftaran.attendance');

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('can:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/mosques/pending', [AdminController::class, 'pendingMosques'])->name('mosques.pending');
        Route::get('/mosques/all', [AdminController::class, 'allMosques'])->name('mosques.all');
        Route::get('/mosques/{mosque}/verify', [AdminController::class, 'verifyMosque'])->name('mosques.verify');
    });
});

require __DIR__.'/auth.php';
