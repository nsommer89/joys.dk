<?php

namespace App\Livewire\Pages\MemberArea;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Friends extends Component
{
    public $activeTab = 'venner';

    public function mount()
    {
        if (request()->routeIs('member.friends.requests')) {
            $this->activeTab = 'anmodninger';
        }
    }

    public function acceptRequest($senderId)
    {
        auth()->user()->receivedRequests()
            ->where('sender_id', $senderId)
            ->where('status', 'pending')
            ->update(['status' => 'accepted']);

        // Optionally send a notification back to the sender here
    }

    public function declineRequest($senderId)
    {
        auth()->user()->receivedRequests()
            ->where('sender_id', $senderId)
            ->where('status', 'pending')
            ->delete();
    }

    use WithPagination;

    public function getFriendsProperty()
    {
        $userId = auth()->id();

        return \App\Models\User::whereHas('sentRequests', function ($q) use ($userId) {
            $q->where('recipient_id', $userId)->where('status', 'accepted');
        })->orWhereHas('receivedRequests', function ($q) use ($userId) {
            $q->where('sender_id', $userId)->where('status', 'accepted');
        })->paginate(12);
    }

    public function getRequestsProperty()
    {
        return auth()->user()->receivedRequests()
            ->where('status', 'pending')
            ->with(['sender.userProfile', 'sender.gender'])
            ->paginate(50, pageName: 'requests-page');
    }

    public function render()
    {
        return view('livewire.pages.member-area.friends', [
            'friends' => $this->friends,
            'requests' => $this->requests,
        ]);
    }
}
