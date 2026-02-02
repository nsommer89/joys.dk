<div class="min-h-screen">
    {{-- Hero Section --}}
    <x-ui.page-header 
        title="Joys <span class='text-accent'>Nyheder</span>" 
        description="Hold dig opdateret med de seneste nyheder, annonceringer og historier fra Joys Swingerklub."
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($news as $post)
                <a href="{{ route('news.show', $post) }}" wire:navigate class="group flex flex-col h-full bg-[#1A1C23] border border-white/5 hover:border-accent/30 rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/50">
                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden flex-shrink-0">
                        @if($post->image_path)
                            <img 
                                src="{{ Storage::url($post->image_path) }}" 
                                alt="{{ $post->title }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                        @else
                            <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                <span class="text-gray-600">Intet billede</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1A1C23] via-transparent to-transparent opacity-80"></div>
                        
                        {{-- Date Badge --}}
                        <div class="absolute bottom-4 left-4 bg-black/60 backdrop-blur-md border border-white/10 px-3 py-1 rounded-full text-xs font-medium text-white shadow-lg">
                            {{ $post->published_at->locale('da')->translatedFormat('d. F Y') }}
                        </div>
                    </div>
                    
                    {{-- Content --}}
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="text-xl font-bold text-white mb-3 group-hover:text-accent transition-colors line-clamp-2">
                            {{ $post->title }}
                        </h3>
                        
                        <div class="text-gray-400 text-sm leading-relaxed line-clamp-3 mb-6 flex-1">
                            {!! Str::limit(strip_tags($post->content), 120) !!}
                        </div>
                        
                        <div class="flex items-center text-accent font-semibold text-sm group/link">
                            Læs artiklen
                            <svg class="w-4 h-4 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="mt-12">
            {{ $news->links() }}
        </div>
    </div>
</div>
