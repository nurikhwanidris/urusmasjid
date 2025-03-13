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
     */
    public function index(Request $request, Mosque $mosque)
    {
        $this->authorize('viewAny', [Announcement::class, $mosque]);

        $status = $request->input('status', 'published');

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
            default:
                $query->published();
        }

        // Filter by type if provided
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        // Search by title or content
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $query->orderBy('published_at', 'desc')
                              ->paginate(10)
                              ->withQueryString();

        return Inertia::render('Announcements/Index', [
            'mosque' => $mosque,
            'announcements' => $announcements,
            'filters' => $request->only(['status', 'type', 'search']),
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
