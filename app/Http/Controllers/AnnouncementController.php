<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request, Mosque $mosque)
    {
        $this->authorize('viewAny', [Announcement::class, $mosque]);

        // Get filter parameters with defaults
        $status = $request->input('status', 'published');
        $type = $request->input('type');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_field', 'published_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Start building the query
        $query = $mosque->announcements();

        // Filter by status
        switch ($status) {
            case 'published':
                $query->published();
                break;
            case 'draft':
                $query->draft();
                break;
            case 'archived':
                $query->archived();
                break;
            case 'expired':
                $query->expired();
                break;
            case 'all':
                // No filter, show all
                break;
            default:
                $query->published();
        }

        // Filter by type if provided
        if ($type) {
            $query->where('type', $type);
        }

        // Search by title or content
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $query->orderBy($sortField, $sortDirection);

        // Get paginated results
        $announcements = $query->with('creator')
            ->paginate($perPage)
            ->withQueryString();

        // Get announcement types for filter dropdown
        $types = [
            ['value' => 'general', 'label' => 'Umum'],
            ['value' => 'important', 'label' => 'Penting'],
            ['value' => 'emergency', 'label' => 'Kecemasan'],
        ];

        // Get status options for filter dropdown
        $statuses = [
            ['value' => 'all', 'label' => 'Semua'],
            ['value' => 'published', 'label' => 'Terbit'],
            ['value' => 'draft', 'label' => 'Draf'],
            ['value' => 'archived', 'label' => 'Arkib'],
            ['value' => 'expired', 'label' => 'Tamat'],
        ];

        return Inertia::render('Announcements/Index', [
            'mosque' => $mosque,
            'announcements' => $announcements,
            'filters' => [
                'status' => $status,
                'type' => $type,
                'search' => $search,
                'per_page' => $perPage,
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
            ],
            'types' => $types,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(Mosque $mosque)
    {
        $this->authorize('create', [Announcement::class, $mosque]);

        return Inertia::render('Announcements/Create', [
            'mosque' => $mosque,
        ]);
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request, Mosque $mosque)
    {
        $this->authorize('create', [Announcement::class, $mosque]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,important,emergency',
            'status' => 'required|string|in:draft,published',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:published_at',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        // Handle the featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('announcements', 'public');
            $validated['featured_image'] = $path;
        }

        // Set published_at to now if status is published and published_at is not provided
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Create the announcement
        $announcement = $mosque->announcements()->create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('mosques.announcements.show', [$mosque, $announcement])
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Mosque $mosque, Announcement $announcement)
    {
        $this->authorize('view', [$announcement, $mosque]);

        return Inertia::render('Announcements/Show', [
            'mosque' => $mosque,
            'announcement' => $announcement->load('creator'),
        ]);
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Mosque $mosque, Announcement $announcement)
    {
        $this->authorize('update', [$announcement, $mosque]);

        return Inertia::render('Announcements/Edit', [
            'mosque' => $mosque,
            'announcement' => $announcement,
        ]);
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Mosque $mosque, Announcement $announcement)
    {
        $this->authorize('update', [$announcement, $mosque]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,important,emergency',
            'status' => 'required|string|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:published_at',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        // Handle the featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete the old image if it exists
            if ($announcement->featured_image) {
                Storage::disk('public')->delete($announcement->featured_image);
            }

            $path = $request->file('featured_image')->store('announcements', 'public');
            $validated['featured_image'] = $path;
        }

        // Set published_at to now if status is changed to published and published_at is not provided
        if ($validated['status'] === 'published' &&
            $announcement->status !== 'published' &&
            empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Update the announcement
        $announcement->update($validated);

        return redirect()->route('mosques.announcements.show', [$mosque, $announcement])
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Mosque $mosque, Announcement $announcement)
    {
        $this->authorize('delete', [$announcement, $mosque]);

        // Delete the featured image if it exists
        if ($announcement->featured_image) {
            Storage::disk('public')->delete($announcement->featured_image);
        }

        $announcement->delete();

        return redirect()->route('mosques.announcements.index', $mosque)
            ->with('success', 'Announcement deleted successfully.');
    }
}
