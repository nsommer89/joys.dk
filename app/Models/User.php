<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, HasRoles, Notifiable;

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['Admin', 'System Admin']);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'username',
        'gender_id',
        'profile_photo_path',
        'cover_photo_path',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'cover_photo_url',
    ];

    public function getPlaceholderPhotoUrlAttribute(): string
    {
        $slug = $this->gender?->slug ?? 'mand';

        return match ($slug) {
            'kvinde' => \Illuminate\Support\Facades\Vite::asset('resources/assets/user-female-nophoto.jpg'),
            'par' => \Illuminate\Support\Facades\Vite::asset('resources/assets/usercouple-nophoto.jpg'),
            default => \Illuminate\Support\Facades\Vite::asset('resources/assets/user-male-nophoto.jpg'),
        };
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return \Illuminate\Support\Facades\Storage::url($this->profile_photo_path);
        }

        return $this->placeholder_photo_url;
    }

    public function getCoverPhotoUrlAttribute(): string
    {
        if ($this->cover_photo_path) {
            return \Illuminate\Support\Facades\Storage::url($this->cover_photo_path);
        }

        return \Illuminate\Support\Facades\Vite::asset('resources/assets/joys_design_bg.png');
    }

    public function userProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function gender(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function preferences(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Preference::class);
    }

    public function userAlbums(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserAlbum::class);
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }

    public function sentRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Friendship::class, 'sender_id');
    }

    public function receivedRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Friendship::class, 'recipient_id');
    }

    public function isFriendWith(User $user): bool
    {
        return Friendship::where(function ($q) use ($user) {
            $q->where(function ($query) use ($user) {
                $query->where('sender_id', $this->id)
                    ->where('recipient_id', $user->id);
            })->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('recipient_id', $this->id);
            });
        })->where('status', 'accepted')->exists();
    }

    public function hasBlocked(User $user): bool
    {
        return $this->sentRequests()
            ->where('recipient_id', $user->id)
            ->where('status', 'blocked')
            ->exists();
    }

    public function isBlockedBy(User $user): bool
    {
        // Check if user blocked me (they are sender, I am recipient, status blocked)
        return Friendship::where('sender_id', $user->id)
            ->where('recipient_id', $this->id)
            ->where('status', 'blocked')
            ->exists();
    }

    public function hasSentFriendRequestTo(User $user): bool
    {
        return $this->sentRequests()->where('recipient_id', $user->id)->where('status', 'pending')->exists();
    }

    public function hasReceivedFriendRequestFrom(User $user): bool
    {
        return $this->receivedRequests()->where('sender_id', $user->id)->where('status', 'pending')->exists();
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'sender_id', 'recipient_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
        // Note: This only gets friends where THIS user is sender. Proper fetch needs merging both directions.
    }

    public function isCurrentlyOnline(): bool
    {
        return true;
    }
}
