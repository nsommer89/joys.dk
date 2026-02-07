<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GalleryImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing gallery images
        GalleryImage::truncate();

        // Ensure gallery directory exists
        if (! Storage::disk('public')->exists('gallery')) {
            Storage::disk('public')->makeDirectory('gallery');
        }

        $this->command->info('Downloading and creating 40 gallery images...');

        // Create 40 gallery images
        for ($i = 1; $i <= 40; $i++) {
            $this->command->info("Processing image {$i}/40...");

            // Use Picsum Photos for placeholder images
            // Random dimensions between 800-1200 for variety
            $width = rand(800, 1200);
            $height = rand(600, 1000);

            try {
                // Download image from Picsum
                $imageUrl = "https://picsum.photos/{$width}/{$height}?random={$i}";
                $response = Http::timeout(30)->get($imageUrl);

                if ($response->successful()) {
                    // Generate filename
                    $filename = 'gallery-'.str_pad($i, 3, '0', STR_PAD_LEFT).'.jpg';
                    $path = 'gallery/'.$filename;

                    // Save image to storage
                    Storage::disk('public')->put($path, $response->body());

                    // Create database record
                    GalleryImage::create([
                        'image_path' => $path,
                        'sort_order' => $i,
                    ]);

                    $this->command->info("✓ Created: {$filename}");
                } else {
                    $this->command->warn("✗ Failed to download image {$i}");
                }
            } catch (\Exception $e) {
                $this->command->error("✗ Error downloading image {$i}: ".$e->getMessage());
            }

            // Small delay to avoid rate limiting
            usleep(200000); // 0.2 seconds
        }

        $this->command->info('Gallery seeding completed!');
    }
}
