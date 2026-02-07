<?php

namespace App\Livewire\Pages\Web;

use Livewire\Component;

class Info extends Component
{
    public $section = 'om-joys';

    public function mount($section = null)
    {
        if ($section) {
            $this->section = $section;
        }

        if (! in_array($this->section, array_keys($this->getSections()))) {
            $this->section = 'om-joys';
        }
    }

    public function getSections()
    {
        return [
            'om-joys' => 'Om Joys',
            'aabningstider' => 'Åbningstider',
            'regler' => 'Regler',
            'priser' => 'Priser',
            'hjaelp' => 'Hjælp',
            'samkoersel' => 'Samkørsel',
            'kontakt' => 'Kontakt',
        ];
    }

    public function setSection($section)
    {
        $this->section = $section;
    }

    public function render()
    {
        return view('livewire.pages.web.info')
            ->layout('layouts.app', ['title' => 'Info - Joys.dk']);
    }
}
