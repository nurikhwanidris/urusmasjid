<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the phone verification form.
     *
     * @return Response
     */
    public function showPhoneVerificationForm(): Response
    {
        $verificationCode = session('verification_code');
        return Inertia::render('Auth/VerifyPhone', [
            'verification_code' => $verificationCode,
            'status' => session('status'),
        ]);
    }

    /**
     * Send a verification code to the user's phone.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function sendVerificationCode(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Generate a verification code
        $code = $user->generateVerificationCode();

        // In a real application, you would send an SMS with the code here
        // For now, we'll just flash it to the session for demo purposes
        session()->flash('verification_code', $code);

        return redirect()->back()->with('status', 'Kod pengesahan telah dihantar ke telefon anda.');
    }

    /**
     * Verify the user's phone with the provided code.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function verifyPhone(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if ($user->verification_code === $request->code) {
            $user->markPhoneAsVerified();

            // Redirect based on role
            if ($user->role === 'admin' || $user->role === 'mosque_admin') {
                return redirect('/masjid/create');
            }

            return redirect('/dashboard');
        }

        return redirect()->back()->withErrors(['code' => 'Kod pengesahan tidak sah.']);
    }
}
