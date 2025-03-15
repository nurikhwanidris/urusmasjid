<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\MosqueCommitteeController;
use App\Http\Controllers\MosqueCommunityMemberController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicEventRegistrationController;
use App\Http\Middleware\EnsurePhoneIsVerified;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

// Public routes for event registration via QR code (no auth required)
Route::get('/events/{eventId}/register', [PublicEventRegistrationController::class, 'showRegistrationForm'])->name('events.public.register');
Route::post('/events/{eventId}/register', [PublicEventRegistrationController::class, 'processRegistration'])->name('events.public.register.store');

// PDF generation route - separate from Inertia routes
Route::get('/masjid/{mosque}/acara/{acara}/pdf', [EventController::class, 'generatePdf'])
    ->middleware(['auth', 'verified', EnsurePhoneIsVerified::class])
    ->name('masjid.acara.pdf');

// Routes that only require authentication
Route::middleware(['auth'])->group(function () {
    // Mosque creation route - accessible after phone verification
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
    Route::get('/masjid/{mosque}/settings', [MosqueController::class, 'settings'])->name('masjid.settings');

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

    // Masjid Announcement routes
    Route::resource('masjid.pengumuman', AnnouncementController::class, ['parameters' => ['masjid' => 'mosque', 'pengumuman' => 'announcement']])->names([
        'index' => 'masjid.pengumuman.index',
        'create' => 'masjid.pengumuman.create',
        'store' => 'masjid.pengumuman.store',
        'show' => 'masjid.pengumuman.show',
        'edit' => 'masjid.pengumuman.edit',
        'update' => 'masjid.pengumuman.update',
        'destroy' => 'masjid.pengumuman.destroy',
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
