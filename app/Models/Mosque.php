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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who verified this mosque.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the mosque admins.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function admins(): HasMany
    {
        return $this->hasMany(MosqueAdmin::class);
    }

    /**
     * Get the community members (kariah) of this mosque.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function communityMembers(): HasMany
    {
        return $this->hasMany(CommunityMember::class);
    }

    /**
     * Get the committee members (AJK) of this mosque.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function committeeMembers(): HasMany
    {
        return $this->hasMany(MosqueCommittee::class);
    }

    /**
     * Get the events of this mosque.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Check if the mosque is verified.
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Check if the mosque is pending verification.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Check if the mosque is rejected.
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    /**
     * Get the full address as a formatted string.
     *
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->street_address,
            $this->address_line_2,
            $this->city,
            $this->district,
            $this->postal_code . ' ' . $this->state,
        ];

        // Filter out empty parts
        $parts = array_filter($parts, function ($part) {
            return !empty($part);
        });

        return implode(', ', $parts);
    }
}
