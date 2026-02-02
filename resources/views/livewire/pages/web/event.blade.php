<div class="min-h-screen pb-20">
    {{-- Hero/Header with Event Image --}}
    <div class="relative h-[50vh] min-h-[400px]">
        <div class="absolute inset-0">
            @if($event->image_path)
                <img 
                    src="{{ Storage::url($event->image_path) }}" 
                    alt="{{ $event->title }}" 
                    class="w-full h-full object-cover"
                >
            @else
                <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                    <span class="text-gray-700 font-bold text-4xl">Intet billede</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0 p-8 pb-12">
            <div class="max-w-7xl mx-auto">
                {{-- Breadcrumb / Back --}}
                <a href="{{ route('events') }}" wire:navigate @click.prevent="window.history.back()" class="inline-flex items-center text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 px-3 py-1.5 rounded-full transition-all duration-300 mb-6 backdrop-blur-sm bg-black/20 border border-white/10 group">
                    <svg class="w-4 h-4 mr-1.5 group-hover:-translate-x-1 transition-transform text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Tilbage til oversigt
                </a>

                {{-- Date badge --}}
                <div class="inline-flex items-center gap-2 bg-accent/90 text-white px-4 py-1.5 rounded-full text-sm font-bold tracking-wider uppercase mb-6 shadow-lg backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $event->start_time->locale('da')->translatedFormat('l d. F Y') }}
                </div>

                <div class="max-w-5xl">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 drop-shadow-xl">{{ $event->title }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-gray-200">
                        <div class="flex items-center gap-2 text-lg">
                            <svg class="w-6 h-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">{{ $event->start_time->format('H:i') }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-lg">
                            <svg class="w-6 h-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="font-medium">{{ $attendeesCount }} tilmeldte</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-8 space-y-8">
                <div class="glass-card-intense rounded-2xl p-8 hover-elevate">
                    <div class="prose prose-invert prose-lg max-w-none text-gray-300">
                        {!! $event->description !!}
                    </div>
                </div>
            </div>

            {{-- Sidebar / Actions --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Action Card --}}
                <div class="glass-card-intense rounded-2xl p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-white mb-6">Deltagelse</h3>
                    
                    @auth
                        <div class="mb-6">
                            @if($isAttending)
                                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 mb-4 flex items-center gap-3">
                                    <div class="bg-emerald-500/20 p-2 rounded-full">
                                        <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-emerald-500">Du er tilmeldt!</div>
                                        <div class="text-xs text-gray-400">Vi glæder os til at se dig</div>
                                    </div>
                                </div>
                            @endif

                            <button 
                                wire:click="toggleAttendance" 
                                wire:loading.attr="disabled"
                                class="w-full py-4 rounded-xl font-bold text-lg transition-all duration-300 flex items-center justify-center gap-2 group cursor-pointer {{ $isAttending ? 'bg-white/5 hover:bg-red-500/10 text-gray-300 hover:text-red-500 border border-white/10 hover:border-red-500/30' : 'bg-accent hover:bg-accent-hover text-white shadow-lg shadow-accent/20 hover:shadow-accent/40 hover:-translate-y-1' }}"
                            >
                                <span wire:loading.remove>
                                    @if($isAttending)
                                        <span class="group-hover:hidden flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Tilmeldt
                                        </span>
                                        <span class="hidden group-hover:flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Afmeld
                                        </span>
                                    @else
                                        Tilmeld nu
                                    @endif
                                </span>
                                <span wire:loading class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Opdaterer...
                                </span>
                            </button>
                        </div>
                    @else
                        <div class="mb-6">
                            <div class="bg-accent/10 border border-accent/20 rounded-xl p-4 mb-4 text-center">
                                <p class="text-gray-300 text-sm mb-3">Log ind for at tilmelde dig dette event</p>
                                <button 
                                    @click="$dispatch('open-login-modal')"
                                    class="text-accent font-bold hover:underline cursor-pointer"
                                >
                                    Log ind her
                                </button>
                            </div>
                        </div>
                    @endauth

                    <div class="border-t border-white/10 pt-6">
                        <h4 class="font-bold text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lokation
                        </h4>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Joys Swingerklub<br>
                            Søren Frichs Vej 54<br>
                            8230 Åbyhøj
                        </p>
                        <a href="https://maps.google.com/?q=Joys+Swingerklub+Søren+Frichs+Vej+54+8230+Åbyhøj" target="_blank" class="text-accent text-sm hover:underline mt-2 inline-block cursor-pointer">Vis på kort →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
