<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the events for a mosque.
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function index(Mosque $mosque)
    {
        $this->authorize('view', $mosque);

        $events = $mosque->events()
            ->with('creator:id,name')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return Inertia::render('Events/Index', [
            'mosque' => $mosque,
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new event.
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function create(Mosque $mosque)
    {
        $this->authorize('update', $mosque);

        return Inertia::render('Events/Create', [
            'mosque' => $mosque,
        ]);
    }

    /**
     * Store a newly created event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mosque  $mosque
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Mosque $mosque)
    {
        $this->authorize('update', $mosque);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'is_online' => 'boolean',
            'online_url' => 'nullable|url|max:255',
            'registration_required' => 'boolean',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'max_participants' => 'nullable|integer|min:1',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'featured_image' => 'nullable|image|max:2048',
            'speaker' => 'nullable|string|max:255',
            'speaker_bio' => 'nullable|string',
            'speaker_image' => 'nullable|image|max:2048',
            'volunteers' => 'nullable|array',
            'volunteers.*.id' => 'nullable|exists:users,id',
            'volunteers.*.role' => 'nullable|string|max:100',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('events', 'public');
            $validated['featured_image'] = $path;
        }

        // Handle speaker image upload
        if ($request->hasFile('speaker_image')) {
            $path = $request->file('speaker_image')->store('speakers', 'public');
            $validated['speaker_image'] = $path;
        }

        $validated['mosque_id'] = $mosque->id;
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'active';

        $event = Event::create($validated);

        // Attach volunteers if provided
        if (isset($validated['volunteers']) && is_array($validated['volunteers'])) {
            $volunteers = [];
            foreach ($validated['volunteers'] as $volunteer) {
                if (isset($volunteer['id'])) {
                    $volunteers[$volunteer['id']] = ['role' => $volunteer['role'] ?? 'Volunteer'];
                }
            }
            $event->volunteers()->attach($volunteers);
        }

        return redirect()->route('masjid.acara.show', [$mosque->id, $event->id])
            ->with('success', 'Acara telah berjaya dicipta.');
    }

    /**
     * Display the specified event.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @return \Inertia\Response
     */
    public function show(Mosque $mosque, Event $acara)
    {
        $this->authorize('view', $mosque);

        $event = $acara->load('creator:id,name', 'volunteers:id,name');

        // Get registration count
        $registrationCount = $event->registrations()->count();

        // Check if current user is registered
        $userRegistered = false;
        if (Auth::check()) {
            $userRegistered = $event->registrations()
                ->where('user_id', Auth::id())
                ->exists();
        }

        return Inertia::render('Events/Show', [
            'mosque' => $mosque,
            'event' => $event,
            'registrationCount' => $registrationCount,
            'userRegistered' => $userRegistered,
            'remainingSlots' => $event->getRemainingSlots(),
            'canRegister' => $event->isRegistrationOpen() && !$event->isFull(),
        ]);
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @return \Inertia\Response
     */
    public function edit(Mosque $mosque, Event $acara)
    {
        $this->authorize('update', $mosque);

        return Inertia::render('Events/Edit', [
            'mosque' => $mosque,
            'event' => $acara,
        ]);
    }

    /**
     * Update the specified event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Mosque $mosque, Event $acara)
    {
        $this->authorize('update', $mosque);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'is_online' => 'boolean',
            'online_url' => 'nullable|url|max:255',
            'registration_required' => 'boolean',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'max_participants' => 'nullable|integer|min:1',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,cancelled,completed',
            'speaker' => 'nullable|string|max:255',
            'speaker_bio' => 'nullable|string',
            'speaker_image' => 'nullable|image|max:2048',
            'volunteers' => 'nullable|array',
            'volunteers.*.id' => 'nullable|exists:users,id',
            'volunteers.*.role' => 'nullable|string|max:100',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($acara->featured_image) {
                Storage::disk('public')->delete($acara->featured_image);
            }

            $path = $request->file('featured_image')->store('events', 'public');
            $validated['featured_image'] = $path;
        }

        // Handle speaker image upload
        if ($request->hasFile('speaker_image')) {
            // Delete old image if exists
            if ($acara->speaker_image) {
                Storage::disk('public')->delete($acara->speaker_image);
            }

            $path = $request->file('speaker_image')->store('speakers', 'public');
            $validated['speaker_image'] = $path;
        }

        $acara->update($validated);

        // Update volunteers if provided
        if (isset($validated['volunteers']) && is_array($validated['volunteers'])) {
            $volunteers = [];
            foreach ($validated['volunteers'] as $volunteer) {
                if (isset($volunteer['id'])) {
                    $volunteers[$volunteer['id']] = ['role' => $volunteer['role'] ?? 'Volunteer'];
                }
            }
            $acara->volunteers()->sync($volunteers);
        }

        return redirect()->route('masjid.acara.show', [$mosque->id, $acara->id])
            ->with('success', 'Acara telah berjaya dikemaskini.');
    }

    /**
     * Remove the specified event from storage.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mosque $mosque, Event $acara)
    {
        $this->authorize('update', $mosque);

        // Delete featured image if exists
        if ($acara->featured_image) {
            Storage::disk('public')->delete($acara->featured_image);
        }

        $acara->delete();

        return redirect()->route('masjid.acara.index', $mosque->id)
            ->with('success', 'Acara telah berjaya dipadam.');
    }
}
