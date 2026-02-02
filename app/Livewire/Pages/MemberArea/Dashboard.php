<?php

namespace App\Livewire\Pages\MemberArea;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.pages.member-area.dashboard')
            ->layout('layouts.app', ['title' => 'Dashboard - Mit Område']);
    }
}
