<div class="min-h-screen">
    {{-- Hero Section --}}
    <x-ui.page-header 
        title="Joys <span class='text-accent'>Events</span>" 
        description="Her finder du en oversigt over alle kommende events og arrangementer. Tilmeld dig og vær med til at skabe den gode stemning."
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        {{-- Filter / Search Placeholder (Optional for future) --}}
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="glass-card rounded-xl overflow-hidden hover-elevate group flex flex-col h-full">
                    {{-- Event Image --}}
                    <a href="{{ route('event.show', $event) }}" class="block flex-1 flex flex-col" wire:navigate>
                        {{-- Event Image --}}
                        <div class="relative h-48 overflow-hidden bg-gray-900 flex-shrink-0">
                            <img 
                                src="{{ Storage::url($event->image_path) }}" 
                                alt="{{ $event->title }}" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            >
                            {{-- Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        </div>
                        
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-accent transition-colors">
                                        {{ $event->title }}
                                    </h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $event->start_time->format('d-m-Y') }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $event->start_time->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-gray-400 text-sm mb-6 leading-relaxed line-clamp-3">
                                {!! strip_tags($event->description) !!}
                            </div>
                        </div>
                    </a>
                    <div class="px-6 pb-6 pt-2 mt-auto">
                        <div class="flex items-center justify-between pt-4 border-t border-white/10">
                            <div class="flex items-center gap-2 text-sm text-gray-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $event->users_count }} tilmeldte
                            </div>
                            
                            @auth
                                <button 
                                    wire:click="toggleAttendance({{ $event->id }})" 
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 {{ $event->is_attending ? 'bg-emerald-500/10 text-emerald-500 hover:bg-red-500/10 hover:text-red-500 border border-emerald-500/20 hover:border-red-500/30' : 'bg-accent text-white hover:bg-accent-hover shadow-lg shadow-accent/20 hover:shadow-accent/40' }}"
                                >
                                    <span x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                                        @if($event->is_attending)
                                            <span x-show="!hover" class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Tilmeldt
                                            </span>
                                            <span x-show="hover" class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Afmeld
                                            </span>
                                        @else
                                            Tilmeld
                                        @endif
                                    </span>
                                </button>
                            @else
                                <button 
                                    @click="$dispatch('open-login-modal')"
                                    class="text-accent hover:text-accent-hover font-semibold text-sm flex items-center gap-1 group/btn"
                                >
                                    Log ind for at deltage
                                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    </div>
</div>
