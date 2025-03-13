<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mosque_id',
        'title',
        'description',
        'category',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'address',
        'is_online',
        'online_url',
        'registration_required',
        'registration_deadline',
        'max_participants',
        'contact_person',
        'contact_phone',
        'contact_email',
        'status',
        'featured_image',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
        'is_online' => 'boolean',
        'registration_required' => 'boolean',
        'max_participants' => 'integer',
    ];

    /**
     * Get the mosque that owns the event.
     */
    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    /**
     * Get the user who created the event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the registrations for the event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the volunteers for the event.
     */
    public function volunteers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_volunteers', 'event_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Check if the event is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->start_date > now();
    }

    /**
     * Check if the event is ongoing.
     */
    public function isOngoing(): bool
    {
        return $this->start_date <= now() && $this->end_date >= now();
    }

    /**
     * Check if the event is past.
     */
    public function isPast(): bool
    {
        return $this->end_date < now();
    }

    /**
     * Check if registration is open.
     */
    public function isRegistrationOpen(): bool
    {
        if (! $this->registration_required) {
            return false;
        }

        if ($this->registration_deadline) {
            return now() <= $this->registration_deadline;
        }

        return now() <= $this->start_date;
    }

    /**
     * Check if the event is full.
     */
    public function isFull(): bool
    {
        if (! $this->max_participants) {
            return false;
        }

        return $this->registrations()->count() >= $this->max_participants;
    }

    /**
     * Get the remaining slots for the event.
     */
    public function getRemainingSlots(): ?int
    {
        if (! $this->max_participants) {
            return null;
        }

        $registered = $this->registrations()->count();

        return max(0, $this->max_participants - $registered);
    }

    /**
     * Scope a query to only include upcoming events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    /**
     * Scope a query to only include ongoing events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Scope a query to only include past events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }
}
