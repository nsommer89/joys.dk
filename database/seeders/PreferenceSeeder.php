<?php

namespace Database\Seeders;

use App\Models\Preference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preferences = [
            'Gangbang',
            'Hardcore',
            'Hetero',
            'Par Par',
            'Rollespil',
            'Trekant',
            'S/M',
            'Bi',
            'Kvinde Kvinde',
            'Bukake',
            'Trans',
            'Glory Hole',
        ];

        foreach ($preferences as $name) {
            Preference::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
