<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MosqueUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mosque_id',
        'user_id',
        'type', // 'admin', 'committee', or 'community'
        'role', // For admins: 'admin', 'manager', etc. For committee: specific position
        'is_primary', // For admins only
        'name', // For committee members and community members
        'ic_number',
        'phone_number',
        'email', // For committee members and community members
        'address', // For committee members and community members
        'start_date', // For committee members
        'end_date', // For committee members
        'status', // For committee members and community members: 'active', 'inactive', 'pending'
        'notes', // For committee members and community members
        'joined_at', // For community members
        'relationship_status', // For community members: 'Bujang', 'Berkahwin', 'Duda', 'Janda'
        'occupation', // For community members
        'emergency_contact', // For community members
        'emergency_phone', // For community members
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'joined_at' => 'datetime',
    ];

    /**
     * Get the mosque that this user belongs to.
     */
    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    /**
     * Get the user associated with this mosque user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is an admin user.
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Check if this is a committee member.
     */
    public function isCommittee(): bool
    {
        return $this->type === 'committee';
    }

    /**
     * Check if this is a community member.
     */
    public function isCommunity(): bool
    {
        return $this->type === 'community';
    }

    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        if ($this->type === 'admin') {
            return true;
        }

        return $this->status === 'active';
    }

    /**
     * Scope a query to only include admin users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->where('type', 'admin');
    }

    /**
     * Scope a query to only include committee members.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommittee($query)
    {
        return $query->where('type', 'committee');
    }

    /**
     * Scope a query to only include community members.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommunity($query)
    {
        return $query->where('type', 'community');
    }

    /**
     * Get the events of the mosque this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function events()
    {
        return $this->mosque->events();
    }

    /**
     * Get the upcoming events of the mosque this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function upcomingEvents()
    {
        return $this->mosque->upcomingEvents;
    }

    /**
     * Get the ongoing events of the mosque this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function ongoingEvents()
    {
        return $this->mosque->ongoingEvents;
    }

    /**
     * Get the past events of the mosque this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pastEvents()
    {
        return $this->mosque->pastEvents;
    }

    /**
     * Get the announcements of the mosque this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function announcements()
    {
        return $this->mosque->announcements();
    }

    /**
     * Get the published announcements of the mosque this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function publishedAnnouncements()
    {
        return $this->mosque->publishedAnnouncements;
    }

    /**
     * Create the mosque user in user table.
     */
    public function createUser()
    {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make(Str::random(10)),
            'ic_number' => $this->ic_number,
            'phone_number' => $this->phone_number,
        ]);

        $this->user_id = $user->id;
        $this->save();
    }
}
