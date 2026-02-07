@props(['user'])

<a href="{{ route('member.profile.view', $user->username) }}" wire:navigate class="group bg-[#151619] p-4 rounded-lg border border-white/5 hover:border-emerald-500/30 transition-colors block">
    <div class="flex items-center gap-3">
        <x-avatar :user="$user" size="w-12 h-12" class="ring-2 ring-white/10 group-hover:ring-emerald-500/50 transition-all" show-status="true" />
        <div class="min-w-0">
            <h4 class="text-white font-bold truncate group-hover:text-emerald-400 transition-colors">{{ $user->username }}</h4>
            @if($user->userProfile?->city)
                <p class="text-xs text-gray-500 truncate">{{ $user->userProfile->city }}</p>
            @endif
        </div>
    </div>
</a>
