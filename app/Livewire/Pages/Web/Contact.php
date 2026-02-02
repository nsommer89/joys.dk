<?php

namespace App\Livewire\Pages\Web;

use Livewire\Component;

class Contact extends Component
{
    public function render()
    {
        return view('livewire.pages.web.contact')
            ->layout('layouts.app', ['title' => 'Kontakt - Joys.dk']);
    }
}
