<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'zip_code',
        'city',
        'description',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profilePeople(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProfilePerson::class);
    }
}
