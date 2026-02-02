<div class="min-h-screen bg-[#0c0d10] pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Breadcrumb --}}
        <a href="{{ route('news.index') }}" wire:navigate @click.prevent="window.history.back()" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-white px-3 py-1.5 rounded-full transition-all duration-300 mb-8 bg-white/5 border border-white/10 group hover:border-white/20">
            <svg class="w-4 h-4 mr-1.5 group-hover:-translate-x-1 transition-transform text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Tilbage til oversigt
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 animate-fade-in-up">
            {{-- Main Content --}}
            <div class="lg:col-span-7 space-y-8">
                {{-- Header Info --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-4 text-sm font-medium">
                        <span class="px-3 py-1 rounded-md bg-accent/10 border border-accent/20 text-accent font-bold shadow-lg shadow-accent/5">Nyhed</span>
                        <div class="flex items-center gap-2 text-gray-400">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $news->published_at->locale('da')->translatedFormat('d. F Y') }}
                        </div>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight tracking-tight">
                        {{ $news->title }}
                    </h1>
                </div>

                {{-- Article Body --}}
                <div class="prose prose-invert prose-lg max-w-none prose-headings:font-bold prose-headings:text-white prose-a:text-accent prose-a:no-underline hover:prose-a:underline prose-blockquote:border-l-accent prose-blockquote:bg-white/5 prose-blockquote:py-2 prose-blockquote:px-4 prose-blockquote:rounded-r-lg prose-img:rounded-xl">
                    <div class="text-gray-300 leading-relaxed space-y-6">
                        {!! $news->content !!}
                    </div>
                </div>

                {{-- Author Footer --}}
                <div class="pt-8 border-t border-white/10 mt-8">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-accent to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg ring-2 ring-white/10">
                            J
                        </div>
                        <div>
                            <p class="text-white font-bold text-lg">Joys Swingerklub</p>
                            <p class="text-sm text-gray-500">Forfatter & Redaktion</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sticky Image Column --}}
            <div class="lg:col-span-5">
                <div class="sticky top-24 space-y-6">
                    <div class="aspect-[4/3] w-full rounded-2xl overflow-hidden shadow-2xl border border-white/10 bg-gray-900 group">
                        @if($news->image_path)
                            <img 
                                src="{{ Storage::url($news->image_path) }}" 
                                alt="{{ $news->title }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            >
                        @else
                            <div class="w-full h-full bg-gray-900 pattern-grid-lg opacity-20 flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .animate-slow-zoom {
            animation: slowZoom 20s infinite alternate;
        }
        @keyframes slowZoom {
            from { transform: scale(1.05); }
            to { transform: scale(1.15); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div>
