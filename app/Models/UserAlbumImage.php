<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserAlbumImage extends Model
{
    protected $fillable = [
        'user_album_id',
        'hash',
        'path',
        'thumbnail_path',
        'is_processing',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->hash)) {
                $model->hash = \Illuminate\Support\Str::random(16);
            }
        });
    }

    protected $casts = [
        'is_processing' => 'boolean',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(UserAlbum::class, 'user_album_id');
    }

    public function getUrlAttribute(): string
    {
        // Locked albums use controller route, public albums use direct storage URL
        if ($this->album->isLocked()) {
            return route('album.image', $this->id);
        }

        return Storage::disk('public')->url($this->path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->album->isLocked()) {
            return route('album.image.thumbnail', $this->id);
        }

        return Storage::disk('public')->url($this->thumbnail_path);
    }
}
