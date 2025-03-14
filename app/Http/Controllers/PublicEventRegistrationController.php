<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Mosque;
use App\Models\MosqueUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * Controller for handling public event registrations via QR code.
 */
class PublicEventRegistrationController extends Controller
{
    /**
     * Show the public registration form for an event.
     *
     * @param  string  $eventId
     * @return \Inertia\Response
     */
    public function showRegistrationForm($eventId)
    {
        $event = Event::with('mosque')->findOrFail($eventId);

        // Check if registration is open
        if (! $event->isRegistrationOpen() || $event->isFull()) {
            return Inertia::render('Events/PublicRegistration/Closed', [
                'event' => $event,
                'mosque' => $event->mosque,
                'message' => $event->isFull() ? 'Pendaftaran untuk acara ini telah penuh.' : 'Pendaftaran untuk acara ini telah ditutup.',
            ]);
        }

        return Inertia::render('Events/PublicRegistration/Form', [
            'event' => $event,
            'mosque' => $event->mosque,
        ]);
    }

    /**
     * Process the public registration for an event.
     *
     * @param  string  $eventId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processRegistration(Request $request, $eventId)
    {
        $event = Event::with('mosque')->findOrFail($eventId);

        // Check if registration is open
        if (! $event->isRegistrationOpen() || $event->isFull()) {
            return back()->with('error', 'Pendaftaran untuk acara ini telah ditutup atau telah penuh.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'ic_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'join_kariah' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Begin transaction to ensure both registrations succeed or fail together
        DB::beginTransaction();

        try {
            // Create event registration
            $registrationData = [
                'event_id' => $event->id,
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'status' => 'confirmed',
                'notes' => $validated['notes'] ?? null,
                'registration_number' => EventRegistration::generateRegistrationNumber(),
            ];

            // Set user_id if authenticated
            if (Auth::check()) {
                $registrationData['user_id'] = Auth::id();
            }

            $registration = EventRegistration::create($registrationData);

            // If user wants to join as kariah member
            if ($validated['join_kariah'] ?? false) {
                // Check if already a kariah member
                $existingMember = MosqueUser::where('mosque_id', $event->mosque_id)
                    ->where('type', 'community')
                    ->where(function ($query) use ($validated) {
                        $query->where('phone_number', $validated['phone']);

                        if (! empty($validated['email'])) {
                            $query->orWhere('email', $validated['email']);
                        }

                        if (! empty($validated['ic_number'])) {
                            $query->orWhere('ic_number', $validated['ic_number']);
                        }
                    })
                    ->first();

                if (! $existingMember) {
                    // Create new kariah member
                    $mosqueUser = MosqueUser::create([
                        'mosque_id' => $event->mosque_id,
                        'type' => 'community',
                        'name' => $validated['name'],
                        'ic_number' => $validated['ic_number'] ?? null,
                        'phone_number' => $validated['phone'],
                        'email' => $validated['email'] ?? null,
                        'address' => $validated['address'] ?? null,
                        'status' => 'pending', // Pending approval by mosque admin
                        'notes' => 'Registered via event: '.$event->title,
                        'joined_at' => now(),
                    ]);

                    // Create corresponding user record
                    $mosqueUser->createUser();
                }
            }

            DB::commit();

            return Inertia::render('Events/PublicRegistration/Success', [
                'event' => $event,
                'mosque' => $event->mosque,
                'registration' => $registration,
                'joinedAsKariah' => $validated['join_kariah'] ?? false,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Pendaftaran gagal. Sila cuba lagi.');
        }
    }
}
