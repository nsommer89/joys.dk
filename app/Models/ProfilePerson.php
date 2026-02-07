<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilePerson extends Model
{
    protected $fillable = [
        'user_profile_id',
        'name',
        'age',
        'height',
        'weight',
    ];

    public function userProfile(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }
}
