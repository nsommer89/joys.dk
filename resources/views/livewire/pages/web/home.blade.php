<div class="min-h-screen">
    {{-- New Hero Section --}}
    <div class="relative min-h-[85vh] flex items-center justify-center overflow-hidden">
        {{-- Background Elements --}}
        <div class="absolute inset-0 bg-transparent">
            {{-- Animated Gradient Mesh --}}
            <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-accent/20 rounded-full blur-[128px] animate-pulse-slow"></div>
            <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[128px] animate-pulse-slow delay-1000"></div>
        </div>

        {{-- Content Container --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card-intense border border-white/10 mb-8 animate-fade-in-up">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-sm font-medium text-gray-300 tracking-wide">
                    Diskretion & elegance i hjertet af Jylland
                </span>
            </div>

            {{-- Main Headline --}}
            <h1 class="text-5xl sm:text-7xl md:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-b from-white via-white to-gray-400 mb-8 tracking-tight leading-none animate-fade-in-up delay-100 drop-shadow-2xl">
                Rum til <br class="hidden sm:block" />
                <span class="text-accent inline-block relative">
                    livsnydere
                    <svg class="absolute w-full h-3 -bottom-1 left-0 text-accent opacity-60" viewBox="0 0 100 10" preserveAspectRatio="none">
                        <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="2" fill="none" />
                    </svg>
                </span>
            </h1>

            {{-- Subheadline --}}
            <p class="text-lg sm:text-2xl text-gray-400 max-w-2xl mx-auto mb-12 leading-relaxed font-light animate-fade-in-up delay-200">
                Oplev en verden af muligheder i vores eksklusive og rolige univers. 
                <span class="text-gray-200 font-medium">450 m² uforstyrrede rammer</span> skabt til nærvær, leg og fællesskab.
            </p>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 animate-fade-in-up delay-300">
                <button class="group relative px-8 py-4 bg-accent hover:bg-accent-hover rounded-2xl font-bold text-white shadow-[0_0_40px_rgba(225,29,72,0.3)] hover:shadow-[0_0_60px_rgba(225,29,72,0.5)] transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    <span class="relative flex items-center gap-3 text-lg">
                        Bliv medlem idag
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </span>
                </button>
                
                <a href="{{ route('events') }}" wire:navigate class="group px-8 py-4 rounded-2xl glass-card hover:bg-white/5 border border-white/10 text-white font-semibold transition-all duration-300 hover:-translate-y-1 flex items-center gap-3 text-lg">
                    <span>Udforsk events</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </a>
            </div>

            {{-- Trust Indicators / Stats --}}
            <div class="mt-20 pt-10 border-t border-white/5 grid grid-cols-2 md:grid-cols-4 gap-8 animate-fade-in-up delay-500">
                <div>
                    <div class="text-3xl font-bold text-white mb-1">100%</div>
                    <div class="text-xs uppercase tracking-widest text-gray-500 font-bold">Diskretion</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white mb-1">450 m²</div>
                    <div class="text-xs uppercase tracking-widest text-gray-500 font-bold">Faciliteter</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white mb-1">24/7</div>
                    <div class="text-xs uppercase tracking-widest text-gray-500 font-bold">Online Community</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white mb-1">Aarhus</div>
                    <div class="text-xs uppercase tracking-widest text-gray-500 font-bold">Lokation</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Key Features Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            {{-- Feature 1 --}}
            <div class="glass-card-intense rounded-2xl p-8 hover-elevate group text-center">
                <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">100% Anonymitet</h3>
                <p class="text-gray-400 leading-relaxed">Diskret facade, private parkeringsforhold og fuld anonymitet for alle medlemmer</p>
            </div>

            {{-- Feature 2 --}}
            <div class="glass-card-intense rounded-2xl p-8 hover-elevate group text-center">
                <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">450 kvm Faciliteter</h3>
                <p class="text-gray-400 leading-relaxed">Moderne lokaler med mange muligheder for leg, afslapning og socialt samvær</p>
            </div>

            {{-- Feature 3 --}}
            <div class="glass-card-intense rounded-2xl p-8 hover-elevate group text-center">
                <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Stærkt Fællesskab</h3>
                <p class="text-gray-400 leading-relaxed">En imødekommende stemning hvor alle føler sig velkomne og respekteret</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        {{-- About & Video Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-24">
            {{-- About Joys --}}
            <div class="glass-card-intense rounded-2xl p-8 lg:p-10 hover-elevate">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-1.5 h-12 bg-gradient-to-b from-accent to-accent-hover rounded-full"></div>
                    <h2 class="text-3xl font-bold text-white">Om Joys</h2>
                </div>
                <div class="space-y-5 text-gray-300 leading-relaxed text-lg">
                    <p>
                        Joys er kendt for sin <strong class="text-white font-bold">intime, hyggelige og imødekommende stemning</strong>. 
                        Lokalerne ligger i Åbyhøj ved Århus tæt på motorvejen.
                    </p>
                    <p>
                        Den ligger anonymt gemt af vejen med en diskret facade. Der er gode parkeringsforhold. 
                        Klubben har <strong class="text-white font-bold">450 kvm "legeplads"</strong> med mange faciliteter.
                    </p>
                    <div class="pt-4">
                        <a href="{{ route('info') }}" wire:navigate class="inline-flex items-center gap-2 text-accent hover:text-accent-hover font-bold text-lg group">
                            <span>Læs mere om os</span>
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Video/Promo --}}
            <div class="glass-card-intense rounded-2xl overflow-hidden hover-elevate">
                <div class="aspect-video bg-black relative overflow-hidden">
                    <iframe 
                        class="w-full h-full" 
                        src="https://www.youtube.com/embed/q3NQIgti1dM" 
                        title="Joys Club Facilities" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen
                    ></iframe>
                </div>
                <div class="p-6 lg:p-8">
                    <h3 class="text-2xl font-bold text-white mb-3">Se vores faciliteter</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">Tag en virtuel rundtur og oplev atmosfæren hos Joys</p>
                    <button class="w-full bg-[#1877F2] hover:bg-[#1565C0] text-white font-bold py-3.5 px-4 rounded-xl transition-all flex items-center justify-center gap-3 hover:scale-105 transform shadow-lg hover:shadow-xl">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span>Find os på Facebook</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Events & News Section --}}
        <div class="mb-24">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">Kommende begivenheder</h2>
                <p class="text-xl text-gray-400">Deltag i vores events og mød nye mennesker</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($events as $event)
                    <div class="glass-card-intense rounded-2xl overflow-hidden hover-elevate cursor-pointer group flex flex-col h-full">
                        <a href="{{ route('event.show', $event) }}" class="block flex-1" wire:navigate>
                            {{-- Event Image --}}
                            <div class="relative h-48 bg-gray-900 overflow-hidden">
                                <img 
                                    src="{{ Storage::url($event->image_path) }}" 
                                    alt="{{ $event->title }}" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="flex items-center gap-3 text-white text-sm mb-2">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $event->start_time->format('d-m-Y') }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $event->start_time->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6 pb-2">
                                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-accent transition-colors">
                                    {{ $event->title }}
                                </h3>
                                <div class="text-gray-400 text-sm leading-relaxed mb-4 line-clamp-2">
                                    {!! Str::limit(strip_tags($event->description), 100) !!}
                                </div>
                            </div>
                        </a>
                        
                        <div class="px-6 pb-6 pt-2 mt-auto">
                            <div class="flex items-center justify-between pt-4 border-t border-white/10">
                                <div class="text-sm text-gray-500 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ $event->users_count }} tilmeldte
                                </div>
                                <a href="{{ route('event.show', $event) }}" wire:navigate class="text-accent hover:text-accent-hover font-bold text-sm flex items-center gap-1 group/btn">
                                    <span>Læs mere</span>
                                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('events') }}" wire:navigate class="inline-flex items-center gap-2 text-accent hover:text-accent-hover font-bold text-lg group">
                    <span>Se alle events</span>
                    <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Latest News Section --}}
        <div class="mb-24">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12">
                <div>
                    <h2 class="text-4xl font-bold text-white mb-2">Seneste nyheder</h2>
                    <p class="text-lg text-gray-400">Opdateringer fra klubben</p>
                </div>
                <a href="{{ route('news.index') }}" wire:navigate class="text-accent hover:text-accent-hover font-bold flex items-center gap-2 group mt-4 sm:mt-0">
                    <span>Nyhedsarkiv</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($news as $post)
                    <a href="{{ route('news.show', $post) }}" wire:navigate class="glass-card-intense rounded-2xl overflow-hidden hover-elevate cursor-pointer group flex flex-col h-full bg-[#1A1C23] border border-white/5 hover:border-accent/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/50">
                        <div class="h-48 relative bg-gray-900 overflow-hidden shrink-0">
                            @if($post->image_path)
                                <img src="{{ Storage::url($post->image_path) }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                    <span class="text-gray-600">Intet billede</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-[#1A1C23] via-transparent to-transparent opacity-80"></div>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-bold">{{ $post->published_at->locale('da')->translatedFormat('d. M Y') }}</span>
                                <span class="w-1 h-1 bg-accent/50 rounded-full"></span>
                                <span class="text-xs text-accent uppercase tracking-wider font-bold">Nyhed</span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-accent transition-colors line-clamp-2">
                                {{ $post->title }}
                            </h3>
                            <div class="text-gray-400 text-sm leading-relaxed mb-4 flex-1 line-clamp-3">
                                {!! strip_tags($post->content) !!}
                            </div>
                            <div class="text-accent hover:text-accent-hover font-bold text-sm flex items-center gap-1.5 group/btn mt-auto">
                                <span>Læs mere</span>
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Special Offer --}}
        <div class="glass-card-intense rounded-2xl p-8 md:p-12 mb-20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex-1">
                    <div class="inline-block mb-4">
                        <span class="bg-accent/20 text-accent border border-accent/30 px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase">
                            Eksklusivt tilbud
                        </span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">
                        15% rabat på alle varer
                    </h2>
                    <p class="text-gray-300 text-lg mb-6">
                        Gælder også varer der allerede er sat ned. Brug koden ved checkout.
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="bg-black/40 border-2 border-accent/50 rounded-lg px-6 py-3">
                            <span class="text-accent font-mono text-2xl font-bold tracking-widest">JOYS15</span>
                        </div>
                        <button class="text-gray-400 hover:text-white transition-colors" title="Kopier kode">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-48 h-48 bg-gradient-to-br from-accent to-accent-hover rounded-full flex items-center justify-center shadow-[0_0_60px_rgba(225,29,72,0.4)] relative">
                        <div class="absolute inset-4 border-4 border-white/20 rounded-full"></div>
                        <div class="text-center relative z-10">
                            <div class="text-white text-6xl font-black mb-1">15%</div>
                            <div class="text-white/90 text-sm font-semibold uppercase tracking-wide">Rabat</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
