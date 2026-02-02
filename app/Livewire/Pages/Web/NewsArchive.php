<?php

namespace App\Livewire\Pages\Web;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\News;

class NewsArchive extends Component
{
    use WithPagination;

    public function render()
    {
        $news = News::where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('livewire.pages.web.news-archive', [
            'news' => $news
        ])->layout('layouts.app', ['title' => 'Nyheder - Joys.dk']);
    }
}
