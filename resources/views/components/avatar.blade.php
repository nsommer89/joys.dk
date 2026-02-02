@props([
    'user' => null,
    'size' => 'h-10 w-10',
    'class' => ''
])

@php
    $user = $user ?? auth()->user();
    $defaultPhoto = asset('assets/user-male-nophoto.jpg');
    // PHP-side initial URL
    $currentSrc = ($user && $user->profile_photo_path) 
        ? Storage::url($user->profile_photo_path) 
        : $defaultPhoto;
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
    @if($attributes->has('wire:persist')) wire:persist="{{ $attributes->get('wire:persist') }}" @endif
    class="{{ $size }} {{ $class }} rounded-xl bg-[#1a1b1e] flex items-center justify-center text-gray-400 font-bold ring-1 ring-white/10 overflow-hidden shrink-0"
>
    <img 
        :src="src" 
        src="{{ $currentSrc }}"
        class="h-full w-full object-cover" 
        alt="{{ $user->username ?? 'User' }}"
        loading="eager"
        onerror="this.src='{{ $defaultPhoto }}'"
    />
</div>
