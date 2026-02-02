@php
    $isMemberArea = request()->routeIs('member.*');
@endphp

<div 
    x-data="{ 
        isMemberArea: {{ $isMemberArea ? 'true' : 'false' }},
        expanded: false,
        hovering: false,
        
        toggle() {
            this.expanded = !this.expanded;
        },

        init() {
            this.handleResponsiveState();
            window.addEventListener('resize', () => this.handleResponsiveState());

            Livewire.hook('navigated', () => {
                this.isMemberArea = window.location.pathname.startsWith('/medlem');
                this.handleResponsiveState();
            });
        },

        handleResponsiveState() {
            if (this.isMemberArea) {
                // If generous space (Large desktop), keep fully expanded.
                // Otherwise (Tablet/Laptop), collapse to icon view to save space.
                if (window.innerWidth >= 1500) {
                    this.expanded = true;
                } else {
                    this.expanded = false;
                }
            } else {
                // Public area always starts collapsed
                this.expanded = false;
            }
        }
    }"
    class="relative w-20" 
    @mouseenter="hovering = true"
    @mouseleave="hovering = false"
    @click.outside="if(!isMemberArea) { expanded = false; hovering = false; }"
>
    {{-- The Sidebar Card --}}
    <aside 
        class="w-20 relative transition-all duration-300 flex flex-col rounded-2xl border border-white/10 overflow-hidden shadow-2xl origin-top-left bg-[#0c0d10]/95 backdrop-blur-xl"
        :class="[
            (expanded || (hovering && !isMemberArea)) ? 'w-64 absolute top-0 left-0 z-50' : 'w-20 relative',
            'transition-all duration-300 flex flex-col rounded-2xl border border-white/10 overflow-hidden shadow-2xl origin-top-left bg-[#0c0d10]/95 backdrop-blur-xl'
        ]"
    >
        {{-- Header / Profile Summary --}}
        <div class="p-4 border-b border-white/5 bg-white/[0.02] overflow-hidden flex-shrink-0">
            <a href="{{ route('member.profile.view', ['username' => auth()->user()->username]) }}" 
               wire:navigate 
               class="flex items-center transition-all duration-300" 
               :class="(expanded || (hovering && !isMemberArea)) ? 'gap-3' : 'justify-center'"
               @click="if(!isMemberArea && !expanded) { $event.preventDefault(); toggle(); }"
            >
                {{-- Avatar --}}
                <div class="flex-shrink-0 relative">
                    <x-avatar wire:persist="avatar-sidebar" class="group-hover:scale-105 transition-transform" />
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-[#121316] rounded-full"></div>
                </div>
                
                {{-- User Info (Visible when expanded) --}}
                <div 
                    x-cloak
                    x-show="expanded || (hovering && !isMemberArea)"
                    x-transition:enter="transition ease-out duration-200 delay-100"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="flex-1 min-w-0 overflow-hidden"
                >
                    <p class="text-sm font-bold text-white truncate w-32 transition-colors">
                        {{ auth()->user()->username }}
                    </p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest truncate">
                        Medlem
                    </p>
                </div>
            </a>
        </div>
        
        {{-- Navigation Items --}}
        <nav class="flex-col px-2 py-4 space-y-1">
            @php
                $navItems = [
                    ['route' => 'member.explore', 'label' => 'Udforsk', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'badge' => 0],
                    ['route' => 'member.profile.edit', 'label' => 'Min profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'badge' => 0],
                    ['route' => 'member.messages', 'label' => 'Beskeder', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'badge' => 3],
                    ['route' => 'member.friends', 'label' => 'Venner', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'badge' => 1],
                    ['route' => 'member.chat', 'label' => 'Chat', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'badge' => 0]
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" wire:navigate 
                   class="flex items-center px-3 py-2.5 rounded-xl transition-colors duration-300 group relative active:opacity-70
                        {{ request()->routeIs($item['route']) 
                            ? 'bg-white/5 text-white' 
                            : 'text-gray-400 hover:bg-white/5 hover:text-white' }}"
                   :class="(expanded || (hovering && !isMemberArea)) ? '' : 'justify-center'"
                   :title="!(expanded || (hovering && !isMemberArea)) ? '{{ $item['label'] }}' : ''"
                >
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center transition-colors relative
                        {{ request()->routeIs($item['route']) ? 'text-accent' : 'text-gray-500 group-hover:text-gray-300' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                        </svg>

                        {{-- Collapsed Badge (Dot) --}}
                        @if($item['badge'] > 0)
                            <div 
                                x-show="!(expanded || (hovering && !isMemberArea))"
                                class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-[#0c0d10]"
                            ></div>
                        @endif
                    </div>
                    
                    <div 
                        x-cloak
                        x-show="expanded || (hovering && !isMemberArea)" 
                        x-transition:enter="transition ease-out duration-200 delay-100"
                        x-transition:enter-start="opacity-0 translate-x-1"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="ml-3 flex-1 flex items-center justify-between overflow-hidden"
                    >
                        <span class="text-sm font-semibold whitespace-nowrap">{{ $item['label'] }}</span>
                        
                        {{-- Expanded Badge (Pill) --}}
                        @if($item['badge'] > 0)
                            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </div>
    
                    @if(request()->routeIs($item['route']))
                        <div class="absolute left-0 top-2 bottom-2 w-1 bg-accent rounded-r-full"></div>
                    @endif
                </a>
            @endforeach
        </nav>
        
        {{-- Logout Button --}}
        <div class="p-2 border-t border-white/5 flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button 
                    type="submit" 
                    class="w-full flex items-center px-3 py-2.5 rounded-xl transition-all text-gray-500 hover:bg-red-500/5 hover:text-red-400 group cursor-pointer"
                    :class="(expanded || (hovering && !isMemberArea)) ? '' : 'justify-center'"
                    :title="!(expanded || (hovering && !isMemberArea)) ? 'Log ud' : ''"
                >
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center transition-colors group-hover:bg-red-500/10">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <span 
                        x-cloak
                        x-show="expanded || (hovering && !isMemberArea)" 
                        x-transition:enter="transition ease-out duration-200 delay-100"
                        x-transition:enter-start="opacity-0 translate-x-1"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="ml-3 text-sm font-semibold whitespace-nowrap"
                    >
                        Log ud
                    </span>
                </button>
            </form>
        </div>
    </aside>
</div>
