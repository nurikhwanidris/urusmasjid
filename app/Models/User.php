<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ic_number',
        'phone_number',
        'phone_verified',
        'phone_verified_at',
        'verification_code',
        'role', // 'admin', 'mosque_admin', 'community_member', 'volunteer', 'khatib'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_admin',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'phone_verified' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the is_admin attribute.
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the mosques created by this user.
     */
    public function createdMosques(): HasMany
    {
        return $this->hasMany(Mosque::class, 'created_by');
    }

    /**
     * Get the mosques verified by this user.
     */
    public function verifiedMosques(): HasMany
    {
        return $this->hasMany(Mosque::class, 'verified_by');
    }

    /**
     * Get the mosque admin roles of this user.
     */
    public function mosqueAdminRoles(): HasMany
    {
        return $this->hasMany(MosqueAdmin::class);
    }

    /**
     * Get the community memberships of this user.
     */
    public function communityMemberships(): HasMany
    {
        return $this->hasMany(CommunityMember::class);
    }

    /**
     * Get the committee positions of this user.
     */
    public function committeePositions(): HasMany
    {
        return $this->hasMany(MosqueCommittee::class);
    }

    /**
     * Check if the user is a system admin.
     */
    public function isSystemAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is an admin of the given mosque.
     *
     * @param  Mosque|int  $mosque
     */
    public function isMosqueAdmin($mosque): bool
    {
        $mosqueId = $mosque instanceof Mosque ? $mosque->id : $mosque;

        return $this->mosqueAdminRoles()->where('mosque_id', $mosqueId)->exists();
    }

    /**
     * Check if the user is a primary admin of the given mosque.
     *
     * @param  Mosque|int  $mosque
     */
    public function isPrimaryMosqueAdmin($mosque): bool
    {
        $mosqueId = $mosque instanceof Mosque ? $mosque->id : $mosque;

        return $this->mosqueAdminRoles()->where('mosque_id', $mosqueId)
            ->where('is_primary', true)
            ->exists();
    }

    /**
     * Check if the user is a committee member of the given mosque.
     *
     * @param  Mosque|int  $mosque
     */
    public function isCommitteeMember($mosque): bool
    {
        $mosqueId = $mosque instanceof Mosque ? $mosque->id : $mosque;

        return $this->committeePositions()->where('mosque_id', $mosqueId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if the user has the given role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if the user's phone is verified.
     */
    public function isPhoneVerified(): bool
    {
        return $this->phone_verified;
    }

    /**
     * Mark the user's phone as verified.
     */
    public function markPhoneAsVerified(): bool
    {
        return $this->forceFill([
            'phone_verified' => true,
            'phone_verified_at' => $this->freshTimestamp(),
            'verification_code' => null,
        ])->save();
    }

    /**
     * Generate a verification code for the user.
     */
    public function generateVerificationCode(): string
    {
        $code = sprintf('%06d', mt_rand(100000, 999999));

        $this->forceFill([
            'verification_code' => $code,
        ])->save();

        return $code;
    }

    /**
     * Get the mosque associated with the user.
     */
    public function mosque(): HasOne
    {
        return $this->hasOne(Mosque::class);
    }
}
