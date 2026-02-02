<?php

namespace App\Livewire\Pages\Web;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $events = \App\Models\Event::where('start_time', '>=', now())
            ->where('is_published', true)
            ->withCount('users')
            ->orderBy('start_time')
            ->take(3)
            ->get();

        $news = \App\Models\News::where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('livewire.pages.web.home', [
            'events' => $events,
            'news' => $news,
        ])->layout('layouts.app', ['title' => 'Velkommen til Joys.dk']);
    }
}
