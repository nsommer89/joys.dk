<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JoysConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'key' => 'album_max',
                'value' => '25',
                'description' => 'Maksimalt antal albums en bruger kan oprette.',
            ],
            [
                'key' => 'album_pr_page',
                'value' => '6',
                'description' => 'Antal albums vist pr. side.',
            ],
        ];

        foreach ($configs as $config) {
            \App\Models\JoysConfig::updateOrCreate(
                ['key' => $config['key']],
                [
                    'value' => $config['value'],
                    'description' => $config['description'],
                ]
            );
        }
    }
}
