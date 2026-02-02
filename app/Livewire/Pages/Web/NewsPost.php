<?php

namespace App\Livewire\Pages\Web;

use Livewire\Component;
use App\Models\News;

class NewsPost extends Component
{
    public News $news;

    public function mount(News $news)
    {
        $this->news = $news;

        if (!$this->news->is_published || $this->news->published_at > now()) {
            abort(404);
        }
    }

    public function render()
    {
        $latestNews = News::where('id', '!=', $this->news->id)
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('livewire.pages.web.news-post', [
            'latestNews' => $latestNews
        ])->layout('layouts.app', ['title' => $this->news->title . ' - Joys.dk']);
    }
}
