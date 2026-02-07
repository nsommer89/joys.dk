<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class UserAlbum extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->name);

                // Ensure unique slug for this user
                $originalSlug = $album->slug;
                $counter = 1;

                while (static::where('user_id', $album->user_id)
                    ->where('slug', $album->slug)
                    ->exists()) {
                    $album->slug = $originalSlug.'-'.$counter++;
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(UserAlbumImage::class);
    }

    public function isLocked(): bool
    {
        return ! empty($this->password);
    }

    public function getCoverImageAttribute(): ?UserAlbumImage
    {
        return $this->images()->where('is_processing', false)->first();
    }

    public function verifyPassword(string $password): bool
    {
        return \Hash::check($password, $this->password);
    }
}
