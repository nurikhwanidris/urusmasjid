<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\CommunityMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

/**
 * Controller for managing mosque community members (Ahli Kariah).
 */
class MosqueCommunityMemberController extends Controller
{
    /**
     * Display a listing of the community members.
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function index(Mosque $mosque)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        $members = $mosque->communityMembers()
            ->orderBy('full_name')
            ->paginate(10);

        return Inertia::render('Mosques/CommunityMembers/Index', [
            'mosque' => $mosque,
            'members' => $members,
        ]);
    }

    /**
     * Show the form for creating a new community member.
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function create(Mosque $mosque)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        return Inertia::render('Mosques/CommunityMembers/Create', [
            'mosque' => $mosque,
        ]);
    }

    /**
     * Store a newly created community member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mosque  $mosque
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Mosque $mosque)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'ic_number' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'membership_status' => 'required|in:active,pending,inactive',
            'notes' => 'nullable|string|max:1000',
        ]);

        $member = $mosque->communityMembers()->create($validated);

        return redirect()->route('masjid.kariah.show', [$mosque->id, $member->id])
            ->with('success', 'Ahli kariah berjaya ditambah.');
    }

    /**
     * Display the specified community member.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\CommunityMember  $kariah
     * @return \Inertia\Response
     */
    public function show(Mosque $mosque, CommunityMember $kariah)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        if ($kariah->mosque_id !== $mosque->id) {
            abort(404);
        }

        return Inertia::render('Mosques/CommunityMembers/Show', [
            'mosque' => $mosque,
            'member' => $kariah,
        ]);
    }

    /**
     * Show the form for editing the specified community member.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\CommunityMember  $kariah
     * @return \Inertia\Response
     */
    public function edit(Mosque $mosque, CommunityMember $kariah)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        if ($kariah->mosque_id !== $mosque->id) {
            abort(404);
        }

        return Inertia::render('Mosques/CommunityMembers/Edit', [
            'mosque' => $mosque,
            'member' => $kariah,
        ]);
    }

    /**
     * Update the specified community member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\CommunityMember  $kariah
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Mosque $mosque, CommunityMember $kariah)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        if ($kariah->mosque_id !== $mosque->id) {
            abort(404);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'ic_number' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'membership_status' => 'required|in:active,pending,inactive',
            'notes' => 'nullable|string|max:1000',
        ]);

        $kariah->update($validated);

        return redirect()->route('masjid.kariah.show', [$mosque->id, $kariah->id])
            ->with('success', 'Maklumat ahli kariah berjaya dikemaskini.');
    }

    /**
     * Remove the specified community member from storage.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\CommunityMember  $kariah
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mosque $mosque, CommunityMember $kariah)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        if ($kariah->mosque_id !== $mosque->id) {
            abort(404);
        }

        $kariah->delete();

        return redirect()->route('masjid.kariah.index', $mosque->id)
            ->with('success', 'Ahli kariah berjaya dipadam.');
    }
}
