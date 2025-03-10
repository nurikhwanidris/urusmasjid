<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\MosqueCommittee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use App\Http\Requests\StoreMosqueCommitteeRequest;
use App\Http\Requests\UpdateMosqueCommitteeRequest;

class MosqueCommitteeController extends Controller
{
    /**
     * Display a listing of the committee members for a mosque.
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function index(Mosque $mosque)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        $committees = MosqueCommittee::with('user')
            ->where('mosque_id', $mosque->id)
            ->orderBy('position')
            ->get();

        return Inertia::render('Mosques/Committees/Index', [
            'mosque' => $mosque,
            'committees' => $committees,
        ]);
    }

    /**
     * Show the form for creating a new committee member.
     *
     * @param  \App\Models\Mosque  $mosque
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
     * @param  \App\Models\Mosque  $mosque
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
            if (!empty($validated['email']) && empty($validated['user_id'])) {
                $user = User::where('email', $validated['email'])->first();

                if ($user) {
                    $validated['user_id'] = $user->id;
                }
            }

            // Create the committee member
            $committee = MosqueCommittee::create([
                'mosque_id' => $mosque->id,
                'user_id' => $validated['user_id'] ?? null,
                'position' => $validated['position'],
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
            return back()->withErrors(['error' => 'Failed to add committee member. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified committee member.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\MosqueCommittee  $committee
     * @return \Inertia\Response
     */
    public function show(Mosque $mosque, MosqueCommittee $committee)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        if ($committee->mosque_id !== $mosque->id) {
            abort(404);
        }

        $committee->load('user');

        return Inertia::render('Mosques/Committees/Show', [
            'mosque' => $mosque,
            'committee' => $committee,
        ]);
    }

    /**
     * Show the form for editing the specified committee member.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\MosqueCommittee  $committee
     * @return \Inertia\Response
     */
    public function edit(Mosque $mosque, MosqueCommittee $committee)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        if ($committee->mosque_id !== $mosque->id) {
            abort(404);
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
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\MosqueCommittee  $committee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateMosqueCommitteeRequest $request, Mosque $mosque, MosqueCommittee $committee)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        if ($committee->mosque_id !== $mosque->id) {
            abort(404);
        }

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Check if a user with the provided email exists
            if (!empty($validated['email']) && empty($validated['user_id'])) {
                $user = User::where('email', $validated['email'])->first();

                if ($user) {
                    $validated['user_id'] = $user->id;
                }
            }

            // Update the committee member
            $committee->fill([
                'user_id' => $validated['user_id'] ?? null,
                'position' => $validated['position'],
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
            return back()->withErrors(['error' => 'Failed to update committee member. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified committee member from storage.
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  \App\Models\MosqueCommittee  $committee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mosque $mosque, MosqueCommittee $committee)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        if ($committee->mosque_id !== $mosque->id) {
            abort(404);
        }

        $committee->delete();

        return redirect()->route('masjid.jawatankuasa.index', $mosque)
            ->with('success', 'Ahli jawatankuasa berjaya dipadam!');
    }
}
