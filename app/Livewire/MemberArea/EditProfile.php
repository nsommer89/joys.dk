<?php

namespace App\Livewire\MemberArea;

use App\Jobs\ProcessGalleryImageJob;
use App\Models\Gender;
use App\Models\Preference;
use App\Models\ProfilePerson;
use App\Models\UserAlbum;
use App\Models\UserAlbumImage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class EditProfile extends Component
{
    use WithFileUploads, WithPagination;

    public $zip_code;

    public $city;

    public $description;

    public $selected_preferences = [];

    public $profile_photo;

    public $username;

    public $is_processing_photo = false;

    public $processed_photo_path = null;

    public $photo_processing_started_at = null;

    // Gender/Account Type
    public $gender_id;

    public $is_couple = false;

    // Persons Data
    public $persons = [];

    // Album Management
    // public $albums = []; // Removed for pagination
    public $selectedAlbum = null;

    // public $albumImages = []; // Removed for pagination
    public $newAlbumName = '';

    public $newAlbumPassword = '';

    public $uploadedImages = [];

    public $showCreateAlbumModal = false;

    public $showDeleteAlbumModal = false;

    public $albumToDelete = null;

    public $showEditPasswordModal = false;

    public $editAlbumPassword = '';

    // Delete Confirmation State
    public $showDeleteConfirmation = false;

    public $deleteConfirmationAction = null;

    public $deleteConfirmationId = null;

    // Tab state (persisted across Livewire requests)
    public $activeTab = 'profil';

    public $selectedImageId = '';

    // Album Renaming
    public $editingAlbumId = null;

    public $editingAlbumName = '';

    public function mount($albumSlug = null, $imageHash = null)
    {
        $user = Auth::user();
        $this->loadProfile($user);
        $this->loadAlbums();

        // Determine active tab based on URL
        if (request()->is('*/albums*') || $albumSlug) {
            $this->activeTab = 'albums';
        }

        // Handle album slug from URL
        if ($albumSlug) {
            $album = $user->userAlbums()->where('slug', $albumSlug)->first();
            if ($album) {
                $this->selectAlbum($album->id);

                // Handle deep link image via hash
                if ($imageHash && $this->selectedAlbum) {
                    $image = $this->selectedAlbum->images->where('hash', $imageHash)->first();
                    if ($image && ! $image->is_processing) {
                        $this->selectedImageId = $image->hash;
                    } else {
                        $this->selectedImageId = '';
                    }
                }
            } else {
                $this->selectedImageId = '';
            }
        } else {
            $this->selectedImageId = '';
        }
    }

    public function loadProfile($user)
    {
        $profile = $user->userProfile()->with('profilePeople')->first();
        $this->username = $user->username;
        $this->gender_id = $user->gender_id;

        if (! $this->gender_id) {
            $defaultGender = Gender::where('slug', 'mand')->first();
            $this->gender_id = $defaultGender ? $defaultGender->id : 1;
        }

        if (! $profile) {
            // Initialize empty profile state with default gender 'Mand' if none exists or not set on user
            // We assume 'Mand' is the default if not set.
            // ID 1 = Mand based on seeder order, but safer to lookup by slug.

            // Should we auto-create the profile here? Or wait for save?
            // Wait for save, but setup state.
            $this->checkIfCouple();
            $this->initializePersonSlots();

            return;
        }

        $this->zip_code = $profile->zip_code;
        $this->city = $profile->city;
        $this->description = $profile->description;
        // $this->gender_id is already set from user
        $this->selected_preferences = $user->preferences()->pluck('preferences.id')->toArray();

        // Determine if couple
        $this->checkIfCouple();

        // Load Persons
        $people = $profile->profilePeople()->orderBy('id')->get();

        foreach ($people as $person) {
            $this->persons[] = [
                'id' => $person->id,
                'name' => $person->name,
                'age' => $person->age,
                'height' => $person->height,
                'weight' => $person->weight,
            ];
        }

        // If structure doesn't match gender (e.g. data corrupt or fresh switch), initialize slots
        $this->initializePersonSlots();
    }

    public function updatedZipCode($value)
    {
        // Basic validation: 4 digits
        if (! preg_match('/^\d{4}$/', $value)) {
            return;
        }

        try {
            $city = \Illuminate\Support\Facades\Cache::remember('dawa_zip_'.$value, now()->addYears(2), function () use ($value) {
                $url = 'https://api.dataforsyningen.dk/postnumre/'.urlencode((string) $value);
                $response = @file_get_contents($url);

                if (! $response) {
                    return null;
                }

                $data = json_decode($response, true);

                return $data['navn'] ?? null;
            });

            if ($city) {
                $this->city = $city;
            }
        } catch (\Throwable $e) {
            // Silent fail as requested
        }
    }

    public function deleteProfilePhoto()
    {
        $user = Auth::user();
        if ($user->profile_photo_path) {
            // Optionally delete file from storage
            // Storage::disk($this->profilePhotoDisk())->delete($user->profile_photo_path);

            $user->update([
                'profile_photo_path' => null,
            ]);

            // Clean up temporary upload state if any
            $this->profile_photo = null;
            $this->processed_photo_path = null;

            $this->is_processing_photo = false;
            $this->photo_processing_started_at = null;

            $this->dispatch('photo-processed');
            $this->dispatch('processing-finished');

            $this->dispatch('profile-updated', [
                'type' => 'success',
                'message' => 'Profilbillede fjernet',
                'photoUrl' => $user->profile_photo_url,
                'userId' => $user->id,
            ]);
        }
    }

    public function updatedProfilePhoto()
    {
        $this->processed_photo_path = null;
        $user = Auth::user();
        \Cache::forget("user_{$user->id}_processed_image");

        try {
            $this->validate([
                'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB Max, explicit types
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('toast', message: 'Filen kunne ikke uploades. Sørg for det er et billede (JPG, PNG, WEBP) under 10MB.', type: 'error');
            $this->profile_photo = null; // Clear invalid file

            return;
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Der opstod en fejl ved upload af billedet.', type: 'error');
            $this->profile_photo = null;

            return;
        }

        $user = Auth::user();

        try {
            // Store in a temp directory that the Job can access
            $path = $this->profile_photo->store('processing', 'local');

            $this->is_processing_photo = true;
            $this->photo_processing_started_at = now();
            $this->dispatch('processing-started');

            // Dispatch job
            \App\Jobs\ProcessProfileImageJob::dispatch($user->id, $path);
        } catch (\Exception $e) {
            $this->is_processing_photo = false;
            $this->dispatch('processing-finished');
            $this->dispatch('toast', message: 'Kunne ikke behandle billedet. Prøv igen.', type: 'error');
        }
    }

    public function pollPhotoStatus()
    {
        if (! $this->is_processing_photo) {
            return;
        }

        $user = Auth::user();
        $cacheKey = "user_{$user->id}_processed_image";

        $processedPath = \Cache::get($cacheKey);

        if ($processedPath) {
            $this->processed_photo_path = $processedPath;
            $this->is_processing_photo = false;
            $this->photo_processing_started_at = null;
            $this->dispatch('photo-processed');
            $this->dispatch('processing-finished');
            // Clear cache so it's not reused incorrectly later if we reload
            \Cache::forget($cacheKey);

            return;
        }

        // Safety Timeout (10 seconds)
        if ($this->photo_processing_started_at && \Carbon\Carbon::parse($this->photo_processing_started_at)->diffInSeconds(now()) > 10) {
            $this->is_processing_photo = false;
            $this->photo_processing_started_at = null;
            $this->dispatch('processing-finished');
            $this->dispatch('toast', message: 'Behandlingen tog for lang tid. Prøv igen eller kontakt support.', type: 'info');
        }
    }

    public function updatedGenderId()
    {
        $this->checkIfCouple();
        $this->initializePersonSlots();
    }

    protected function checkIfCouple()
    {
        $gender = Gender::find($this->gender_id);
        $this->is_couple = $gender && $gender->slug === 'par';
    }

    protected function initializePersonSlots()
    {
        $requiredCount = $this->is_couple ? 2 : 1;
        $currentCount = count($this->persons);

        if ($currentCount < $requiredCount) {
            for ($i = $currentCount; $i < $requiredCount; $i++) {
                $this->persons[] = [
                    'id' => null,
                    'name' => '',
                    'age' => null,
                    'height' => null,
                    'weight' => null,
                ];
            }
        } elseif ($currentCount > $requiredCount) {
            $this->persons = array_slice($this->persons, 0, $requiredCount);
        }
    }

    public function save()
    {
        try {
            $this->validate([
                'gender_id' => 'required|exists:genders,id',
                'zip_code' => 'nullable|digits:4',
                'city' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:5000',
                // Let's validate persons data
                'persons.*.name' => 'nullable|string|max:255',
                'persons.*.age' => 'nullable|integer|min:18|max:120',
                'persons.*.height' => 'nullable|integer|min:50|max:250',
                'persons.*.weight' => 'nullable|integer|min:30|max:300',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('toast', message: 'Der er fejl i formularen. Tjek venligst de markerede felter.', type: 'error');
            $this->dispatch('validation-failed');
            throw $e;
        }

        $user = Auth::user();

        // 0. Update User Gender
        $user->update(['gender_id' => $this->gender_id]);

        // 1. Save/Create User Profile
        $profile = $user->userProfile()->firstOrCreate(
            ['user_id' => $user->id]
        );

        $profile->update([
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'description' => $this->description,
        ]);

        // 2. Save Profile Photo
        if ($this->processed_photo_path) {
            // Check if file exists in the public disk
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->processed_photo_path)) {
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
                }

                // Move from temp to permanent
                $newFilename = basename($this->processed_photo_path);
                $finalPath = 'profile-photos/'.$newFilename;

                \Illuminate\Support\Facades\Storage::disk('public')->move($this->processed_photo_path, $finalPath);

                $user->update(['profile_photo_path' => $finalPath]);

                // Clear the temporary state
                $this->processed_photo_path = null;
                $this->profile_photo = null;
            }
        } elseif ($this->profile_photo && ! $this->is_processing_photo) {
            // Fallback
        }

        // 3. Save Preferences
        $user->preferences()->sync($this->selected_preferences);

        // 4. Save Persons
        // We delete existing and re-create? Or update?
        // Better to update existing IDs and create new ones.
        // But for simplicity in this logic, if we have IDs, update.

        // Let's track existing IDs to know what to delete (if user switched from Tuple to Single, we might have orphans)
        // But initializePersonSlots handled the array slicing.

        $currentPersonIds = [];

        foreach ($this->persons as $personData) {
            if (! empty($personData['id'])) {
                $person = ProfilePerson::find($personData['id']);
                $person->update([
                    'name' => $personData['name'],
                    'age' => $personData['age'],
                    'height' => $personData['height'],
                    'weight' => $personData['weight'],
                ]);
                $currentPersonIds[] = $person->id;
            } else {
                $newPerson = $profile->profilePeople()->create([
                    'name' => $personData['name'],
                    'age' => $personData['age'],
                    'height' => $personData['height'],
                    'weight' => $personData['weight'],
                ]);
                $currentPersonIds[] = $newPerson->id; // Although we don't need it for deletion since we sliced the array
            }
        }

        // Remove people not in the current set (e.g. if we went from 2 to 1)
        $profile->profilePeople()->whereNotIn('id', $currentPersonIds)->delete();

        // Refresh gender relation to ensure correct placeholder if gender changed
        $user->refresh();

        $this->dispatch('toast', message: 'Din profil er opdateret.', type: 'success');

        $this->is_processing_photo = false;
        $this->dispatch('profile-updated', userId: $user->id, photoUrl: $user->profile_photo_url);
    }

    // ==================== ALBUM MANAGEMENT ====================

    public function loadAlbums()
    {
        // No longer storing in property to support pagination
    }

    public function selectAlbum($albumId)
    {
        $this->selectedAlbum = UserAlbum::findOrFail($albumId);
    }

    public function refreshAlbumImages()
    {
        if ($this->selectedAlbum) {
            $this->selectedAlbum->refresh();
        }
    }

    public function createAlbum()
    {
        $this->validate([
            'newAlbumName' => 'required|string|max:100',
            'newAlbumPassword' => 'nullable|string|min:4|max:50',
        ]);

        $user = Auth::user();

        // Check album limit from config
        $albumMax = (int) config('joys.album_max', 25);
        if ($user->userAlbums()->count() >= $albumMax) {
            $this->dispatch('toast', message: "Du kan maksimalt have {$albumMax} albums.", type: 'error');

            return;
        }

        $album = $user->userAlbums()->create([
            'name' => $this->newAlbumName,
            'password' => $this->newAlbumPassword ? \Hash::make($this->newAlbumPassword) : null,
        ]);

        $this->newAlbumName = '';
        $this->newAlbumPassword = '';
        $this->showCreateAlbumModal = false;

        $this->dispatch('toast', message: 'Album oprettet!', type: 'success');

        return redirect()->route('member.profile.edit.album', $album->slug);
    }

    public function renameAlbum($albumId, $newName)
    {
        $album = Auth::user()->userAlbums()->findOrFail($albumId);

        $album->update(['name' => $newName]);

        $this->loadAlbums();
        if ($this->selectedAlbum && $this->selectedAlbum->id === $albumId) {
            $this->selectAlbum($albumId);
        }

        $this->dispatch('toast', message: 'Album omdøbt!', type: 'success');
    }

    public function confirmDeleteAlbum($albumId)
    {
        $this->albumToDelete = $albumId;
        $this->showDeleteAlbumModal = true;
    }

    public function deleteAlbum()
    {
        if (! $this->albumToDelete) {
            return;
        }

        $album = Auth::user()->userAlbums()->findOrFail($this->albumToDelete);

        // Delete all images from storage
        foreach ($album->images as $image) {
            $disk = $album->isLocked() ? 'local' : 'public';
            \Storage::disk($disk)->delete($image->path);
            \Storage::disk($disk)->delete($image->thumbnail_path);
        }

        $album->delete();

        $this->albumToDelete = null;
        $this->showDeleteAlbumModal = false;
        $this->selectedAlbum = null;
        $this->albumImages = [];

        $this->dispatch('toast', message: 'Album slettet.', type: 'success');

        return redirect()->route('member.profile.edit.albums');
    }

    public function openEditPasswordModal()
    {
        if ($this->selectedAlbum) {
            $this->editAlbumPassword = '';
            $this->showEditPasswordModal = true;
        }
    }

    public function updateAlbumPassword()
    {
        if (! $this->selectedAlbum) {
            return;
        }

        $album = Auth::user()->userAlbums()->findOrFail($this->selectedAlbum->id);
        $wasLocked = $album->isLocked();
        $willBeLocked = ! empty($this->editAlbumPassword);

        if ($willBeLocked) {
            $this->validate(['editAlbumPassword' => 'min:4|max:50']);
        }

        // Only move files if lock status changes
        if ($wasLocked !== $willBeLocked) {
            $sourceDisk = $wasLocked ? 'local' : 'public';
            $targetDisk = $willBeLocked ? 'local' : 'public';

            foreach ($album->images as $image) {
                // Move main image
                if (\Storage::disk($sourceDisk)->exists($image->path)) {
                    $content = \Storage::disk($sourceDisk)->get($image->path);
                    \Storage::disk($targetDisk)->put($image->path, $content);
                    \Storage::disk($sourceDisk)->delete($image->path);
                }

                // Move thumbnail
                if (\Storage::disk($sourceDisk)->exists($image->thumbnail_path)) {
                    $content = \Storage::disk($sourceDisk)->get($image->thumbnail_path);
                    \Storage::disk($targetDisk)->put($image->thumbnail_path, $content);
                    \Storage::disk($sourceDisk)->delete($image->thumbnail_path);
                }
            }
        }

        if ($willBeLocked) {
            // Set/update password
            $album->update(['password' => bcrypt($this->editAlbumPassword)]);
            $this->dispatch('toast', message: 'Adgangskode opdateret.', type: 'success');
        } else {
            // Remove password (make album public)
            $album->update(['password' => null]);
            $this->dispatch('toast', message: 'Album er nu offentligt.', type: 'success');
        }

        // Refresh selected album
        $this->selectedAlbum = $album->fresh();
        $this->editAlbumPassword = '';
        $this->showEditPasswordModal = false;

        // Reload albums list
        $this->loadAlbums();
    }

    public function uploadImages($albumId)
    {
        $this->validate([
            'uploadedImages.*' => 'required|image|max:12288', // 12MB
        ]);

        $album = Auth::user()->userAlbums()->findOrFail($albumId);

        // Check image limit (50 per album)
        $currentCount = $album->images()->count();
        $newCount = count($this->uploadedImages);

        if ($currentCount + $newCount > 50) {
            $this->dispatch('toast', message: 'Du kan maksimalt have 50 billeder per album.', type: 'error');

            return;
        }

        foreach ($this->uploadedImages as $image) {
            // Store temporarily
            $tempPath = $image->store('temp-uploads', 'local');

            // Create database record
            $albumImage = $album->images()->create([
                'path' => '',
                'thumbnail_path' => '',
                'is_processing' => true,
            ]);

            // Dispatch job
            ProcessGalleryImageJob::dispatch($albumImage, $tempPath);
        }

        $this->uploadedImages = [];
        $this->selectAlbum($albumId);

        $this->dispatch('toast', message: 'Billeder uploades...', type: 'info');
    }

    public function deleteImage($imageId)
    {
        $image = UserAlbumImage::findOrFail($imageId);
        $album = $image->album;

        // Verify ownership
        if ($album->user_id !== Auth::id()) {
            abort(403);
        }

        $disk = $album->isLocked() ? 'local' : 'public';
        \Storage::disk($disk)->delete($image->path);
        \Storage::disk($disk)->delete($image->thumbnail_path);

        $image->delete();

        $this->selectAlbum($album->id);
        $this->dispatch('toast', message: 'Billede slettet.', type: 'success');
    }

    public function requestDeleteProfilePhoto()
    {
        $this->deleteConfirmationAction = 'profile_photo';
        $this->deleteConfirmationId = null;
        $this->showDeleteConfirmation = true;
    }

    public function requestDeleteImage($imageId)
    {
        $this->deleteConfirmationAction = 'album_image';
        $this->deleteConfirmationId = $imageId;
        $this->showDeleteConfirmation = true;
    }

    public function confirmDelete()
    {
        if ($this->deleteConfirmationAction === 'profile_photo') {
            $this->deleteProfilePhoto();
        } elseif ($this->deleteConfirmationAction === 'album_image') {
            $this->deleteImage($this->deleteConfirmationId);
        }

        $this->showDeleteConfirmation = false;
        $this->deleteConfirmationAction = null;
        $this->deleteConfirmationId = null;
    }

    public function getListeners()
    {
        return [
            "echo-private:user.{$this->getUserId()},album.image.processed" => 'handleImageProcessed',
        ];
    }

    public function handleImageProcessed($event)
    {
        if ($this->selectedAlbum && $this->selectedAlbum->id == $event['album_id']) {
            $this->selectAlbum($this->selectedAlbum->id);
        }
        $this->loadAlbums();
    }

    private function getUserId()
    {
        return Auth::id();
    }

    public function render()
    {
        $paginatedAlbums = collect();
        $paginatedImages = collect();
        $allAlbumImages = collect();

        if ($this->activeTab === 'albums') {
            if (! $this->selectedAlbum) {
                $paginatedAlbums = Auth::user()->userAlbums()
                    ->withCount('images')
                    ->with(['images' => function ($query) {
                        $query->where('is_processing', false)->oldest()->limit(1);
                    }])
                    ->latest()
                    ->paginate((int) config('joys.album_pr_page', 12), pageName: 'albums-page');
            } else {
                $paginatedImages = $this->selectedAlbum->images()
                    ->latest()
                    ->paginate((int) config('joys.album_pr_page', 12), pageName: 'images-page');

                $allAlbumImages = $this->selectedAlbum->images()
                    ->with('album')
                    ->where('is_processing', false)
                    ->latest()
                    ->get();
            }
        }

        return view('livewire.member-area.edit-profile', [
            'genders' => Gender::all(), // For Account Type
            'allPreferences' => Preference::all(),
            'albums' => $paginatedAlbums,
            'images' => $paginatedImages,
            'allAlbumImages' => $allAlbumImages,
        ]);
    }
    // ==================== ALBUM RENAMING ====================

    public function startEditingAlbum($albumId)
    {
        $album = Auth::user()->userAlbums()->findOrFail($albumId);
        $this->editingAlbumId = $albumId;
        $this->editingAlbumName = $album->name;
    }

    public function cancelEditingAlbum()
    {
        $this->editingAlbumId = null;
        $this->editingAlbumName = '';
    }

    public function updateAlbumName()
    {
        if (! $this->editingAlbumId) {
            return;
        }

        $this->validate([
            'editingAlbumName' => 'required|string|max:100',
        ]);

        $album = Auth::user()->userAlbums()->findOrFail($this->editingAlbumId);
        $album->update(['name' => $this->editingAlbumName]);

        $this->editingAlbumId = null;
        $this->editingAlbumName = '';

        $this->loadAlbums();
        if ($this->selectedAlbum && $this->selectedAlbum->id === $album->id) {
            $this->selectedAlbum->refresh();
        }

        $this->dispatch('toast', message: 'Album navn opdateret', type: 'success');
    }
}
