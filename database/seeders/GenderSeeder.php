<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            ['name' => 'Mand', 'slug' => 'mand', 'icon' => 'male'],
            ['name' => 'Kvinde', 'slug' => 'kvinde', 'icon' => 'female'],
            ['name' => 'Par', 'slug' => 'par', 'icon' => 'users'],
        ];

        foreach ($genders as $gender) {
            \App\Models\Gender::firstOrCreate(
                ['slug' => $gender['slug']],
                $gender
            );
        }
    }
}
