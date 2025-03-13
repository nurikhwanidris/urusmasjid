<?php

namespace App\Http\Controllers;

use App\Models\MosqueUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        $userMosque = MosqueUser::where('user_id', $user->id)->first();
        $upComingEvents = $userMosque->mosque->upcomingEvents;
        $khariah = $userMosque->mosque->communityMembers;
        $ajk = $userMosque->mosque->committeeMembers;

        return Inertia::render('Dashboard', [
            'mosque' => $userMosque->mosque,
            'upComingEvents' => $upComingEvents,
            'khariah' => $khariah,
            'ajk' => $ajk,
        ]);
    }
}
