<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'role', // 'admin', 'mosque_admin', 'community_member'
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get the is_admin attribute.
     *
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the mosques created by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdMosques(): HasMany
    {
        return $this->hasMany(Mosque::class, 'created_by');
    }

    /**
     * Get the mosques verified by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function verifiedMosques(): HasMany
    {
        return $this->hasMany(Mosque::class, 'verified_by');
    }

    /**
     * Get the mosque admin roles of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mosqueAdminRoles(): HasMany
    {
        return $this->hasMany(MosqueAdmin::class);
    }

    /**
     * Get the community memberships of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function communityMemberships(): HasMany
    {
        return $this->hasMany(CommunityMember::class);
    }

    /**
     * Get the committee positions of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function committeePositions(): HasMany
    {
        return $this->hasMany(MosqueCommittee::class);
    }

    /**
     * Check if the user is a system admin.
     *
     * @return bool
     */
    public function isSystemAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is an admin of the given mosque.
     *
     * @param Mosque|int $mosque
     * @return bool
     */
    public function isMosqueAdmin($mosque): bool
    {
        $mosqueId = $mosque instanceof Mosque ? $mosque->id : $mosque;
        return $this->mosqueAdminRoles()->where('mosque_id', $mosqueId)->exists();
    }

    /**
     * Check if the user is a primary admin of the given mosque.
     *
     * @param Mosque|int $mosque
     * @return bool
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
     * @param Mosque|int $mosque
     * @return bool
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
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}
