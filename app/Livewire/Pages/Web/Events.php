<?php

namespace App\Livewire\Pages\Web;

use Livewire\Component;
use Livewire\WithPagination;

class Events extends Component
{
    use WithPagination;

    public function toggleAttendance($eventId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $event = \App\Models\Event::findOrFail($eventId);

        if ($event->users()->where('user_id', $user->id)->exists()) {
            $event->users()->detach($user->id);
        } else {
            $event->users()->attach($user->id);
        }
    }

    public function render()
    {
        $events = \App\Models\Event::where('start_time', '>=', now())
            ->where('is_published', true)
            ->withCount('users')
            ->withExists(['users as is_attending' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
            ->orderBy('start_time')
            ->paginate(9);

        return view('livewire.pages.web.events', [
            'events' => $events
        ])->layout('layouts.app', ['title' => 'Events - Joys.dk']);
    }
}
