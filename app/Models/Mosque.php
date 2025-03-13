<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mosque extends Model
{
    protected $fillable = [
        'name',
        'street_address',
        'address_line_2',
        'city',
        'district',
        'state',
        'postal_code',
        'location',
        'latitude',
        'longitude',
        'jakim_zone',
        'contact_number',
        'email',
        'website',
        'type',
        'created_by',
        'registration_number',
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user who created this mosque.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who verified this mosque.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get all mosque users (both admins and committee members).
     */
    public function mosqueUsers(): HasMany
    {
        return $this->hasMany(MosqueUser::class);
    }

    /**
     * Get the mosque admins.
     */
    public function admins(): HasMany
    {
        return $this->mosqueUsers()->where('type', 'admin');
    }

    /**
     * Get the committee members (AJK) of this mosque.
     */
    public function committeeMembers(): HasMany
    {
        return $this->mosqueUsers()->where('type', 'committee');
    }

    /**
     * Get the community members (kariah) of this mosque.
     */
    public function communityMembers(): HasMany
    {
        return $this->mosqueUsers()->where('type', 'community');
    }

    /**
     * Get the events of this mosque.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the announcements of this mosque.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Get the published announcements of this mosque.
     */
    public function publishedAnnouncements(): HasMany
    {
        return $this->announcements()->published();
    }

    /**
     * Get the upcoming events of this mosque.
     */
    public function upcomingEvents(): HasMany
    {
        return $this->events()->upcoming();
    }

    /**
     * Get the ongoing events of this mosque.
     */
    public function ongoingEvents(): HasMany
    {
        return $this->events()->ongoing();
    }

    /**
     * Get the past events of this mosque.
     */
    public function pastEvents(): HasMany
    {
        return $this->events()->past();
    }

    /**
     * Check if the mosque is verified.
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Check if the mosque is pending verification.
     */
    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Check if the mosque is rejected.
     */
    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    /**
     * Get the full address as a formatted string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->street_address,
            $this->address_line_2,
            $this->city,
            $this->district,
            $this->postal_code.' '.$this->state,
        ];

        // Filter out empty parts
        $parts = array_filter($parts, function ($part) {
            return ! empty($part);
        });

        return implode(', ', $parts);
    }
}
