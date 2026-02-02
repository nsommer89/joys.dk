<nav x-data class="sticky top-0 left-0 right-0 z-40 bg-[#121316]/90 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo --}}
            <div class="flex-shrink-0 group">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center relative">
                    <div class="absolute inset-0 bg-accent/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-full"></div>
                    <x-logo class="h-10 w-auto relative z-10 transition-transform duration-300 group-hover:scale-105" />
                </a>
            </div>
            
            {{-- Desktop Navigation --}}
            <div class="hidden md:flex md:items-center md:space-x-1">
                @foreach([
                    ['route' => 'who-is-coming', 'label' => 'Hvem kommer idag', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />'],
                    ['route' => 'events', 'label' => 'Events', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />', 'active' => ['events', 'event.show']],
                    ['route' => 'news.index', 'label' => 'Nyheder', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />', 'active' => ['news.index', 'news.show']],
                    ['route' => 'gallery', 'label' => 'Galleri', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />'],
                    ['route' => 'info', 'label' => 'Info', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                ] as $item)
                    @php
                        $isActive = isset($item['active']) ? request()->routeIs($item['active']) : request()->routeIs($item['route']);
                    @endphp
                    <a href="{{ route($item['route']) }}" wire:navigate class="nav-link group relative px-4 py-2 text-[15px] font-medium transition-colors duration-300 active:opacity-70 {{ $isActive ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                        <span class="relative z-10 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                {!! $item['icon'] !!}
                            </svg>
                            {{ $item['label'] }}
                        </span>
                        @if($isActive)
                            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-accent to-transparent"></div>
                        @else
                            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-accent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @endif
                        <div class="absolute inset-0 bg-white/[0.02] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                @endforeach
                
                <div class="h-8 w-px bg-white/10 mx-3 hidden lg:block"></div>
                
                @guest
                    <button 
                        @click="$dispatch('open-login-modal')" 
                        class="relative ml-2 bg-accent hover:bg-accent-hover text-white font-medium py-2 px-5 rounded-lg transition-all text-sm overflow-hidden group cursor-pointer"
                    >
                        <span class="relative z-10">Medlemsområde</span>
                        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                    </button>
                @else
                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button 
                            @click="open = !open" 
                            class="flex items-center gap-3 px-2 py-2 transition-all group focus:outline-none cursor-pointer"
                        >
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <x-avatar size="h-9 w-9" wire:persist="avatar-nav-desktop" />
                                    <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-[#121316] rounded-full"></div>
                                </div>
                                
                                <div class="text-left hidden lg:block">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[14px] font-semibold text-white whitespace-nowrap transition-colors">{{ auth()->user()->username ?? 'Medlem' }}</span>
                                        <span class="text-[9px] uppercase tracking-widest text-gray-400 font-bold px-1.5 py-0.5 bg-white/5 rounded-md border border-white/5">Medlem</span>
                                    </div>
                                </div>
                            </div>

                            <svg class="w-4 h-4 text-gray-500 group-hover:text-white transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-56 glass-card-intense border border-white/10 rounded-xl shadow-2xl py-2 z-50 overflow-hidden"
                            style="display: none;"
                        >
                            <a href="{{ route('member.profile.view', ['username' => auth()->user()->username]) }}" wire:navigate class="block px-5 py-4 border-b border-white/5 bg-white/[0.02]">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <x-avatar size="h-9 w-9" wire:persist="avatar-nav-dropdown" />
                                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-[#1c1d21] rounded-full"></div>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm text-white font-semibold truncate">{{ auth()->user()->username ?? 'Medlem' }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Medlem</p>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="space-y-0.5">
                                <a href="{{ route('member.settings') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Indstillinger</span>
                                </a>

                                <a href="{{ route('member.settings') }}#password" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Skift adgangskode</span>
                                </a>

                                <a href="{{ route('member.profile.edit') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Min profil</span>
                                </a>

                                <a href="{{ route('member.settings') }}#blocking" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Blokering</span>
                                </a>

                                <a href="{{ route('member.settings') }}#notifications" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">E-mail præferencer</span>
                                </a>
                                
                                <div class="h-px bg-white/5 my-2"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400/80 hover:bg-red-500/5 hover:text-red-400 transition-all group cursor-pointer">
                                        <div class="w-8 h-8 rounded-lg bg-red-500/5 flex items-center justify-center group-hover:bg-red-500/10 transition-colors">
                                            <svg class="w-4 h-4 text-red-400/60 group-hover:text-red-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">Log ud</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
            
            {{-- Mobile Actions --}}
            <div class="md:hidden flex items-center gap-3">
                @auth
                    {{-- Mobile User Dropdown --}}
                    <div class="relative" x-data="{ 
                        open: false,
                        init() {
                            this.$watch('open', value => {
                                if (window.innerWidth < 768) {
                                    if (value) {
                                        document.body.classList.add('overflow-hidden');
                                    } else {
                                        document.body.classList.remove('overflow-hidden');
                                    }
                                }
                            });
                        }
                    }" @click.outside="open = false">
                        <button 
                            @click="open = !open" 
                            class="rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 transition-all focus:outline-none overflow-hidden"
                        >
                            <x-avatar size="h-10 w-10" wire:persist="avatar-nav-mobile-btn" />
                        </button>

                        <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute right-0 mt-3 w-64 bg-[#0c0d10] md:glass-card-intense border border-white/10 rounded-2xl shadow-2xl py-2 z-50 overflow-hidden scrollbar-hide"
                            style="display: none;"
                        >
                            <a href="{{ route('member.profile.view', ['username' => auth()->user()->username]) }}" wire:navigate class="block px-5 py-4 border-b border-white/5 bg-white/[0.02]">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <x-avatar size="h-9 w-9" wire:persist="avatar-nav-mobile-dropdown" />
                                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-[#1c1d21] rounded-full"></div>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm text-white font-semibold truncate">{{ auth()->user()->username ?? 'Medlem' }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Medlem</p>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="space-y-0.5">
                                <a href="{{ route('member.settings') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Indstillinger</span>
                                </a>

                                <a href="{{ route('member.settings') }}#password" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Skift adgangskode</span>
                                </a>

                                <a href="{{ route('member.profile.edit') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Min profil</span>
                                </a>

                                <a href="{{ route('member.settings') }}#blocking" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Blokering</span>
                                </a>

                                <a href="{{ route('member.settings') }}#notifications" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">E-mail præferencer</span>
                                </a>
                                
                                <div class="h-px bg-white/5 my-2"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400/80 hover:bg-red-500/5 hover:text-red-400 transition-all group cursor-pointer">
                                        <div class="w-8 h-8 rounded-lg bg-red-500/5 flex items-center justify-center group-hover:bg-red-500/10 transition-colors">
                                            <svg class="w-4 h-4 text-red-400/60 group-hover:text-red-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">Log ud</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Burger Button --}}
                <button 
                    @click="$dispatch('toggle-mobile-menu')" 
                    class="p-2 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 transition-all"
                >
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
