<div 
    id="mobile-drawer"
    x-data="{ 
        get open() { return $store.mobileMenu?.open ?? false },
        set open(value) { if ($store.mobileMenu) $store.mobileMenu.open = value }
    }"
    x-init="$watch('open', value => {
        if (value) {
            document.body.classList.add('overflow-hidden');
            $el.classList.remove('hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    })"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[9999] md:hidden hidden"
>
    {{-- Drawer Panel (Full Screen) --}}
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="absolute inset-0 bg-[#0c0d10]/95 backdrop-blur-2xl flex flex-col overflow-hidden"
    >
        {{-- Background Effects --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-accent/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-purple-500/5 blur-[120px] rounded-full"></div>
        </div>

        {{-- Header --}}
        <div class="relative z-10 flex items-center justify-between px-6 h-16 border-b border-white/5 bg-white/[0.02] backdrop-blur-xl">
            <x-logo class="h-8 w-auto" />
            
            <button 
                onclick="document.getElementById('mobile-drawer').classList.add('hidden'); document.body.classList.remove('overflow-hidden'); if(window.Alpine && Alpine.store('mobileMenu')) Alpine.store('mobileMenu').open = false;"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-white/70 hover:text-white transition-all active:scale-95"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Navigation Links --}}
        <div class="relative z-10 flex-1 overflow-y-auto scrollbar-hide px-4 py-4">
            <nav class="space-y-1">
                @foreach([
                    ['route' => 'home', 'label' => 'Hjem', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'who-is-coming', 'label' => 'Hvem kommer idag', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['route' => 'events', 'label' => 'Events', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'news.index', 'label' => 'Nyheder', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                    ['route' => 'gallery', 'label' => 'Galleri', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'info', 'label' => 'Info', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ] as $item)
                    <a 
                        href="{{ route($item['route']) }}" 
                        wire:navigate 
                        onclick="document.getElementById('mobile-drawer').classList.add('hidden'); document.body.classList.remove('overflow-hidden');"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 border border-transparent
                            {{ request()->routeIs($item['route'])
                                ? 'bg-accent/10 border-accent/20 text-white' 
                                : 'text-gray-400 hover:bg-white/5' }}"
                    >
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors
                            {{ request()->routeIs($item['route']) ? 'bg-accent text-white' : 'bg-white/5 text-gray-400' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- Footer - Compact User Section --}}
        <div class="relative z-10 p-4 pb-8 border-t border-white/5 bg-white/[0.02] backdrop-blur-xl">
            @guest
            <button 
                    onclick="document.getElementById('mobile-drawer').classList.add('hidden'); document.body.classList.remove('overflow-hidden'); if(window.Livewire) Livewire.dispatch('open-login-modal');"
                    class="w-full bg-accent hover:bg-accent-hover text-white font-bold py-3.5 px-6 rounded-xl transition-all text-center text-lg shadow-xl shadow-accent/20 border border-white/10 relative overflow-hidden group"
                >
                    <span class="relative z-10">Log ind</span>
                    <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                </button>
            @else
                <div class="flex flex-col gap-3">
                    <a 
                        href="{{ route('member.explore') }}" 
                        wire:navigate 
                        onclick="document.getElementById('mobile-drawer').classList.add('hidden'); document.body.classList.remove('overflow-hidden');"
                        class="flex items-center justify-between p-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all group"
                    >
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <x-avatar size="h-10 w-10" wire:persist="avatar-mobile-drawer" />
                                <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-[#121316] rounded-full"></div>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-white truncate">{{ auth()->user()->username ?? 'Medlem' }}</p>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest leading-tight">Gå til medlemsområde</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-accent transform group-hover:translate-x-1 transition-transform mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            @endguest
        </div>
    </div>
</div>
