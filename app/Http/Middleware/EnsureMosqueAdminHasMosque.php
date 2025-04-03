<?php

namespace App\Http\Middleware;

use App\Models\MosqueUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureMosqueAdminHasMosque
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Only check for mosque_admin role
        if ($user && $user->role === 'mosque_admin') {
            // Check if user has any mosque as admin
            $hasMosque = MosqueUser::where('user_id', $user->id)
                ->where('type', 'admin')
                ->exists();

            // If no mosque found, redirect to mosque registration
            if (! $hasMosque) {
                return redirect()->route('masjid.create')
                    ->with('warning', 'Sila daftar masjid anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
