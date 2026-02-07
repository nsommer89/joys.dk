@props([
    'user' => null,
    'size' => 'h-10 w-10',
    'class' => '',
    'showStatus' => false
])

@php
    $user = $user ?? auth()->user();
    
    // Default fallback if no user (guest)
    $guestPlaceholder = Vite::asset('resources/assets/user-male-nophoto.jpg'); 

    if ($user) {
        $currentSrc = $user->profile_photo_url;
        $defaultPhoto = $user->placeholder_photo_url;
        
        $isOnline = $user->isCurrentlyOnline();
    } else {
        $currentSrc = $guestPlaceholder;
        $defaultPhoto = $guestPlaceholder;
        $isOnline = false;
    }
@endphp

<div 
    x-data="{ 
        src: '{{ $currentSrc }}',
        userId: {{ $user ? $user->id : 'null' }}
    }"
    x-on:profile-updated.window="
        if ($event.detail.userId === userId) {
            src = $event.detail.photoUrl;
        }
    "
    wire:key="avatar-{{ $user->id ?? 'guest' }}-{{ $size }}-{{ $class }}"
    {{ $attributes->merge(['class' => "$size $class rounded-xl bg-[#1a1b1e] shrink-0 relative"]) }}
>
    {{-- Inner container for image with rounding and overflow hidden --}}
    <div class="w-full h-full rounded-xl overflow-hidden flex items-center justify-center text-gray-400 font-bold ring-1 ring-white/10">
        <img 
            :src="src" 
            src="{{ $currentSrc }}"
            class="h-full w-full object-cover" 
            alt="{{ $user->username ?? 'User' }}"
            loading="eager"
            onerror="this.src='{{ $defaultPhoto }}'"
        />
    </div>

    @if($showStatus && $user)
        @if($isOnline)
            <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-[#1a1b1e] rounded-full z-10" title="Online"></div>
        @else
            <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-rose-500 border-2 border-[#1a1b1e] rounded-full z-10" title="Offline"></div>
        @endif
    @endif
</div>
