<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationBell extends Component
{
    public function getUnreadCountProperty()
    {
        return auth()->user()->unreadNotifications->count();
    }

    public function getNotificationsProperty()
    {
        return auth()->user()->notifications()->take(10)->get();
    }

    public function markAsRead($notificationId)
    {
        auth()->user()->notifications()->where('id', $notificationId)->first()?->markAsRead();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
