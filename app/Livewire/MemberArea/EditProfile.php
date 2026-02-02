<?php

namespace App\Livewire\MemberArea;

use App\Models\Gender;
use App\Models\Preference;
use App\Models\ProfilePerson;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProfile extends Component
{
    use WithFileUploads;

    public $zip_code;
    public $city;
    public $description;
    public $selected_preferences = [];
    public $profile_photo;
    public $username;
    public $is_processing_photo = false;
    public $processed_photo_path = null;
    
    // Gender/Account Type
    public $gender_id;
    public $is_couple = false;

    // Persons Data
    public $persons = [];

    public function mount()
    {
        $user = Auth::user();
        $this->loadProfile($user);
    }

    public function loadProfile($user)
    {
        $profile = $user->userProfile()->with('profilePeople')->first();
        $this->username = $user->username;
        $this->gender_id = $user->gender_id;

        if (!$this->gender_id) {
            $defaultGender = Gender::where('slug', 'mand')->first();
            $this->gender_id = $defaultGender ? $defaultGender->id : 1;
        }

        if (!$profile) {
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
        
        foreach($people as $person) {
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
        if (!preg_match('/^\d{4}$/', $value)) {
            return;
        }

        try {
            $city = \Illuminate\Support\Facades\Cache::remember('dawa_zip_' . $value, now()->addYears(2), function () use ($value) {
                $url = 'https://api.dataforsyningen.dk/postnumre/' . urlencode((string) $value);
                $response = @file_get_contents($url);
                
                if (!$response) {
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
            
            // Dispatch job
            \App\Jobs\ProcessProfileImageJob::dispatch($user->id, $path);
        } catch (\Exception $e) {
            $this->is_processing_photo = false;
            $this->dispatch('toast', message: 'Kunne ikke behandle billedet. Prøv igen.', type: 'error');
        }
    }

    public function pollPhotoStatus()
    {
        if (!$this->is_processing_photo) {
            return;
        }

        $user = Auth::user();
        $cacheKey = "user_{$user->id}_processed_image";
        
        $processedPath = \Cache::get($cacheKey);
        
        if ($processedPath) {
            $this->processed_photo_path = $processedPath;
            $this->is_processing_photo = false;
            $this->dispatch('photo-processed');
            // Clear cache so it's not reused incorrectly later if we reload
            \Cache::forget($cacheKey);
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
                // Actually, the Job saved it to 'temp-profiles/filename.jpg'
                // We should move it to 'profile-photos/filename.jpg' to keep things organized and permanent
                $newFilename = basename($this->processed_photo_path);
                $finalPath = 'profile-photos/' . $newFilename;

                \Illuminate\Support\Facades\Storage::disk('public')->move($this->processed_photo_path, $finalPath);
                
                $user->update(['profile_photo_path' => $finalPath]);
                
                // Clear the temporary state
                $this->processed_photo_path = null;
                $this->profile_photo = null; // Clear livewire upload
            }
        } elseif ($this->profile_photo && !$this->is_processing_photo) {
            // Fallback: If user uploaded but for some reason job didn't run or we want direct upload (should technically not hit here if updatedProfilePhoto works)
            // But if user clicks save BEFORE job finishes? We should probably block save while processing.
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
            if (!empty($personData['id'])) {
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

        $this->dispatch('toast', message: 'Din profil er opdateret.', type: 'success');
        
        $newPhotoUrl = $user->profile_photo_path 
            ? \Illuminate\Support\Facades\Storage::url($user->profile_photo_path)
            : asset('assets/user-male-nophoto.jpg'); // Fallback or handle null
            
        $this->dispatch('profile-updated', userId: $user->id, photoUrl: $newPhotoUrl);
    }

    public function render()
    {
        return view('livewire.member-area.edit-profile', [
            'genders' => Gender::all(), // For Account Type
            'allPreferences' => Preference::all(),
        ]);
    }
}
