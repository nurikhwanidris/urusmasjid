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

        if($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $userMosque = MosqueUser::where('user_id', $user->id)->first();
        $events = $userMosque->mosque->events;

        return Inertia::render('Dashboard', [
            'mosque' => $userMosque,
            'events' => $events,
        ]);
    }
}
