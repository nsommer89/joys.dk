<?php

namespace App\Http\Controllers;

use App\Models\UserAlbumImage;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AlbumImageController extends Controller
{
    public function show(UserAlbumImage $image): StreamedResponse
    {
        $this->authorize($image);

        $disk = $image->album->isLocked() ? 'local' : 'public';

        return Storage::disk($disk)->response($image->path);
    }

    public function thumbnail(UserAlbumImage $image): StreamedResponse
    {
        $this->authorize($image);

        $disk = $image->album->isLocked() ? 'local' : 'public';

        return Storage::disk($disk)->response($image->thumbnail_path);
    }

    private function authorize(UserAlbumImage $image): void
    {
        $album = $image->album;

        // Public albums are always accessible
        if (! $album->isLocked()) {
            return;
        }

        // Album owner always has access to their own albums
        if (auth()->check() && auth()->id() === $album->user_id) {
            return;
        }

        // Check if user has unlocked this album in their session
        $unlockedAlbums = session('unlocked_albums', []);

        // Check if album ID exists in session and password hash matches
        // New secure format: [album_id => password_hash]
        if (isset($unlockedAlbums[$album->id]) && $unlockedAlbums[$album->id] === $album->password) {
            return;
        }

        abort(403, 'Access denied to locked album');
    }
}
