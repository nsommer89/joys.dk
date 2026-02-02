<?php

namespace App\Livewire\Pages\MemberArea;

use Livewire\Component;

class Explore extends Component
{
    public function render()
    {
        return view('livewire.pages.member-area.explore')
            ->layout('layouts.app', ['title' => 'Udforsk - Mit Område']);
    }
}
