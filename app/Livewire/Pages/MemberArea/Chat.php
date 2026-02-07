<?php

namespace App\Livewire\Pages\MemberArea;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Chat extends Component
{
    public function render()
    {
        return view('livewire.pages.member-area.chat');
    }
}
