<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'name',
        'email',
        'phone',
        'status',
        'attendance_status',
        'notes',
        'registration_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attended_at' => 'datetime',
    ];

    /**
     * Get the event that owns the registration.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user that owns the registration.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the registration is confirmed.
     *
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if the registration is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the registration is cancelled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if the attendee has attended the event.
     *
     * @return bool
     */
    public function hasAttended(): bool
    {
        return $this->attendance_status === 'attended';
    }

    /**
     * Generate a unique registration number.
     *
     * @return string
     */
    public static function generateRegistrationNumber(): string
    {
        return 'REG-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    }
}
