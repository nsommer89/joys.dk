<?php

namespace App\Livewire\Pages\MemberArea;

use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
        return view('livewire.pages.member-area.profile')
            ->layout('layouts.app', ['title' => 'Profil - Mit Område']);
    }
}
