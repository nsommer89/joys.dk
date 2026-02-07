<?php

namespace App\Events;

use App\Models\UserAlbumImage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AlbumImageProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public UserAlbumImage $image
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('user.'.$this->image->album->user_id);
    }

    public function broadcastAs(): string
    {
        return 'album.image.processed';
    }

    public function broadcastWith(): array
    {
        return [
            'image_id' => $this->image->id,
            'album_id' => $this->image->user_album_id,
            'url' => $this->image->url,
            'thumbnail_url' => $this->image->thumbnail_url,
        ];
    }
}
