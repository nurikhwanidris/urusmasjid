<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'type', // 'admin' or 'committee'
        'role', // For admins: 'admin', 'manager', etc. For committee: specific position
        'is_primary', // For admins only
        'name', // For committee members
        'ic_number',
        'phone_number',
        'email', // For committee members
        'address', // For committee members
        'start_date', // For committee members
        'end_date', // For committee members
        'status', // For committee members: 'active', 'inactive', 'pending'
        'notes', // For committee members
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
    ];

    /**
     * Get the mosque that this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    /**
     * Get the user associated with this mosque user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is an admin user.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Check if this is a committee member.
     *
     * @return bool
     */
    public function isCommittee(): bool
    {
        return $this->type === 'committee';
    }

    /**
     * Check if the committee member is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        if ($this->type !== 'committee') {
            return false;
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
}
