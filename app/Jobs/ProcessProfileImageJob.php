<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessProfileImageJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $userId,
        public string $tempFilePath,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);
        if (!$user) return;

        // Path to the temp file in the 'processing' directory (local disk usually)
        // or wherever Livewire/we stored it.
        // Assuming we stored it in 'storage/app/private/tmp' or similar.
        // Let's assume passed path is relative to Storage::disk('local') or absolute.
        
        $absPath = Storage::disk('local')->path($this->tempFilePath);

        if (!file_exists($absPath)) {
            // Fail or log
            \Log::error("File not found for processing: " . $absPath);
            return;
        }

        // Simulate processing time if needed, or just do the work
        // sleep(2); // Optional: Simulate delay as requested/implied for UX testing? User asked for delay on button, maybe not here.

        // Resize/Optimization using Intervention Image
        $manager = new ImageManager(new Driver());
        $image = $manager->read($absPath);

        // Example processing: Resize to max 800x800
        $image->scaleDown(width: 800, height: 800);

        // Save processed image. 
        // We save it to a public temporary location so the user can see it.
        $newFileName = 'processed_' . basename($this->tempFilePath);
        $publicTempPath = 'temp-profiles/' . $newFileName;

        // Save to public disk
        Storage::disk('public')->put($publicTempPath, (string) $image->toJpeg(quality: 85));

        // Update a cache key or database flag to notify UI
        // We'll use Cache for "Processed Temp Image"
        \Cache::put("user_{$this->userId}_processed_image", $publicTempPath, 60 * 60); // 1 hour TTL

        // Clean up the raw upload
        @unlink($absPath);
    }
}
