<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoysConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    protected static function booted()
    {
        static::saved(function ($config) {
            \Illuminate\Support\Facades\Cache::forget('joys_configs_all');
        });
    }
}
