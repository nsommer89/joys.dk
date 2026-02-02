<?php

namespace App\Livewire\Pages\MemberArea;

use Livewire\Component;

class Messages extends Component
{
    public function render()
    {
        return view('livewire.pages.member-area.messages')
            ->layout('layouts.app', ['title' => 'Beskeder - Mit Område']);
    }
}
