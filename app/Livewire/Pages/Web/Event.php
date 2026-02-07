<?php

namespace App\Livewire\Pages\Web;

use App\Models\Event as EventModel;
use Livewire\Component;

class Event extends Component
{
    public EventModel $event;

    public function mount(EventModel $event)
    {
        $this->event = $event;

        if (! $this->event->is_published) {
            abort(404);
        }
    }

    public function toggleAttendance()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($this->event->users()->where('user_id', $user->id)->exists()) {
            $this->event->users()->detach($user->id);
        } else {
            $this->event->users()->attach($user->id);
        }
    }

    public function render()
    {
        return view('livewire.pages.web.event', [
            'isAttending' => auth()->check() ? $this->event->users()->where('user_id', auth()->id())->exists() : false,
            'attendeesCount' => $this->event->users()->count(),
            'attendees' => $this->event->users()->inRandomOrder()->take(20)->get(), // Show up to 20 random attendees
        ])->layout('layouts.app', ['title' => $this->event->title.' - Joys.dk']);
    }
}
