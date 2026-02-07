<?php

namespace App\Jobs;

use App\Models\UserAlbumImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProcessGalleryImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public UserAlbumImage $image,
        public string $tempPath
    ) {}

    public function handle(): void
    {
        $album = $this->image->album;
        $disk = $album->isLocked() ? 'local' : 'public';

        // Base directory for this album
        $baseDir = "user-albums/{$album->user_id}/{$album->id}";

        // Read the temporary file
        $tempFullPath = Storage::disk('local')->path($this->tempPath);

        // Create image manager
        $manager = new ImageManager(new Driver);

        // Process main image (max 1920x1920)
        $mainImage = $manager->read($tempFullPath);
        $mainImage->scale(width: 1920, height: 1920);

        // Generate filename
        $filename = uniqid().'.jpg';
        $mainPath = "{$baseDir}/{$filename}";

        // Save main image
        $encoded = $mainImage->toJpeg(quality: 85);
        Storage::disk($disk)->put($mainPath, $encoded);

        // Process thumbnail (400x400)
        $thumbnail = $manager->read($tempFullPath);
        $thumbnail->cover(400, 400);

        $thumbFilename = uniqid().'_thumb.jpg';
        $thumbPath = "{$baseDir}/thumbs/{$thumbFilename}";

        // Save thumbnail
        $encodedThumb = $thumbnail->toJpeg(quality: 80);
        Storage::disk($disk)->put($thumbPath, $encodedThumb);

        // Update database
        $this->image->update([
            'path' => $mainPath,
            'thumbnail_path' => $thumbPath,
            'is_processing' => false,
        ]);

        // Clean up temp file
        Storage::disk('local')->delete($this->tempPath);

        // Dispatch event for real-time UI update
        event(new \App\Events\AlbumImageProcessed($this->image));
    }
}
