<nav {{ $attributes->merge(['class' => 'fixed bottom-0 left-0 right-0 bg-[#121316]/95 backdrop-blur-md border-t border-gray-800 z-50']) }}>
    <div class="flex justify-around items-center h-16">
        {{-- Udforsk --}}
        <a href="{{ route('member.explore') }}" wire:navigate 
           class="flex flex-col items-center justify-center flex-1 h-full transition-colors {{ request()->routeIs('member.explore') ? 'text-accent' : 'text-gray-400 hover:text-white' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="text-[10px] mt-1">Udforsk</span>
        </a>
        
        {{-- Min Profil --}}
        <a href="{{ route('member.profile.edit') }}" wire:navigate 
           class="flex flex-col items-center justify-center flex-1 h-full transition-colors {{ request()->routeIs('member.profile.*') ? 'text-accent' : 'text-gray-400 hover:text-white' }}">
            <x-avatar size="h-6 w-6" wire:persist="avatar-mobile-tabbar" class="{{ request()->routeIs('member.profile.*') ? 'ring-accent ring-2' : '' }}" />
            <span class="text-[10px] mt-1">Min profil</span>
        </a>

        {{-- Beskeder --}}
        <a href="{{ route('member.messages') }}" wire:navigate 
           class="flex flex-col items-center justify-center flex-1 h-full transition-colors {{ request()->routeIs('member.messages') ? 'text-accent' : 'text-gray-400 hover:text-white' }}">
            <div class="relative">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{-- Badge --}}
                <div class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[16px] text-center border-2 border-[#121316]">
                    3
                </div>
            </div>
            <span class="text-[10px] mt-1">Beskeder</span>
        </a>

        {{-- Venner --}}
        <a href="{{ route('member.friends') }}" wire:navigate 
           class="flex flex-col items-center justify-center flex-1 h-full transition-colors {{ request()->routeIs('member.friends') ? 'text-accent' : 'text-gray-400 hover:text-white' }}">
            <div class="relative">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{-- Badge --}}
                <div class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[16px] text-center border-2 border-[#121316]">
                    1
                </div>
            </div>
            <span class="text-[10px] mt-1">Venner</span>
        </a>

        {{-- Chat --}}
        <a href="{{ route('member.chat') }}" wire:navigate 
           class="flex flex-col items-center justify-center flex-1 h-full transition-colors {{ request()->routeIs('member.chat') ? 'text-accent' : 'text-gray-400 hover:text-white' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span class="text-[10px] mt-1">Chat</span>
        </a>
    </div>
</nav>
