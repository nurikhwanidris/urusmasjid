<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

/**
 * Controller for admin-specific functions.
 */
class AdminController extends Controller
{
    /**
     * Display a dashboard for administrators.
     *
     * @return \Inertia\Response
     */
    public function dashboard()
    {
        if (Gate::denies('admin')) {
            abort(403);
        }

        // Get counts for dashboard
        $pendingMosquesCount = Mosque::where('verification_status', 'pending')->count();
        $verifiedMosquesCount = Mosque::where('verification_status', 'verified')->count();
        $rejectedMosquesCount = Mosque::where('verification_status', 'rejected')->count();
        $totalMosquesCount = Mosque::count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'pendingMosques' => $pendingMosquesCount,
                'verifiedMosques' => $verifiedMosquesCount,
                'rejectedMosques' => $rejectedMosquesCount,
                'totalMosques' => $totalMosquesCount,
            ],
        ]);
    }

    /**
     * Display a list of mosques pending verification.
     *
     * @return \Inertia\Response
     */
    public function pendingMosques()
    {
        if (Gate::denies('admin')) {
            abort(403);
        }

        $mosques = Mosque::where('verification_status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/PendingMosques', [
            'mosques' => $mosques,
        ]);
    }

    /**
     * Display a list of all mosques with their verification status.
     *
     * @return \Inertia\Response
     */
    public function allMosques()
    {
        if (Gate::denies('admin')) {
            abort(403);
        }

        $mosques = Mosque::with(['user', 'verifier'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/AllMosques', [
            'mosques' => $mosques,
        ]);
    }

    /**
     * Show the form for verifying a mosque.
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function verifyMosque(Mosque $mosque)
    {
        if (Gate::denies('admin')) {
            abort(403);
        }

        $mosque->load(['user', 'admins.user']);

        return Inertia::render('Admin/VerifyMosque', [
            'mosque' => $mosque,
        ]);
    }
}
