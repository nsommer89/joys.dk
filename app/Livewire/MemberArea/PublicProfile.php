<?php

namespace App\Livewire\MemberArea;

use App\Models\User;
use App\Models\UserAlbum;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PublicProfile extends Component
{
    use WithPagination;

    public $user;

    public $profile;

    public $persons = [];

    // Album viewing
    // public $albums = []; // Removed for pagination
    /** @var UserAlbum|null */
    public $selectedAlbum = null;

    public $pendingAlbumId = null; // Album waiting for password

    public $albumPassword = '';

    public $showPasswordModal = false;

    public $passwordError = '';

    // Tab state (persisted across Livewire requests)
    public $activeTab = 'profil';

    public $selectedImageId = '';

    // Friend & Block Status
    public $friendStatus = null; // 'friend', 'pending_sent', 'pending_received', 'none'

    public $isBlocked = false;

    public $hasBlockedMe = false;

    // Confirmation Modal State
    public $showConfirmationModal = false;

    public $confirmationTitle = '';

    public $confirmationMessage = '';

    public $pendingAction = null;

    public function triggerConfirm($action)
    {
        $this->pendingAction = $action;
        $this->showConfirmationModal = true;

        switch ($action) {
            case 'removeFriend':
                $this->confirmationTitle = 'Fjern ven';
                $this->confirmationMessage = 'Er du sikker på, at du vil fjerne denne bruger fra dine venner?';
                break;
            case 'blockUser':
                $this->confirmationTitle = 'Bloker bruger';
                $this->confirmationMessage = 'Er du sikker på, at du vil blokere denne bruger? I vil ikke længere kunne se hinandens profiler eller sende beskeder.';
                break;
            case 'unblockUser':
                $this->confirmationTitle = 'Fjern blokering';
                $this->confirmationMessage = 'Er du sikker på, at du vil fjerne blokeringen af denne bruger?';
                break;
        }
    }

    public function proceedWithAction()
    {
        if ($this->pendingAction && method_exists($this, $this->pendingAction)) {
            $this->{$this->pendingAction}();
        }

        $this->closeConfirmationModal();
    }

    public function closeConfirmationModal()
    {
        $this->showConfirmationModal = false;
        $this->pendingAction = null;
    }

    public function mount($username, $albumSlug = null, $imageHash = null)
    {
        $this->user = User::where('username', $username)->with('userProfile.profilePeople', 'gender', 'preferences')->firstOrFail();

        // --- Friend & Block Logic ---
        if (auth()->check() && auth()->id() !== $this->user->id) {
            $currentUser = auth()->user();

            // Check Block
            $this->isBlocked = $currentUser->hasBlocked($this->user);
            $this->hasBlockedMe = $currentUser->isBlockedBy($this->user);

            // Check Friend Status
            if ($currentUser->isFriendWith($this->user)) {
                $this->friendStatus = 'friend';
            } elseif ($currentUser->hasSentFriendRequestTo($this->user)) {
                $this->friendStatus = 'pending_sent';
            } elseif ($currentUser->hasReceivedFriendRequestFrom($this->user)) {
                $this->friendStatus = 'pending_received';
            } else {
                $this->friendStatus = 'none';
            }
        }
        // -----------------------------

        $this->profile = $this->user->userProfile;

        if ($this->profile) {
            $this->persons = $this->profile->profilePeople()->orderBy('id')->get();
        }

        // Load user's albums
        $this->loadAlbums();

        // Determine active tab based on URL
        if (request()->is('*/albums*') || $albumSlug) {
            $this->activeTab = 'albums';
        } elseif (request()->routeIs('member.profile.friends')) {
            $this->activeTab = 'friends';
        }

        // Handle album slug from URL
        if ($albumSlug) {
            $album = $this->user->userAlbums()->where('slug', $albumSlug)->firstOrFail();

            $isOwner = auth()->id() === $this->user->id;

            if ($album->isLocked() && ! $isOwner) {
                // Check if unlocked in session
                $unlockedAlbums = session('unlocked_albums', []);
                $isUnlocked = isset($unlockedAlbums[$album->id]) && $unlockedAlbums[$album->id] === $album->password;

                if (! $isUnlocked) {
                    // Access denied - show password modal instead of selecting album
                    $this->pendingAlbumId = $album->id;
                    $this->showPasswordModal = true;
                    // Reset selected image if access denied
                    $this->selectedImageId = '';

                    return;
                }
            }

            $this->selectAlbum($album->id);

            // Handle deep link image via Hash
            if ($imageHash && $this->selectedAlbum) {
                // Find image by hash
                $image = $this->selectedAlbum->images->where('hash', $imageHash)->first();

                if ($image && ! $image->is_processing) {
                    $this->selectedImageId = $image->hash; // Use hash as ID for frontend consistency if we switch frontend to key by hash
                } else {
                    $this->selectedImageId = '';
                }
            }
        } else {
            $this->selectedImageId = '';
        }
    }

    // Error Modal State
    public $showErrorModal = false;

    public $errorMessage = '';

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function sendFriendRequest()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        // Refresh Blocking Status from DB to handle race conditions
        $currentUser = auth()->user();
        if ($currentUser->hasBlocked($this->user)) {
            $this->isBlocked = true;
        }
        if ($currentUser->isBlockedBy($this->user)) {
            $this->hasBlockedMe = true;
        }

        // Cannot send request if blocked by either side
        if ($this->isBlocked || $this->hasBlockedMe) {
            $this->errorMessage = 'Der skete en fejl.';
            $this->showErrorModal = true;

            return;
        }

        auth()->user()->sentRequests()->create([
            'recipient_id' => $this->user->id,
            'status' => 'pending',
        ]);

        // Send Notification
        $this->user->notify(new \App\Notifications\FriendRequestReceived(auth()->user()));

        $this->friendStatus = 'pending_sent';
    }

    public function cancelFriendRequest()
    {
        if (! auth()->check()) {
            return;
        }

        auth()->user()->sentRequests()
            ->where('recipient_id', $this->user->id)
            ->where('status', 'pending')
            ->delete();

        $this->friendStatus = 'none';
    }

    public function declineFriendRequest()
    {
        if (! auth()->check()) {
            return;
        }

        auth()->user()->receivedRequests()
            ->where('sender_id', $this->user->id)
            ->where('status', 'pending')
            ->delete();

        $this->friendStatus = 'none';
    }

    public function acceptFriendRequest()
    {
        if (! auth()->check()) {
            return;
        }

        auth()->user()->receivedRequests()
            ->where('sender_id', $this->user->id)
            ->where('status', 'pending')
            ->update(['status' => 'accepted']);

        $this->friendStatus = 'friend';
    }

    public function removeFriend()
    {
        if (! auth()->check()) {
            return;
        }

        \App\Models\Friendship::where(function ($q) {
            $q->where('sender_id', auth()->id())->where('recipient_id', $this->user->id);
        })->orWhere(function ($q) {
            $q->where('sender_id', $this->user->id)->where('recipient_id', auth()->id());
        })->delete();

        $this->friendStatus = 'none';
    }

    public function blockUser()
    {
        if (! auth()->check()) {
            return;
        }

        // Remove any existing friendship/request
        $this->removeFriend(); // This deletes any record between them

        // Create block record
        auth()->user()->sentRequests()->create([
            'recipient_id' => $this->user->id,
            'status' => 'blocked',
        ]);

        $this->isBlocked = true;
        $this->friendStatus = 'none';
    }

    public function unblockUser()
    {
        if (! auth()->check()) {
            return;
        }

        auth()->user()->sentRequests()
            ->where('recipient_id', $this->user->id)
            ->where('status', 'blocked')
            ->delete();

        $this->isBlocked = false;
    }

    public function loadAlbums()
    {
        // No longer storing in property to support pagination
    }

    public function selectAlbum($albumId)
    {
        $album = UserAlbum::findOrFail($albumId);

        // Check if locked
        // Check if locked
        $isOwner = auth()->id() === $album->user_id;

        if ($album->isLocked() && ! $isOwner) {
            $unlockedAlbums = session('unlocked_albums', []);

            // Check if unlocked (ID key exists AND hash matches)
            $isUnlocked = isset($unlockedAlbums[$album->id]) && $unlockedAlbums[$album->id] === $album->password;

            if (! $isUnlocked) {
                // Don't load images yet, just show password modal
                $this->pendingAlbumId = $albumId;
                $this->showPasswordModal = true;

                return;
            }
        }

        // Album is unlocked or public, load with images
        $this->selectedAlbum = UserAlbum::with(['images' => function ($query) {
            $query->where('is_processing', false)->latest();
        }])->findOrFail($albumId);

        $this->showPasswordModal = false;
    }

    public function unlockAlbum()
    {
        if (! $this->pendingAlbumId) {
            return;
        }

        $album = UserAlbum::findOrFail($this->pendingAlbumId);

        if ($album->verifyPassword($this->albumPassword)) {
            // Add to session
            $unlockedAlbums = session('unlocked_albums', []);
            // Support migration from old numeric indexed array to associative array
            // If it's a numeric array (old format), we reset it to avoid issues
            if (array_is_list($unlockedAlbums) && count($unlockedAlbums) > 0) {
                $unlockedAlbums = [];
            }

            // Store album ID associated with the current password hash
            $unlockedAlbums[$album->id] = $album->password;
            session(['unlocked_albums' => $unlockedAlbums]);

            // Now load album with images
            $this->selectedAlbum = UserAlbum::with(['images' => function ($query) {
                $query->where('is_processing', false)->latest();
            }])->findOrFail($album->id);

            $this->showPasswordModal = false;
            $this->pendingAlbumId = null;
            $this->albumPassword = '';
            $this->passwordError = '';
        } else {
            $this->passwordError = 'Forkert adgangskode';
            $this->albumPassword = '';
        }
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
        $this->pendingAlbumId = null;
        $this->albumPassword = '';
        $this->passwordError = '';
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $paginatedAlbums = collect();
        $paginatedImages = collect();
        $allAlbumImages = collect();

        if ($this->activeTab === 'albums') {
            if (! $this->selectedAlbum) {
                $paginatedAlbums = $this->user->userAlbums()
                    ->withCount('images')
                    ->with(['images' => function ($query) {
                        $query->where('is_processing', false)->oldest()->limit(1);
                    }])
                    ->latest()
                    ->paginate((int) config('joys.album_pr_page', 12), pageName: 'albums-page');
            } else {
                $paginatedImages = $this->selectedAlbum->images()
                    ->where('is_processing', false)
                    ->latest()
                    ->paginate((int) config('joys.album_pr_page', 12), pageName: 'images-page');

                $allAlbumImages = $this->selectedAlbum->images()
                    ->with('album')
                    ->where('is_processing', false)
                    ->latest()
                    ->get();
            }
        }

        $friends = collect();
        if ($this->activeTab === 'friends') {
            $userId = $this->user->id;
            $friends = \App\Models\User::whereHas('sentRequests', function ($q) use ($userId) {
                $q->where('recipient_id', $userId)->where('status', 'accepted');
            })->orWhereHas('receivedRequests', function ($q) use ($userId) {
                $q->where('sender_id', $userId)->where('status', 'accepted');
            })->paginate(12, pageName: 'friends-page');
        }

        return view('livewire.member-area.public-profile', [
            'albums' => $paginatedAlbums,
            'images' => $paginatedImages,
            'allAlbumImages' => $allAlbumImages,
            'hasAlbums' => $this->user->userAlbums()->exists(),
            'events' => $this->user->events()->where('start_time', '>=', now())->orderBy('start_time')->get(),
            'friends' => $friends,
        ])->title($this->user->username.' - Profil');
    }
}
