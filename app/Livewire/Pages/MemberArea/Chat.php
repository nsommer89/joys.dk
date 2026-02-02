<?php

namespace App\Livewire\Pages\MemberArea;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Chat extends Component
{
    public function render()
    {
        return view('livewire.pages.member-area.chat');
    }
}
