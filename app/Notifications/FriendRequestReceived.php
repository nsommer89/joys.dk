<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FriendRequestReceived extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $sender;

    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->username,
            'sender_avatar' => $this->sender->profile_photo_url,
            'message' => $this->sender->username.' har sendt dig en venneanmodning.',
            'type' => 'friend_request',
            'action_url' => route('member.friends.requests'),
        ];
    }
}
