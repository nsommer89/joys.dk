<?php

namespace Database\Seeders;

use App\Models\Friendship;
use App\Models\User;
use App\Models\UserAlbum;
use App\Models\UserAlbumImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('da_DK');

        // Configuration
        $usersPerRun = 1000; // User set value

        // ----------------------------------------------------------------
        // Helper: Local Asset Caching
        // ----------------------------------------------------------------
        // Ensures we only download images once and store them in resources/demodata
        $resourcePath = resource_path('demodata');
        if (! File::exists($resourcePath)) {
            File::makeDirectory($resourcePath, 0755, true);
        }

        $getImagePath = function ($url, $filename, $targetDir = 'misc') use ($resourcePath) {
            $localPath = $resourcePath.'/'.$filename;

            // 1. Check local resource first
            if (! File::exists($localPath)) {
                try {
                    $contents = file_get_contents($url);
                    if ($contents) {
                        File::put($localPath, $contents);
                    } else {
                        return null; // Failed to download
                    }
                } catch (\Exception $e) {
                    return null;
                }
            }

            // 2. Copy to Storage for usage
            // We return the storage path relative to 'public' disk root
            // We DO NOT expose 'demodata' folder publicly. We copy to the target usage directory.
            $storageFilename = $targetDir.'/'.$filename;

            // Ensure target directory exists in storage
            if (! Storage::disk('public')->exists($targetDir)) {
                Storage::disk('public')->makeDirectory($targetDir);
            }

            if (! Storage::disk('public')->exists($storageFilename)) {
                Storage::disk('public')->put($storageFilename, File::get($localPath));
            }

            return $storageFilename;
        };

        // Shared Image URLs
        $imageUrls = [
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&q=80', // Party
            'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80', // Piercing/Night
            'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&q=80', // Concert/Crowd
            'https://images.unsplash.com/photo-1543807535-eceef0bc6599?w=800&q=80', // Friends
            'https://images.unsplash.com/photo-1514525253440-b393452e8d26?w=800&q=80', // Club
        ];

        // ----------------------------------------------------------------
        // 1. Static Data (Events, News, Gallery) - Run only if empty
        // ----------------------------------------------------------------

        // Events
        if (\App\Models\Event::count() === 0) {
            Storage::disk('public')->makeDirectory('events');
            $eventImages = [];
            foreach ($imageUrls as $index => $url) {
                if ($path = $getImagePath($url, "event_{$index}.jpg", 'events')) {
                    $eventImages[] = $path;
                }
            }

            // Fallback placeholder
            if (empty($eventImages)) {
                Storage::disk('public')->put('events/placeholder.jpg', '');
            }

            for ($i = 0; $i < 25; $i++) {
                $title = $faker->sentence(3);
                $startDate = $faker->dateTimeBetween('now', '+3 months');
                $endDate = (clone $startDate)->modify('+6 hours');

                \App\Models\Event::create([
                    'title' => trim($title, '.'),
                    'slug' => \Illuminate\Support\Str::slug($title.'-'.$faker->unique()->numberBetween(1, 999)),
                    'description' => $faker->paragraphs(3, true),
                    'start_time' => $startDate,
                    'end_time' => $endDate,
                    'image_path' => ! empty($eventImages) ? $faker->randomElement($eventImages) : null,
                    'is_published' => true,
                ]);
            }
        }

        // News
        if (\App\Models\News::count() === 0) {
            Storage::disk('public')->makeDirectory('news');
            $newsImages = [];
            foreach ($imageUrls as $index => $url) {
                if ($path = $getImagePath($url, "news_{$index}.jpg", 'news')) {
                    $newsImages[] = $path;
                }
            }

            for ($i = 0; $i < 15; $i++) {
                $title = $faker->sentence(4);
                $publishedAt = $faker->dateTimeBetween('-1 year', 'now');

                \App\Models\News::create([
                    'title' => trim($title, '.'),
                    'slug' => \Illuminate\Support\Str::slug($title.'-'.$faker->unique()->numberBetween(1, 999)),
                    'content' => $faker->paragraphs(5, true),
                    'published_at' => $publishedAt,
                    'image_path' => ! empty($newsImages) ? $faker->randomElement($newsImages) : null,
                    'is_published' => true,
                ]);
            }
        }

        // Global Gallery
        if (\App\Models\GalleryImage::count() === 0) {
            Storage::disk('public')->makeDirectory('gallery');
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
                if ($path = $getImagePath($url, "gallery_{$index}.jpg", 'gallery')) {
                    $galleryImages[] = $path;
                }
            }

            for ($i = 0; $i < 40; $i++) {
                if (! empty($galleryImages)) {
                    \App\Models\GalleryImage::create([
                        'image_path' => $faker->randomElement($galleryImages),
                        'sort_order' => $i,
                    ]);
                }
            }
        }

        // ----------------------------------------------------------------
        // 2. Incremental Data (Users, Friendships, User Albums)
        // ----------------------------------------------------------------

        $createdUsers = collect();

        $genderIds = \App\Models\Gender::pluck('id', 'slug'); // [ 'mand' => 1, 'kvinde' => 2, 'par' => 3 ]
        if ($genderIds->isEmpty()) {
            // Fallback IDs if table empty (shouldn't happen with production seeds)
            $genderIds = collect(['mand' => 1, 'kvinde' => 2, 'par' => 3]);
        }

        $allPreferenceIds = \App\Models\Preference::pluck('id');
        $allEventIds = \App\Models\Event::pluck('id');

        // ----------------------------------------------------------------
        // 3. Specific Test User (test@yap.dk)
        // ----------------------------------------------------------------
        if (! User::where('email', 'test@yap.dk')->exists()) {
            $testUser = User::factory()->create([
                'email' => 'test@yap.dk',
                'password' => Hash::make('password'),
                'gender_id' => $genderIds['mand'] ?? 1, // Default to mand
                'profile_photo_path' => null,
            ]);

            $this->command->info('Created test user: test@yap.dk / password');
        }

        // Prepare some user profile images
        Storage::disk('public')->makeDirectory('avatars');
        $avatarImages = [];
        $avatarUrls = [
            'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=400&q=80',
            'https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=400&q=80',
            'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&q=80',
            'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&q=80',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80',
            'https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=400&q=80',
        ];

        foreach ($avatarUrls as $index => $url) {
            if ($path = $getImagePath($url, "avatar_{$index}.jpg", 'avatars')) {
                $avatarImages[] = $path;
            }
        }

        // Create Users
        for ($i = 0; $i < $usersPerRun; $i++) {
            $genderSlug = $faker->randomElement($genderIds->keys()->toArray());
            $genderId = $genderIds[$genderSlug] ?? 1;

            // Ensure unique username
            $username = $faker->userName();
            while (User::where('username', $username)->exists()) {
                $username = $faker->userName().$faker->numberBetween(1, 9999);
            }

            $user = User::factory()->create([
                'username' => $username,
                'gender_id' => $genderId,
                'profile_photo_path' => $faker->boolean(60) && ! empty($avatarImages) ? $faker->randomElement($avatarImages) : null,
                'password' => Hash::make('password'),
            ]);

            // Create User Profile
            $userProfile = $user->userProfile()->create([
                'zip_code' => $faker->postcode,
                'city' => $faker->city,
                'description' => $faker->text(200),
            ]);

            // Add Profile People (Couples Logic)
            $personCount = ($genderSlug === 'par') ? 2 : 1;

            for ($p = 0; $p < $personCount; $p++) {
                $userProfile->profilePeople()->create([
                    'name' => $faker->firstName,
                    'age' => $faker->numberBetween(18, 60),
                    'height' => $faker->numberBetween(150, 200),
                    'weight' => $faker->numberBetween(50, 120),
                ]);
            }

            // Sync Preferences
            if ($allPreferenceIds->isNotEmpty()) {
                $randomPrefs = $allPreferenceIds->random(min($faker->numberBetween(1, 5), $allPreferenceIds->count()));
                $user->preferences()->sync($randomPrefs);
            }

            // Sync Events (Attendance)
            if ($allEventIds->isNotEmpty() && $faker->boolean(20)) {
                $randomEvents = $allEventIds->random(min($faker->numberBetween(1, 3), $allEventIds->count()));
                $user->events()->sync($randomEvents);
            }

            $createdUsers->push($user);
        }

        // Create Friendships (Accepted & Pending)
        $allUserIds = User::pluck('id');

        foreach ($createdUsers as $user) {
            // Random friendships
            $friendCount = $faker->numberBetween(0, 8);

            // Pending requests (incoming/outgoing mixed conceptually here we just create links)
            $pendingCount = $faker->numberBetween(0, 3);

            $totalTarget = $friendCount + $pendingCount;
            if ($totalTarget === 0) {
                continue;
            }

            $potentialFriends = $allUserIds->reject(fn ($id) => $id === $user->id)
                ->shuffle()
                ->take(min($totalTarget, $allUserIds->count() - 1));

            $acceptedSlice = $potentialFriends->slice(0, $friendCount);
            $pendingSlice = $potentialFriends->slice($friendCount, $pendingCount);

            // Create Accepted
            foreach ($acceptedSlice as $friendId) {
                $this->createFriendship($user->id, $friendId, 'accepted');
            }

            // Create Pending
            foreach ($pendingSlice as $friendId) {
                // Randomize direction: User sent it OR User received it
                if ($faker->boolean) {
                    $this->createFriendship($user->id, $friendId, 'pending');
                } else {
                    $this->createFriendship($friendId, $user->id, 'pending');
                }
            }
        }

        // Create User Galleries (Albums)
        Storage::disk('public')->makeDirectory('user-albums');

        foreach ($createdUsers as $user) {
            // 60% chance to have an album
            if ($faker->boolean(60)) {
                $albumCount = $faker->numberBetween(1, 3);

                for ($k = 0; $k < $albumCount; $k++) {

                    // Password protection logic
                    $password = null;
                    if ($faker->boolean(20)) { // 20% chance of being locked
                        $rawPassword = 'secret';
                        $password = Hash::make($rawPassword);

                        // Update user description with hint
                        $currentDesc = $user->userProfile->description;
                        $user->userProfile->update([
                            'description' => $currentDesc."\n\n(Psst! Min private album kode er: {$rawPassword})",
                        ]);
                    }

                    $name = $faker->words(3, true);
                    $album = UserAlbum::create([
                        'user_id' => $user->id,
                        'name' => $name,
                        'slug' => \Illuminate\Support\Str::slug($name.'-'.\Illuminate\Support\Str::random(4)),
                        'password' => $password,
                    ]);

                    // Add images to album
                    $imageCount = $faker->numberBetween(2, 6);
                    for ($m = 0; $m < $imageCount; $m++) {
                        // Reuse downloaded assets to fake user images
                        // We look for any image in our cached demodata folder to copy

                        // Get random file from resource path to simulate "upload"
                        $files = File::files($resourcePath);
                        if (count($files) > 0) {
                            $sourceFile = $faker->randomElement($files);
                            $newFilename = 'user-albums/'.\Illuminate\Support\Str::random(16).'.jpg';

                            Storage::disk('public')->put($newFilename, File::get($sourceFile->getPathname()));

                            UserAlbumImage::create([
                                'user_album_id' => $album->id,
                                'path' => $newFilename,
                                'thumbnail_path' => $newFilename, // Lazy thumbnail usage
                                'is_processing' => false,
                            ]);
                        }
                    }
                }
            }
        }

        $this->command->info("Seeded {$usersPerRun} new users (with couples/preferences), friendships (accepted/pending), and galleries (some locked).");
    }

    private function createFriendship($senderId, $recipientId, $status)
    {
        // Check existence logic
        $exists = Friendship::where(function ($q) use ($senderId, $recipientId) {
            $q->where('sender_id', $senderId)->where('recipient_id', $recipientId);
        })->orWhere(function ($q) use ($senderId, $recipientId) {
            $q->where('sender_id', $recipientId)->where('recipient_id', $senderId);
        })->exists();

        if (! $exists) {
            Friendship::create([
                'sender_id' => $senderId,
                'recipient_id' => $recipientId,
                'status' => $status,
            ]);
        }
    }
}
