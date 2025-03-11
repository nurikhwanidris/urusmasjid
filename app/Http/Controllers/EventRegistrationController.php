<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EventRegistrationController extends Controller
{
    /**
     * Display a listing of the registrations for an event.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @return \Inertia\Response
     */
    public function index(Mosque $mosque, Event $acara)
    {
        $this->authorize('update', $mosque);

        $registrations = $acara->registrations()
            ->with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Events/Registrations/Index', [
            'mosque' => $mosque,
            'event' => $acara,
            'registrations' => $registrations,
        ]);
    }

    /**
     * Show the form for creating a new registration.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @return \Inertia\Response
     */
    public function create(Mosque $mosque, Event $acara)
    {
        // Check if registration is open
        if (!$acara->isRegistrationOpen() || $acara->isFull()) {
            return redirect()->route('masjid.acara.show', [$mosque->id, $acara->id])
                ->with('error', 'Pendaftaran untuk acara ini telah ditutup atau telah penuh.');
        }

        // Check if user is already registered
        if (Auth::check()) {
            $existingRegistration = $acara->registrations()
                ->where('user_id', Auth::id())
                ->first();

            if ($existingRegistration) {
                return redirect()->route('masjid.acara.show', [$mosque->id, $acara->id])
                    ->with('error', 'Anda telah mendaftar untuk acara ini.');
            }
        }

        return Inertia::render('Events/Registrations/Create', [
            'mosque' => $mosque,
            'event' => $acara,
        ]);
    }

    /**
     * Store a newly created registration in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Mosque $mosque, Event $acara)
    {
        // Check if registration is open
        if (!$acara->isRegistrationOpen() || $acara->isFull()) {
            return redirect()->route('masjid.acara.show', [$mosque->id, $acara->id])
                ->with('error', 'Pendaftaran untuk acara ini telah ditutup atau telah penuh.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Set user_id if authenticated
        $validated['user_id'] = Auth::id();
        $validated['event_id'] = $acara->id;
        $validated['status'] = 'confirmed';
        $validated['registration_number'] = EventRegistration::generateRegistrationNumber();

        $registration = EventRegistration::create($validated);

        return redirect()->route('masjid.acara.show', [$mosque->id, $acara->id])
            ->with('success', 'Pendaftaran anda telah berjaya. Nombor pendaftaran anda adalah ' . $registration->registration_number);
    }

    /**
     * Display the specified registration.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @param  \App\Models\EventRegistration  $registration
     * @return \Inertia\Response
     */
    public function show(Mosque $mosque, Event $acara, EventRegistration $pendaftaran)
    {
        // Check if user is authorized to view this registration
        if (Auth::id() !== $pendaftaran->user_id && !Auth::user()->can('update', $mosque)) {
            abort(403, 'Unauthorized action.');
        }

        return Inertia::render('Events/Registrations/Show', [
            'mosque' => $mosque,
            'event' => $acara,
            'registration' => $pendaftaran->load('user:id,name,email'),
        ]);
    }

    /**
     * Update the specified registration in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @param  \App\Models\EventRegistration  $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Mosque $mosque, Event $acara, EventRegistration $pendaftaran)
    {
        $this->authorize('update', $mosque);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'attendance_status' => 'nullable|in:registered,attended,absent',
            'notes' => 'nullable|string',
        ]);

        // Set attended_at timestamp if marked as attended
        if ($validated['attendance_status'] === 'attended' && $pendaftaran->attendance_status !== 'attended') {
            $validated['attended_at'] = now();
        }

        $pendaftaran->update($validated);

        return redirect()->route('masjid.acara.pendaftaran.index', [$mosque->id, $acara->id])
            ->with('success', 'Status pendaftaran telah dikemaskini.');
    }

    /**
     * Remove the specified registration from storage.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @param  \App\Models\EventRegistration  $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mosque $mosque, Event $acara, EventRegistration $pendaftaran)
    {
        // Check if user is authorized to delete this registration
        if (Auth::id() !== $pendaftaran->user_id && !Auth::user()->can('update', $mosque)) {
            abort(403, 'Unauthorized action.');
        }

        $pendaftaran->delete();

        if (Auth::user()->can('update', $mosque)) {
            return redirect()->route('masjid.acara.pendaftaran.index', [$mosque->id, $acara->id])
                ->with('success', 'Pendaftaran telah dipadam.');
        }

        return redirect()->route('masjid.acara.show', [$mosque->id, $acara->id])
            ->with('success', 'Pendaftaran anda telah dibatalkan.');
    }

    /**
     * Mark attendance for a registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @param  \App\Models\EventRegistration  $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAttendance(Request $request, Mosque $mosque, Event $acara, EventRegistration $pendaftaran)
    {
        $this->authorize('update', $mosque);

        $validated = $request->validate([
            'attendance_status' => 'required|in:registered,attended,absent',
        ]);

        // Set attended_at timestamp if marked as attended
        if ($validated['attendance_status'] === 'attended' && $pendaftaran->attendance_status !== 'attended') {
            $pendaftaran->attended_at = now();
        }

        $pendaftaran->attendance_status = $validated['attendance_status'];
        $pendaftaran->save();

        return redirect()->back()->with('success', 'Status kehadiran telah dikemaskini.');
    }
}
