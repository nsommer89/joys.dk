<?php

namespace App\Livewire\Pages\Web;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\GalleryImage;

class Gallery extends Component
{
    use WithPagination;

    public function render()
    {
        $images = GalleryImage::orderBy('sort_order', 'asc')
            ->paginate(24);

        return view('livewire.pages.web.gallery', [
            'images' => $images
        ])->layout('layouts.app', ['title' => 'Galleri - Joys.dk']);
    }
}
