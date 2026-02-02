<?php

namespace App\Livewire\MemberArea;

use App\Models\User;
use Livewire\Component;

class PublicProfile extends Component
{
    public $user;
    public $profile;
    public $persons = [];

    public function mount($username)
    {
        $this->user = User::where('username', $username)->with('userProfile.profilePeople', 'gender', 'preferences')->firstOrFail();
        $this->profile = $this->user->userProfile;
        
        if ($this->profile) {
            $this->persons = $this->profile->profilePeople()->orderBy('id')->get();
        }
    }

    public function render()
    {
        return view('livewire.member-area.public-profile')
            ->layout('layouts.app', ['title' => $this->user->name . ' - Profil']);
    }
}
