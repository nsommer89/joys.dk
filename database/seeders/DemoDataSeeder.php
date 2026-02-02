<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('da_DK');
        
        // Ensure directory exists
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('events');
        
        // Download some dummy images
        $images = [];
        $imageUrls = [
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&q=80', // Party
            'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80', // Piercing/Night
            'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&q=80', // Concert/Crowd
            'https://images.unsplash.com/photo-1543807535-eceef0bc6599?w=800&q=80', // Friends
            'https://images.unsplash.com/photo-1514525253440-b393452e8d26?w=800&q=80', // Club
        ];
        
        foreach ($imageUrls as $index => $url) {
            $filename = "events/dummy_{$index}.jpg";
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($filename)) {
                try {
                    $contents = file_get_contents($url);
                    if ($contents) {
                        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);
                        $images[] = $filename;
                    }
                } catch (\Exception $e) {
                    // Fallback or skip
                }
            } else {
                $images[] = $filename;
            }
        }
        
        if (empty($images)) {
             // Create a placeholder if download fails
             \Illuminate\Support\Facades\Storage::disk('public')->put('events/placeholder.jpg', ''); // Empty file just to avoid error? No, better null.
        }

        for ($i = 0; $i < 25; $i++) {
            $title = $faker->sentence(3);
            $startDate = $faker->dateTimeBetween('now', '+3 months');
            $endDate = (clone $startDate)->modify('+6 hours');
            
            \App\Models\Event::create([
                'title' => trim($title, '.'),
                'slug' => \Illuminate\Support\Str::slug($title . '-' . $faker->unique()->numberBetween(1, 999)),
                'description' => $faker->paragraphs(3, true),
                'start_time' => $startDate,
                'end_time' => $endDate,
                'image_path' => !empty($images) ? $faker->randomElement($images) : null,
                'is_published' => true,
            ]);
        }
        // News Seeder
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('news');
        $newsImages = [];
        
        foreach ($imageUrls as $index => $url) {
            $filename = "news/dummy_{$index}.jpg";
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($filename)) {
                try {
                    $contents = file_get_contents($url);
                    if ($contents) {
                        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);
                        $newsImages[] = $filename;
                    }
                } catch (\Exception $e) {
                }
            } else {
                $newsImages[] = $filename;
            }
        }

        for ($i = 0; $i < 15; $i++) {
            $title = $faker->sentence(4);
            $publishedAt = $faker->dateTimeBetween('-1 year', 'now');
            
            \App\Models\News::create([
                'title' => trim($title, '.'),
                'slug' => \Illuminate\Support\Str::slug($title . '-' . $faker->unique()->numberBetween(1, 999)),
                'content' => $faker->paragraphs(5, true),
                'published_at' => $publishedAt,
                'image_path' => !empty($newsImages) ? $faker->randomElement($newsImages) : null,
                'is_published' => true,
            ]);
        }

        // Gallery Seeder
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('gallery');
        $galleryImages = [];
        $galleryUrls = [
            'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?w=800&q=80',
            'https://images.unsplash.com/photo-1574094433880-0138336bb3f1?w=800&q=80',
            'https://images.unsplash.com/photo-1560439514-4e9645039924?w=800&q=80',
            'https://images.unsplash.com/photo-1517457373958-b7bdd458ad20?w=800&q=80',
            'https://images.unsplash.com/photo-1496333039240-42171f259740?w=800&q=80',
            'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&q=80',
            'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80',
            'https://images.unsplash.com/photo-1550928431-ee0ec6db30d3?w=800&q=80',
            'https://images.unsplash.com/photo-1514924013411-cbf25faa35ad?w=800&q=80',
            'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?w=800&q=80',
        ];

        foreach ($galleryUrls as $index => $url) {
            $filename = "gallery/dummy_{$index}.jpg";
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($filename)) {
                try {
                    $contents = file_get_contents($url);
                    if ($contents) {
                        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);
                        $galleryImages[] = $filename;
                    }
                } catch (\Exception $e) {
                }
            } else {
                $galleryImages[] = $filename;
            }
        }

        for ($i = 0; $i < 40; $i++) {
            \App\Models\GalleryImage::create([
                'image_path' => $faker->randomElement($galleryImages),
                'sort_order' => $i,
            ]);
        }
    }
}
