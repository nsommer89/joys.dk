@php
    $isMemberArea = request()->routeIs('member.*');
@endphp

<div
    x-data="{
        isMemberArea: {{ $isMemberArea ? 'true' : 'false' }},
        hovering: false,
        currentPath: window.location.pathname,

        get expanded() {
            return $store.sidebar.expanded;
        },

        toggle() {
            $store.sidebar.toggle();
        },

        isRouteActive(route) {
            // Extract pathname from full URL if needed
            let routePath = route;
            if (route.startsWith('http://') || route.startsWith('https://')) {
                try {
                    routePath = new URL(route).pathname;
                } catch (e) {
                    routePath = route;
                }
            }

            // Profile routes: /medlem/profile/edit should match /medlem/profile/edit/albums, etc.
            if (routePath === '/medlem/profile/edit') {
                return this.currentPath.startsWith('/medlem/profile/edit');
            }
            // Friends routes: /medlem/friends should match /medlem/friends/requests
            if (routePath === '/medlem/friends') {
                return this.currentPath.startsWith('/medlem/friends');
            }
            // Messages routes: for future sub-routes
            if (routePath === '/medlem/messages') {
                return this.currentPath.startsWith('/medlem/messages');
            }
            // Explore routes: for future sub-routes
            if (routePath === '/medlem/explore') {
                return this.currentPath.startsWith('/medlem/explore');
            }
            // Other routes: exact match or with trailing slash
            return this.currentPath === routePath || this.currentPath === routePath + '/';
        },

        init() {
            // Set initial state if not set
            const savedState = localStorage.getItem('sidebar-expanded');
            if (savedState === null) {
                this.handleResponsiveState();
            }

            window.addEventListener('resize', () => {
                // Only auto-adjust if user hasn't manually toggled
                if (localStorage.getItem('sidebar-expanded') === null) {
                    this.handleResponsiveState();
                }
            });
        },

        handleResponsiveState() {
            if (this.isMemberArea) {
                // If generous space (Large desktop), keep fully expanded.
                // Otherwise (Tablet/Laptop), collapse to icon view to save space.
                if (window.innerWidth >= 1500) {
                    $store.sidebar.setExpanded(true);
                } else {
                    $store.sidebar.setExpanded(false);
                }
            } else {
                // Public area always starts collapsed
                $store.sidebar.setExpanded(false);
            }
        }
    }"
    x-on:livewire:navigated.window="
        currentPath = window.location.pathname;
        isMemberArea = currentPath.startsWith('/medlem');
        if (localStorage.getItem('sidebar-expanded') === null) {
            if (isMemberArea) {
                if (window.innerWidth >= 1500) {
                    $store.sidebar.setExpanded(true);
                } else {
                    $store.sidebar.setExpanded(false);
                }
            } else {
                $store.sidebar.setExpanded(false);
            }
        }
    "
    class="relative w-20" 
    @mouseenter="hovering = true"
    @mouseleave="hovering = false"
    @click.outside="if(!isMemberArea) { expanded = false; hovering = false; }"
>
    {{-- The Sidebar Card --}}
    <aside
        class="w-20 relative flex flex-col rounded-2xl overflow-hidden origin-top-left"
        :class="[
            (expanded || (hovering && !isMemberArea)) ? 'w-64 absolute top-0 left-0 z-50' : 'w-20 relative',
            'transition-all duration-500 ease-out',
            'bg-gradient-to-b from-[#0c0d10]/95 via-[#0c0d10]/98 to-[#0c0d10]',
            'backdrop-blur-2xl',
            'border border-white/[0.08]',
            'shadow-[0_8px_32px_rgba(0,0,0,0.4),0_2px_8px_rgba(0,0,0,0.2),inset_0_1px_0_rgba(255,255,255,0.05)]',
            'hover:shadow-[0_12px_48px_rgba(0,0,0,0.5),0_4px_16px_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.08)]',
            'hover:border-white/[0.12]'
        ]"
    >
        {{-- Header / Profile Summary --}}
        <div class="p-4 border-b border-white/[0.06] bg-gradient-to-b from-white/[0.03] to-transparent overflow-hidden flex-shrink-0 backdrop-blur-sm">
            {{-- Expanded Layout: Horizontal --}}
            <div
                x-show="expanded || (hovering && !isMemberArea)"
                x-cloak
                class="flex items-center gap-2"
            >
                <a href="{{ route('member.profile.view', ['username' => auth()->user()->username]) }}"
                   wire:navigate
                   class="flex items-center gap-3 flex-1 min-w-0 transition-all duration-300"
                >
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 relative">
                        <x-avatar wire:persist="avatar-sidebar" class="group-hover:scale-105 transition-transform" show-status="true" />
                    </div>

                    {{-- User Info --}}
                    <div class="flex-1 min-w-0 overflow-hidden">
                        <p class="text-sm font-bold text-white truncate w-32 transition-colors">
                            {{ auth()->user()->username }}
                        </p>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest truncate">
                            Medlem
                        </p>
                    </div>
                </a>

                {{-- Toggle Button --}}
                <button
                    x-show="isMemberArea"
                    @click="toggle()"
                    class="group flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:text-white transition-all duration-300 hover:bg-white/10 active:scale-95 hover:shadow-lg hover:shadow-white/5"
                    title="Fold sammen"
                >
                    <svg class="w-4 h-4 transition-transform duration-500 ease-out rotate-180 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            {{-- Collapsed Layout: Vertical Stack --}}
            <div
                x-show="!(expanded || (hovering && !isMemberArea))"
                class="flex flex-col items-center gap-2"
            >
                <a href="{{ route('member.profile.view', ['username' => auth()->user()->username]) }}"
                   wire:navigate
                   class="flex items-center justify-center transition-all duration-300"
                   @click="if(!isMemberArea && !expanded) { $event.preventDefault(); toggle(); }"
                >
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 relative">
                        <x-avatar wire:persist="avatar-sidebar-collapsed" class="group-hover:scale-105 transition-transform" show-status="true" />
                    </div>
                </a>

                {{-- Toggle Button --}}
                <button
                    x-show="isMemberArea"
                    @click="toggle()"
                    class="group w-8 h-6 rounded-lg flex items-center justify-center text-gray-500 hover:text-white transition-all duration-300 hover:bg-white/10 active:scale-95 hover:shadow-lg hover:shadow-white/5"
                    title="Fold ud"
                >
                    <svg class="w-4 h-4 transition-transform duration-500 ease-out group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>
        </div>
        
        {{-- Navigation Items --}}
        <nav class="flex-col px-2 py-4 space-y-1">
            @php
                $navItems = [
                    ['route' => 'member.explore', 'label' => 'Udforsk', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'badge' => 0],
                    ['route' => 'member.profile.edit', 'label' => 'Rediger profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'badge' => 0],
                    ['route' => 'member.messages', 'label' => 'Beskeder', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'badge' => 3],
                    ['route' => 'member.friends', 'label' => 'Venner', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'badge' => 1],
                    ['route' => 'member.chat', 'label' => 'Chat', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'badge' => 0]
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" wire:navigate
                   class="flex items-center px-3 py-2.5 rounded-xl group relative overflow-hidden
                        transition-all duration-300 ease-out active:scale-[0.98] border"
                   :class="{
                        'justify-center': !(expanded || (hovering && !isMemberArea)),
                        'bg-gradient-to-r from-white/[0.08] to-white/[0.04] text-white shadow-lg shadow-white/5 border-white/10': isRouteActive('{{ route($item['route']) }}'),
                        'text-gray-400 border-transparent hover:bg-white/[0.06] hover:text-white hover:border-white/5': !isRouteActive('{{ route($item['route']) }}')
                   }"
                   :title="!(expanded || (hovering && !isMemberArea)) ? '{{ $item['label'] }}' : ''"
                >
                    {{-- Hover glow effect --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-rose-500/0 via-rose-500/5 to-rose-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    {{-- Active indicator --}}
                    <div
                        x-show="isRouteActive('{{ route($item['route']) }}')"
                        class="absolute left-0 top-2 bottom-2 w-1 bg-gradient-to-b from-rose-500 via-accent to-rose-500 rounded-r-full shadow-lg shadow-rose-500/50"
                    ></div>

                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 relative"
                         :class="{
                            'text-accent scale-110': isRouteActive('{{ route($item['route']) }}'),
                            'text-gray-500 group-hover:text-gray-300 group-hover:scale-110': !isRouteActive('{{ route($item['route']) }}')
                         }">
                        <svg class="h-5 w-5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                        </svg>

                        {{-- Collapsed Badge (Dot) --}}
                        @if($item['badge'] > 0)
                            <div
                                x-show="!(expanded || (hovering && !isMemberArea))"
                                class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-[#0c0d10] animate-pulse shadow-lg shadow-red-500/50"
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
                            <span class="bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center shadow-lg shadow-red-500/30 animate-pulse">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </nav>
        
        {{-- Logout Button --}}
        <div class="p-2 border-t border-white/[0.06] bg-gradient-to-t from-black/10 to-transparent flex-shrink-0 backdrop-blur-sm">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center px-3 py-2.5 rounded-xl transition-all duration-300 ease-out text-gray-500 hover:bg-red-500/10 hover:text-red-400 group cursor-pointer active:scale-95 border border-transparent hover:border-red-500/20 hover:shadow-lg hover:shadow-red-500/5"
                    :class="(expanded || (hovering && !isMemberArea)) ? '' : 'justify-center'"
                    :title="!(expanded || (hovering && !isMemberArea)) ? 'Log ud' : ''"
                >
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 group-hover:bg-red-500/10 group-hover:scale-110">
                        <svg class="h-5 w-5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
