<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $fillable = ['name', 'slug', 'icon'];

    public function userProfiles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserProfile::class);
    }

    public function profilePeople(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProfilePerson::class);
    }
}
