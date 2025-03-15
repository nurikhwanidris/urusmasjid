<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mosque_id',
        'donor_id', // Optional, links to MosqueUser for registered donors
        'amount',
        'payment_method', // 'duitnow_qr', 'cash', 'bank_transfer', etc.
        'reference_number', // For online transactions
        'status', // 'pending', 'completed', 'failed'
        'purpose', // General purpose of the donation
        'notes',
        'donor_name', // For anonymous/non-registered donors
        'donor_phone', // For anonymous/non-registered donors
        'donor_email', // For anonymous/non-registered donors
        'receipt_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the mosque that owns the donation.
     */
    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    /**
     * Get the donor if registered.
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(MosqueUser::class, 'donor_id');
    }

    /**
     * Generate a unique receipt number.
     */
    public static function generateReceiptNumber(): string
    {
        $prefix = 'RCP';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));

        return "{$prefix}{$date}{$random}";
    }

    /**
     * Scope a query to only include completed donations.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending donations.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include failed donations.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include online donations.
     */
    public function scopeOnline($query)
    {
        return $query->whereIn('payment_method', ['duitnow_qr', 'bank_transfer']);
    }

    /**
     * Scope a query to only include offline donations.
     */
    public function scopeOffline($query)
    {
        return $query->where('payment_method', 'cash');
    }
}
