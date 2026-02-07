<nav {{ $attributes->merge(['class' => 'fixed bottom-0 left-0 right-0 bg-gradient-to-t from-[#0c0d10]/98 via-[#0c0d10]/95 to-[#0c0d10]/90 backdrop-blur-2xl border-t border-white/[0.08] z-50 shadow-[0_-8px_32px_rgba(0,0,0,0.4),0_-2px_8px_rgba(0,0,0,0.2),inset_0_1px_0_rgba(255,255,255,0.05)]']) }}>
    <div class="flex justify-around items-center h-[70px] px-1">
        {{-- Udforsk --}}
        <a href="{{ route('member.explore') }}" wire:navigate
           class="group relative flex flex-col items-center justify-center flex-1 h-full gap-0.5 transition-all duration-500 ease-out {{ request()->routeIs('member.explore') ? 'text-accent' : 'text-gray-400' }}">
            {{-- Active Glow --}}
            @if(request()->routeIs('member.explore'))
                <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-accent to-transparent"></div>
            @endif

            <div class="relative">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-500 group-active:scale-90
                    {{ request()->routeIs('member.explore') ? 'bg-accent/10 shadow-lg shadow-accent/20' : 'group-hover:bg-white/5' }}">
                    <svg class="h-6 w-6 transition-all duration-500 {{ request()->routeIs('member.explore') ? 'scale-105' : 'group-hover:scale-105' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <span class="text-[10px] font-bold tracking-wider transition-colors duration-500 {{ request()->routeIs('member.explore') ? '' : 'group-hover:text-white' }}">Udforsk</span>
        </a>

        {{-- Min Profil --}}
        <a href="{{ route('member.profile.edit') }}" wire:navigate
           class="group relative flex flex-col items-center justify-center flex-1 h-full gap-0.5 transition-all duration-500 ease-out {{ request()->routeIs('member.profile.*') ? 'text-accent' : 'text-gray-400' }}">
            {{-- Active Glow --}}
            @if(request()->routeIs('member.profile.*'))
                <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-accent to-transparent"></div>
            @endif

            <div class="relative">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-500 group-active:scale-90
                    {{ request()->routeIs('member.profile.*') ? 'bg-accent/10 shadow-lg shadow-accent/20' : 'group-hover:bg-white/5' }}">
                    <x-avatar size="h-7 w-7" wire:persist="avatar-mobile-tabbar" class="transition-transform duration-500 {{ request()->routeIs('member.profile.*') ? 'ring-accent ring-2 scale-105' : 'group-hover:scale-105' }}" />
                </div>
            </div>
            <span class="text-[10px] font-bold tracking-wider transition-colors duration-500 {{ request()->routeIs('member.profile.*') ? '' : 'group-hover:text-white' }}">Profil</span>
        </a>

        {{-- Beskeder --}}
        <a href="{{ route('member.messages') }}" wire:navigate
           class="group relative flex flex-col items-center justify-center flex-1 h-full gap-0.5 transition-all duration-500 ease-out {{ request()->routeIs('member.messages') ? 'text-accent' : 'text-gray-400' }}">
            {{-- Active Glow --}}
            @if(request()->routeIs('member.messages'))
                <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-accent to-transparent"></div>
            @endif

            <div class="relative">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-500 group-active:scale-90
                    {{ request()->routeIs('member.messages') ? 'bg-accent/10 shadow-lg shadow-accent/20' : 'group-hover:bg-white/5' }}">
                    <svg class="h-6 w-6 transition-all duration-500 {{ request()->routeIs('member.messages') ? 'scale-105' : 'group-hover:scale-105' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                {{-- Badge --}}
                <div class="absolute -top-[6px] -right-[6px] bg-gradient-to-r from-red-500 to-red-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center border-2 border-[#0c0d10] shadow-lg shadow-red-500/50 animate-pulse">
                    3
                </div>
            </div>
            <span class="text-[10px] font-bold tracking-wider transition-colors duration-500 {{ request()->routeIs('member.messages') ? '' : 'group-hover:text-white' }}">Beskeder</span>
        </a>

        {{-- Venner --}}
        <a href="{{ route('member.friends') }}" wire:navigate
           class="group relative flex flex-col items-center justify-center flex-1 h-full gap-0.5 transition-all duration-500 ease-out {{ request()->routeIs('member.friends') ? 'text-accent' : 'text-gray-400' }}">
            {{-- Active Glow --}}
            @if(request()->routeIs('member.friends'))
                <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-accent to-transparent"></div>
            @endif

            <div class="relative">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-500 group-active:scale-90
                    {{ request()->routeIs('member.friends') ? 'bg-accent/10 shadow-lg shadow-accent/20' : 'group-hover:bg-white/5' }}">
                    <svg class="h-6 w-6 transition-all duration-500 {{ request()->routeIs('member.friends') ? 'scale-105' : 'group-hover:scale-105' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                {{-- Badge --}}
                <div class="absolute -top-[6px] -right-[6px] bg-gradient-to-r from-red-500 to-red-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center border-2 border-[#0c0d10] shadow-lg shadow-red-500/50 animate-pulse">
                    1
                </div>
            </div>
            <span class="text-[10px] font-bold tracking-wider transition-colors duration-500 {{ request()->routeIs('member.friends') ? '' : 'group-hover:text-white' }}">Venner</span>
        </a>

        {{-- Chat --}}
        <a href="{{ route('member.chat') }}" wire:navigate
           class="group relative flex flex-col items-center justify-center flex-1 h-full gap-0.5 transition-all duration-500 ease-out {{ request()->routeIs('member.chat') ? 'text-accent' : 'text-gray-400' }}">
            {{-- Active Glow --}}
            @if(request()->routeIs('member.chat'))
                <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-accent to-transparent"></div>
            @endif

            <div class="relative">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-500 group-active:scale-90
                    {{ request()->routeIs('member.chat') ? 'bg-accent/10 shadow-lg shadow-accent/20' : 'group-hover:bg-white/5' }}">
                    <svg class="h-6 w-6 transition-all duration-500 {{ request()->routeIs('member.chat') ? 'scale-105' : 'group-hover:scale-105' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
            </div>
            <span class="text-[10px] font-bold tracking-wider transition-colors duration-500 {{ request()->routeIs('member.chat') ? '' : 'group-hover:text-white' }}">Chat</span>
        </a>
    </div>
</nav>
