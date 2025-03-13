<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMosqueCommitteeRequest;
use App\Http\Requests\UpdateMosqueCommitteeRequest;
use App\Models\Mosque;
use App\Models\MosqueUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class MosqueCommitteeController extends Controller
{
    /**
     * Display a listing of the committee members for a mosque.
     *
     * @return \Inertia\Response
     */
    public function index(Mosque $mosque)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        $committees = MosqueUser::with('user')
            ->where('mosque_id', $mosque->id)
            ->where('type', 'committee')
            ->orderBy('role')
            ->get();

        return Inertia::render('Mosques/Committees/Index', [
            'mosque' => $mosque,
            'committees' => $committees,
        ]);
    }

    /**
     * Show the form for creating a new committee member.
     *
     * @return \Inertia\Response
     */
    public function create(Mosque $mosque)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        // Get common committee positions for dropdown
        $positions = [
            'Pengerusi' => 'Pengerusi',
            'Naib Pengerusi' => 'Naib Pengerusi',
            'Setiausaha' => 'Setiausaha',
            'Penolong Setiausaha' => 'Penolong Setiausaha',
            'Bendahari' => 'Bendahari',
            'Penolong Bendahari' => 'Penolong Bendahari',
            'AJK' => 'AJK',
            'Imam' => 'Imam',
            'Bilal' => 'Bilal',
            'Siak' => 'Siak',
        ];

        return Inertia::render('Mosques/Committees/Create', [
            'mosque' => $mosque,
            'positions' => $positions,
        ]);
    }

    /**
     * Store a newly created committee member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreMosqueCommitteeRequest $request, Mosque $mosque)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Check if a user with the provided email exists
            if (! empty($validated['email']) && empty($validated['user_id'])) {
                $user = User::where('email', $validated['email'])->first();

                if ($user) {
                    $validated['user_id'] = $user->id;
                }
            }

            // Create the committee member
            $committee = MosqueUser::create([
                'mosque_id' => $mosque->id,
                'user_id' => $validated['user_id'] ?? null,
                'type' => 'committee',
                'role' => $validated['role'],
                'name' => $validated['name'],
                'ic_number' => $validated['ic_number'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            DB::commit();

            return redirect()->route('masjid.jawatankuasa.index', $mosque)
                ->with('success', 'Ahli jawatankuasa berjaya ditambah!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to add committee member. '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified committee member.
     *
     * @return \Inertia\Response
     */
    public function show(Mosque $mosque, $id)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        $committee = MosqueUser::where('mosque_id', $mosque->id)
            ->where('type', 'committee')
            ->where('id', $id)
            ->firstOrFail();

        $committee->load('user');

        return Inertia::render('Mosques/Committees/Show', [
            'mosque' => $mosque,
            'committee' => $committee,
        ]);
    }

    /**
     * Show the form for editing the specified committee member.
     *
     * @return \Inertia\Response
     */
    public function edit(Mosque $mosque, $id)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        $committee = MosqueUser::where('mosque_id', $mosque->id)
            ->where('type', 'committee')
            ->where('id', $id)
            ->firstOrFail();

        // Get common committee positions for dropdown
        $positions = [
            'Pengerusi' => 'Pengerusi',
            'Naib Pengerusi' => 'Naib Pengerusi',
            'Setiausaha' => 'Setiausaha',
            'Penolong Setiausaha' => 'Penolong Setiausaha',
            'Bendahari' => 'Bendahari',
            'Penolong Bendahari' => 'Penolong Bendahari',
            'AJK' => 'AJK',
            'Imam' => 'Imam',
            'Bilal' => 'Bilal',
            'Siak' => 'Siak',
        ];

        return Inertia::render('Mosques/Committees/Edit', [
            'mosque' => $mosque,
            'committee' => $committee,
            'positions' => $positions,
        ]);
    }

    /**
     * Update the specified committee member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateMosqueCommitteeRequest $request, Mosque $mosque, $id)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        $committee = MosqueUser::where('mosque_id', $mosque->id)
            ->where('type', 'committee')
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Check if a user with the provided email exists
            if (! empty($validated['email']) && empty($validated['user_id'])) {
                $user = User::where('email', $validated['email'])->first();

                if ($user) {
                    $validated['user_id'] = $user->id;
                }
            }

            // Update the committee member
            $committee->fill([
                'user_id' => $validated['user_id'] ?? null,
                'role' => $validated['role'],
                'name' => $validated['name'],
                'ic_number' => $validated['ic_number'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);
            $committee->save();

            DB::commit();

            return redirect()->route('masjid.jawatankuasa.index', $mosque)
                ->with('success', 'Maklumat ahli jawatankuasa berjaya dikemaskini!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to update committee member. '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified committee member from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mosque $mosque, $id)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        $committee = MosqueUser::where('mosque_id', $mosque->id)
            ->where('type', 'committee')
            ->where('id', $id)
            ->firstOrFail();

        try {
            $committee->delete();

            return redirect()->route('masjid.jawatankuasa.index', $mosque)
                ->with('success', 'Ahli jawatankuasa berjaya dipadam!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete committee member. '.$e->getMessage()]);
        }
    }
}
