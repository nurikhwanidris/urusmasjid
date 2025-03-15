<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\MosqueUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Controller for managing mosque community members (Ahli Kariah).
 */
class MosqueCommunityMemberController extends Controller
{
    /**
     * Display a listing of the community members.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request, Mosque $mosque)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        // Get filter parameters with defaults
        $status = $request->input('status');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_field', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Start building the query
        $query = MosqueUser::with('user')
            ->where('mosque_id', $mosque->id);

        // Filter by status if provided
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Search by name, ic_number, phone_number, or email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('ic_number', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $query->orderBy($sortField, $sortDirection);

        // Get paginated results
        $members = $query->paginate($perPage)
            ->withQueryString();

        // Get status options for filter dropdown
        $statuses = [
            ['value' => 'all', 'label' => 'Semua'],
            ['value' => 'active', 'label' => 'Aktif'],
            ['value' => 'pending', 'label' => 'Belum Disahkan'],
            ['value' => 'inactive', 'label' => 'Tidak Aktif'],
        ];

        return Inertia::render('Mosques/CommunityMembers/Index', [
            'mosque' => $mosque,
            'members' => $members,
            'filters' => [
                'status' => $status,
                'search' => $search,
                'per_page' => $perPage,
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
            ],
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new community member.
     *
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
     * Generate QR code for kariah registration
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function generateQR(Mosque $mosque, Request $request)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        // Generate registration URL
        $registrationUrl = route('masjid.kariah.register', $mosque);

        // Generate QR code as base64 encoded SVG
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($registrationUrl));

        // If PDF is requested
        if ($request->has('pdf')) {
            return $this->generateQRPdf($mosque, $qrCode);
        }

        return Inertia::render('Mosques/CommunityMembers/QR', [
            'mosque' => $mosque,
            'qrCode' => $qrCode,
        ]);
    }

    /**
     * Generate PDF version of QR code
     *
     * @param  \App\Models\Mosque  $mosque
     * @param  string  $qrCode
     * @return \Illuminate\Http\Response
     */
    private function generateQRPdf(Mosque $mosque, $qrCode)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.qr-code', [
            'mosque' => $mosque,
            'qrCode' => $qrCode,
        ]);

        return $pdf->download('qr-code-pendaftaran-kariah.pdf');
    }

    /**
     * Show registration form via QR code
     *
     * @param  \App\Models\Mosque  $mosque
     * @return \Inertia\Response
     */
    public function showRegistrationForm(Mosque $mosque)
    {
        return Inertia::render('Mosques/CommunityMembers/Register', [
            'mosque' => $mosque,
        ]);
    }

    /**
     * Store a newly created community member in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Mosque $mosque)
    {
        // For QR registrations, skip Gate check
        if (!$request->is('*/register')) {
            if (Gate::denies('update', $mosque)) {
                abort(403);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ic_number' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Set default status based on registration type
        $status = $request->is('*/register') ? 'pending' : ($request->input('status', 'active'));

        $member = MosqueUser::create([
            'mosque_id' => $mosque->id,
            'type' => 'community',
            'name' => $validated['name'],
            'ic_number' => $validated['ic_number'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'status' => $status,
            'notes' => $validated['notes'] ?? null,
            'joined_at' => now(),
        ]);

        $member->createUser();

        if ($request->is('*/register')) {
            return redirect()->back()->with('success', 'Pendaftaran anda telah berjaya dihantar. Sila tunggu pengesahan dari pihak masjid.');
        }

        return redirect()->route('masjid.kariah.show', [$mosque->id, $member->id])
            ->with('success', 'Ahli kariah berjaya ditambah.');
    }

    /**
     * Display the specified community member.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function show(Mosque $mosque, $id)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        $member = MosqueUser::where('mosque_id', $mosque->id)
            ->where('type', 'community')
            ->where('id', $id)
            ->firstOrFail();

        return Inertia::render('Mosques/CommunityMembers/Show', [
            'mosque' => $mosque,
            'member' => $member,
        ]);
    }

    /**
     * Show the form for editing the specified community member.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function edit(Mosque $mosque, $id)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        $member = MosqueUser::where('mosque_id', $mosque->id)
            ->where('type', 'community')
            ->where('id', $id)
            ->firstOrFail();

        return Inertia::render('Mosques/CommunityMembers/Edit', [
            'mosque' => $mosque,
            'member' => $member,
        ]);
    }

    /**
     * Update the specified community member in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Mosque $mosque, $id)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        $member = MosqueUser::where('mosque_id', $mosque->id)
            ->where('type', 'community')
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ic_number' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,pending,inactive',
            'notes' => 'nullable|string|max:1000',
        ]);

        $member->update($validated);

        return redirect()->route('masjid.kariah.show', [$mosque->id, $member->id])
            ->with('success', 'Maklumat ahli kariah berjaya dikemaskini.');
    }

    /**
     * Remove the specified community member from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mosque $mosque, $id)
    {
        if (Gate::denies('update', $mosque)) {
            abort(403);
        }

        $member = MosqueUser::where('mosque_id', $mosque->id)
            ->where('type', 'community')
            ->where('id', $id)
            ->firstOrFail();

        $member->delete();

        return redirect()->route('masjid.kariah.index', $mosque->id)
            ->with('success', 'Ahli kariah berjaya dipadam.');
    }
}
