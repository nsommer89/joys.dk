<?php

namespace App\Livewire\Pages\Web;

use App\Models\GalleryImage;
use Livewire\Component;
use Livewire\WithPagination;

class Gallery extends Component
{
    use WithPagination;

    public function render()
    {
        $images = GalleryImage::orderBy('sort_order', 'asc')
            ->paginate(12);

        return view('livewire.pages.web.gallery', [
            'images' => $images,
        ])->layout('layouts.app', ['title' => 'Galleri - Joys.dk']);
    }
}
