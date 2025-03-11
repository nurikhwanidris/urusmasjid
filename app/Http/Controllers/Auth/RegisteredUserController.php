<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone_number' => 'required|string|max:20',
            'ic_number' => 'nullable|string|max:20',
            'role' => 'required|string|in:admin,mosque_admin,community_member,volunteer,khatib',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Nama diperlukan.',
            'name.string' => 'Nama mestilah alfanumerik.',
            'name.max' => 'Nama mestilah tidak melebihi 255 karakter.',
            'email.required' => 'Email diperlukan.',
            'email.string' => 'Email mestilah alfanumerik.',
            'email.lowercase' => 'Email mestilah dalam huruf kecil.',
            'email.email' => 'Email mestilah dalam format yang sah.',
            'phone_number.required' => 'Nombor telefon diperlukan.',
            'phone_number.string' => 'Nombor telefon mestilah alfanumerik.',
            'phone_number.max' => 'Nombor telefon mestilah tidak melebihi 20 digit.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'ic_number' => $request->ic_number,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Generate a verification code for the user
        $user->generateVerificationCode();

        // Redirect to phone verification page
        return redirect('/verify-phone');
    }
}
