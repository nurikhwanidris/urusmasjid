<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\MosqueAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class MosqueController extends Controller
{
    /**
     * Display a listing of the mosques.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();

        // If user is system admin, show all mosques
        if ($user->role === 'admin') {
            $mosques = Mosque::with('user')->latest()->paginate(10);
        } else {
            // Show mosques where user is an admin
            $mosqueIds = MosqueAdmin::where('user_id', $user->id)->pluck('mosque_id');
            $mosques = Mosque::with('user')->whereIn('id', $mosqueIds)->latest()->paginate(10);
        }

        return Inertia::render('Mosques/Index', [
            'mosques' => $mosques,
        ]);
    }

    /**
     * Show the form for creating a new mosque.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        if (Gate::denies('create', Mosque::class)) {
            abort(403);
        }

        return Inertia::render('Mosques/Create');
    }

    /**
     * Store a newly created mosque in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('create', Mosque::class)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:masjid,surau',
            'registration_number' => 'nullable|string|max:50',

            // Address fields
            'street_address' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'jakim_zone' => 'required|string|max:50',

            // Contact fields
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',

            // Admin fields
            'ic_number' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
        ], [
            'name.required' => 'Nama masjid wajib diisi.',
            'type.required' => 'Jenis masjid wajib diisi.',
            'street_address.required' => 'Alamat jalan wajib diisi.',
            'city.required' => 'Bandar wajib diisi.',
            'district.required' => 'Daerah wajib diisi.',
            'state.required' => 'Negeri wajib diisi.',
            'postal_code.required' => 'Kod pos wajib diisi.',
            'location.required' => 'Lokasi wajib diisi.',
            'jakim_zone.required' => 'Zon JAKIM wajib diisi.',
            'ic_number.required' => 'Nombor IC wajib diisi.',
            'phone_number.required' => 'Nombor telefon wajib diisi.',
            'postal_code.max' => 'Poskod maksimum 10 digit.',
            'ic_number.max' => 'Nombor IC maksimum 20 digit.',
            'phone_number.max' => 'Nombor telefon maksimum 20 digit.',
        ]);

        try {
            DB::beginTransaction();

            // Create the mosque
            $mosque = Mosque::create([
                'name' => $validated['name'],
                'street_address' => $validated['street_address'],
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city' => $validated['city'],
                'district' => $validated['district'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'location' => $validated['location'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'jakim_zone' => $validated['jakim_zone'],
                'contact_number' => $validated['contact_number'],
                'email' => $validated['email'],
                'website' => $validated['website'],
                'type' => $validated['type'],
                'registration_number' => $validated['registration_number'],
                'created_by' => Auth::id(),
                'verification_status' => 'pending',
            ]);

            // Create mosque admin record
            MosqueAdmin::create([
                'mosque_id' => $mosque->id,
                'user_id' => Auth::id(),
                'role' => 'admin',
                'is_primary' => true,
                'ic_number' => $validated['ic_number'],
                'phone_number' => $validated['phone_number'],
            ]);

            // Update user role if needed
            $user = Auth::user();
            if ($user->role === 'community_member') {
                User::where('id', $user->id)->update(['role' => 'mosque_admin']);
            }

            DB::commit();

            return redirect()->route('masjid.show', $mosque)
                ->with('success', 'Masjid berjaya didaftarkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to register mosque. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified mosque.
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function show(Mosque $mosque)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        $mosque->load(['user', 'admins.user', 'communityMembers']);

        // Load committee members
        $committees = $mosque->committeeMembers()
            ->orderBy('position')
            ->limit(5) // Limit to 5 for preview
            ->get();

        return Inertia::render('Mosques/Show', [
            'mosque' => $mosque,
            'committees' => $committees,
        ]);
    }

    /**
     * Show the form for editing the specified mosque.
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function edit(Mosque $mosque)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        return Inertia::render('Mosques/Edit', [
            'mosque' => $mosque,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mosque $mosque)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:masjid,surau',
            'registration_number' => 'nullable|string|max:50',

            // Address fields
            'street_address' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'jakim_zone' => 'required|string|max:50',

            // Contact fields
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ], [
            'name.required' => 'Nama masjid wajib diisi.',
            'type.required' => 'Jenis masjid wajib diisi.',
            'street_address.required' => 'Alamat jalan wajib diisi.',
            'city.required' => 'Bandar wajib diisi.',
            'district.required' => 'Daerah wajib diisi.',
            'state.required' => 'Negeri wajib diisi.',
            'postal_code.required' => 'Kod pos wajib diisi.',
            'location.required' => 'Lokasi wajib diisi.',
            'jakim_zone.required' => 'Zon JAKIM wajib diisi.',
            'ic_number.required' => 'Nombor IC wajib diisi.',
            'phone_number.required' => 'Nombor telefon wajib diisi.',
            'postal_code.max' => 'Poskod maksimum 10 digit.',
            'ic_number.max' => 'Nombor IC maksimum 20 digit.',
            'phone_number.max' => 'Nombor telefon maksimum 20 digit.',
        ]);

        $mosque->update($validated);

        return redirect()->route('masjid.show', $mosque)
            ->with('success', 'Maklumat masjid berjaya dikemaskini!');
    }

    /**
     * Remove the specified mosque from storage.
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mosque $mosque)
    {
        if (Gate::denies('delete', $mosque)) {
            abort(403);
        }

        $mosque->delete();

        return redirect()->route('masjid.index')
            ->with('success', 'Masjid berjaya dipadam!');
    }

    /**
     * Verify a mosque.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mosque  $mosque
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, Mosque $mosque)
    {
        if (Gate::denies('verify', $mosque)) {
            abort(403);
        }

        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $mosque->fill([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'],
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);
        $mosque->save();

        return back()->with('success', 'Mosque verification status updated successfully.');
    }
}
