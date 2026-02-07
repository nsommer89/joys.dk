<div class="max-w-4xl mx-auto py-12 px-4 md:px-0">
    
    <!-- Title Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">{{ $user->username }}</h1>
        <p class="text-gray-400">
            @if($profile?->city)
                {{ $profile->city }}
                @if($user->gender)
                    · {{ $user->gender->name }}
                @endif
            @elseif($user->gender)
                {{ $user->gender->name }}
            @endif
        </p>
    </div>

    <!-- Main Card Container -->
    <div class="bg-gradient-to-b from-[#1a1b1e]/95 via-[#1a1b1e]/98 to-[#1a1b1e] rounded-2xl border border-white/[0.08] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.4),0_2px_8px_rgba(0,0,0,0.2),inset_0_1px_0_rgba(255,255,255,0.05)] backdrop-blur-xl hover:shadow-[0_12px_48px_rgba(0,0,0,0.5),0_4px_16px_rgba(0,0,0,0.3)] hover:border-white/[0.12] transition-all duration-500">

        <!-- Tabs Navigation -->
        <div class="flex border-b border-white/[0.08] bg-gradient-to-b from-white/[0.03] to-transparent backdrop-blur-sm px-6">
            <a
                href="{{ route('member.profile.view', $user->username) }}"
                wire:navigate
                wire:click="setActiveTab('profil')"
                @class([
                    'relative px-8 py-4 border-b-2 font-semibold text-sm transition-all duration-500 ease-out group',
                    'border-rose-600 text-rose-500' => $activeTab === 'profil',
                    'border-transparent text-gray-400 hover:text-white' => $activeTab !== 'profil'
                ])
            >
                @if($activeTab === 'profil')
                    <div class="absolute inset-0 bg-gradient-to-t from-rose-500/5 to-transparent"></div>
                @endif
                <span class="relative">Profil</span>
            </a>
            @if($hasAlbums || auth()->id() === $user->id)
                <a
                    href="{{ route('member.profile.albums', $user->username) }}"
                    wire:navigate
                    wire:click="setActiveTab('albums')"
                    @class([
                        'relative px-8 py-4 border-b-2 font-semibold text-sm transition-all duration-500 ease-out group',
                        'border-rose-600 text-rose-500' => $activeTab === 'albums',
                        'border-transparent text-gray-400 hover:text-white' => $activeTab !== 'albums'
                    ])
                >
                    @if($activeTab === 'albums')
                        <div class="absolute inset-0 bg-gradient-to-t from-rose-500/5 to-transparent"></div>
                    @endif
                    <span class="relative">Albums</span>
                </a>
            @endif
            <a
                href="{{ route('member.profile.friends', $user->username) }}"
                wire:navigate
                wire:click="setActiveTab('friends')"
                @class([
                    'relative px-8 py-4 border-b-2 font-semibold text-sm transition-all duration-500 ease-out group',
                    'border-rose-600 text-rose-500' => $activeTab === 'friends',
                    'border-transparent text-gray-400 hover:text-white' => $activeTab !== 'friends'
                ])
            >
                @if($activeTab === 'friends')
                    <div class="absolute inset-0 bg-gradient-to-t from-rose-500/5 to-transparent"></div>
                @endif
                <span class="relative">Venner</span>
            </a>
        </div>

        <!-- Content Area -->
        <div class="p-8 md:p-10 min-h-[600px] bg-gradient-to-b from-transparent to-black/10">

    <div class="space-y-10">
        
        <!-- Header Section (Compact) -->
        <div class="px-6 md:px-0">
            <div class="flex items-center gap-5">
                <!-- Avatar -->
                <div class="shrink-0 relative group" x-data="{ showProfilePhoto: false }">
                    <div @click="showProfilePhoto = true" class="cursor-pointer transition-transform hover:scale-105 active:scale-95">
                         <x-avatar :user="$user" size="w-20 h-20 md:w-24 md:h-24" class="ring-4 ring-[#1a1b1e] !bg-[#1a1b1e]" show-status="true" />
                    </div>
                    
                    <template x-teleport="body">
                        <div 
                            x-show="showProfilePhoto" 
                            style="display: none; touch-action: manipulation;"
                            class="fixed inset-0 z-[200] bg-black/95 backdrop-blur-md flex items-center justify-center p-4 cursor-zoom-out"
                            @click="showProfilePhoto = false"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            @keydown.window.escape="showProfilePhoto = false"
                        >
                            <img 
                                src="{{ $user->profile_photo_url }}" 
                                class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl bg-[#1a1b1e]"
                                style="touch-action: manipulation"
                            >
                            
                            <button @click="showProfilePhoto = false" class="absolute top-4 right-4 z-[210] p-2 text-white/50 hover:text-white transition-colors rounded-full hover:bg-white/10">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Info & Actions -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <!-- User Details -->
                        <div>
                            <h2 class="text-2xl font-bold text-white truncate">{{ $user->username }}</h2>
                            <p class="text-gray-400 font-medium text-sm flex items-center flex-wrap gap-x-2 gap-y-1 mt-0.5">
                                @if($profile?->city)
                                    <span>{{ $profile->city }}</span>
                                    @if($user->gender)
                                        <span class="text-gray-600">·</span>
                                        <span>{{ $user->gender->name }}</span>
                                    @endif
                                @elseif($user->gender)
                                    <span>{{ $user->gender->name }}</span>
                                @endif
                            </p>
                        </div>
                    
                        <!-- Action Buttons -->
                        <div class="flex gap-3 shrink-0">
                            @if(auth()->id() === $user->id)
                                <a href="{{ route('member.profile.edit') }}" wire:navigate class="px-5 py-2 bg-[#25262b] text-white text-xs font-bold rounded-xl hover:bg-[#2c2d32] transition-colors border border-white/10 shadow-lg whitespace-nowrap">
                                    Rediger
                                </a>
                            @else
                                <div class="flex items-stretch gap-2 w-full md:w-auto">
                                    <!-- Message Button -->
                                    <button class="flex-1 md:flex-none justify-center px-4 md:px-5 py-2.5 md:py-2 bg-rose-600 text-white text-xs font-bold rounded-xl hover:bg-rose-700 transition-colors shadow-lg shadow-rose-500/20 flex items-center gap-2 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                                        Besked
                                    </button>

                                    <!-- Friend Actions -->
                                    @if($isBlocked)
                                        <button wire:click="triggerConfirm('unblockUser')" class="flex-1 md:flex-none justify-center px-4 md:px-5 py-2.5 md:py-2 bg-red-900/50 text-red-200 text-xs font-bold rounded-xl hover:bg-red-900/80 transition-colors border border-red-500/20 whitespace-nowrap">
                                            Fjern blokering
                                        </button>
                                    @else
                                        @if($friendStatus === 'friend')
                                            <div x-data="{ open: false }" class="relative flex-1 md:flex-none">
                                                <button @click="open = !open" @click.away="open = false" class="w-full justify-center px-4 md:px-5 py-2.5 md:py-2 bg-green-600/20 text-green-400 text-xs font-bold rounded-xl hover:bg-green-600/30 transition-colors border border-green-500/20 flex items-center gap-2 whitespace-nowrap">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    Venner
                                                </button>
                                                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-[#1a1b1e] border border-white/10 rounded-xl shadow-xl z-50 py-1" style="display: none;">
                                                    <button wire:click="triggerConfirm('removeFriend')" class="w-full text-left px-4 py-2 text-xs text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                                        Fjern ven
                                                    </button>
                                                    <button wire:click="triggerConfirm('blockUser')" class="w-full text-left px-4 py-2 text-xs text-red-400 hover:bg-white/5 hover:text-red-300 transition-colors">
                                                        Bloker bruger
                                                    </button>
                                                </div>
                                            </div>
                                        @elseif($friendStatus === 'pending_sent')
                                            <button wire:click="cancelFriendRequest" class="flex-1 md:flex-none justify-center px-4 md:px-5 py-2.5 md:py-2 bg-[#25262b] text-gray-400 text-xs font-bold rounded-xl hover:bg-[#2c2d32] transition-colors border border-white/10 whitespace-nowrap">
                                                Anmodning sendt
                                            </button>
                                        @elseif($friendStatus === 'pending_received')
                                            <button wire:click="declineFriendRequest" class="flex-1 md:flex-none justify-center px-4 md:px-5 py-2.5 md:py-2 bg-[#25262b] text-gray-400 text-xs font-bold rounded-xl hover:bg-[#2c2d32] transition-colors border border-white/10 whitespace-nowrap">
                                                Afvis
                                            </button>
                                            <button wire:click="acceptFriendRequest" class="flex-1 md:flex-none justify-center px-4 md:px-5 py-2.5 md:py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-500/20 whitespace-nowrap">
                                                Accepter ven
                                            </button>
                                        @else
                                            <button wire:click="sendFriendRequest" class="flex-1 md:flex-none justify-center px-4 md:px-5 py-2.5 md:py-2 bg-[#25262b] text-white text-xs font-bold rounded-xl hover:bg-[#2c2d32] transition-colors border border-white/10 hover:border-emerald-500/50 hover:text-emerald-400 flex items-center gap-2 whitespace-nowrap group">
                                                <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                                                Tilføj ven
                                            </button>
                                            
                                            <!-- Block Option (for non-friends) -->
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open" @click.away="open = false" class="h-full w-10 md:w-auto md:px-2 text-gray-500 hover:text-white transition-colors rounded-lg hover:bg-white/5 flex items-center justify-center border border-transparent hover:border-white/5">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                                </button>
                                                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-[#1a1b1e] border border-white/10 rounded-xl shadow-xl z-50 py-1" style="display: none;">
                                                    <button wire:click="triggerConfirm('blockUser')" class="w-full text-left px-4 py-2 text-xs text-red-400 hover:bg-white/5 hover:text-red-300 transition-colors">
                                                        Bloker bruger
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-white/5"></div>

        @if($activeTab === 'profil')
        <!-- Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 animate-fade-in">
            
            <!-- Left Column: Stats -->
            <div class="md:col-span-4 space-y-6">
                @foreach($persons as $index => $person)
                    <div class="group bg-gradient-to-br from-[#121316]/60 to-[#121316]/40 p-6 rounded-2xl border border-white/[0.08] hover:border-white/[0.12] hover:shadow-lg hover:shadow-black/20 transition-all duration-500 ease-out space-y-5 backdrop-blur-sm">
                        <div class="flex items-center gap-3 mb-2">
                             <div class="w-10 h-10 bg-[#25262b] rounded-full flex items-center justify-center text-emerald-500 border border-white/5">
                                   <!-- Gender Icon -->
                                   @php
                                       $inferredGender = null;
                                       if ($user->gender?->slug === 'par') {
                                           $inferredGender = ($index === 0) ? 'mand' : 'kvinde';
                                       } else {
                                           $inferredGender = $user->gender?->slug;
                                       }
                                   @endphp
    
                                   @if($inferredGender === 'mand') 
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 0v9m-4-6l4-3 4 3" /></svg>
                                   @elseif($inferredGender === 'kvinde')
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a4 4 0 100 8 4 4 0 000-8zm0 8v8m-4-6l4 4 4-4" /></svg>
                                   @else
                                        ⚥
                                   @endif
                             </div>
                             <div>
                                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wide">
                                    {{ $person->name ?? (($user->gender?->slug === 'par') ? 'Person ' . ($index + 1) : $user->username) }}
                                </h3>
                                <p class="text-xs text-gray-500 font-medium">Personlige detaljer</p>
                             </div>
                        </div>
    
                        <div class="space-y-3">
                            <div class="flex justify-between items-center px-3 py-2 bg-[#1a1b1e]/80 rounded-lg border border-white/[0.08] group-hover:border-white/[0.12] transition-all duration-300 backdrop-blur-sm">
                                <span class="text-gray-500 text-xs uppercase font-bold tracking-wider">Alder</span>
                                @if($person->age)
                                    <span class="text-white font-medium text-sm">{{ $person->age }} år</span>
                                @else
                                    <span class="text-white/30 text-sm">Ikke angivet</span>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-center px-3 py-2 bg-[#1a1b1e]/80 rounded-lg border border-white/[0.08] group-hover:border-white/[0.12] transition-all duration-300 backdrop-blur-sm">
                                <span class="text-gray-500 text-xs uppercase font-bold tracking-wider">Højde</span>
                                @if($person->height)
                                    <span class="text-white font-medium text-sm">{{ $person->height }} cm</span>
                                @else
                                    <span class="text-white/30 text-sm">Ikke angivet</span>
                                @endif
                            </div>
    
                            <div class="flex justify-between items-center px-3 py-2 bg-[#1a1b1e]/80 rounded-lg border border-white/[0.08] group-hover:border-white/[0.12] transition-all duration-300 backdrop-blur-sm">
                                <span class="text-gray-500 text-xs uppercase font-bold tracking-wider">Vægt</span>
                                @if($person->weight)
                                    <span class="text-white font-medium text-sm">{{ $person->weight }} kg</span>
                                @else
                                    <span class="text-white/30 text-sm">Ikke angivet</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
 
                <!-- Events Section -->
            </div>
    
            <!-- Right Column: Description & Preferences -->
            <div class="md:col-span-8 space-y-8">
                <!-- Description -->
                <div class="space-y-3">
                    <div class="border-b border-white/5 pb-2">
                        <h3 class="text-lg font-bold text-white">Om {{ $user->username }}</h3>
                    </div>
                    <div class="prose prose-invert prose-sm max-w-none text-gray-300 leading-relaxed bg-[#121316]/30 p-6 rounded-2xl border border-white/5 break-words overflow-hidden">
                        @if($profile?->description)
                            {!! nl2br(e($profile->description)) !!}
                        @else
                            <p class="text-white/30 italic text-center py-4">Denne bruger har ikke skrevet en beskrivelse endnu.</p>
                        @endif
                    </div>
                </div>
    
                <!-- Preferences -->
                 @if($user->preferences->isNotEmpty())
                    <div class="space-y-3">
                        <div class="border-b border-white/5 pb-2">
                            <h3 class="text-lg font-bold text-white">Præferencer & Interesser</h3>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($user->preferences as $pref)
                                <div class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-[#121316] border border-white/5 text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.05)]">
                                    {{ $pref->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Events (Moved to right column) -->
                @if($events->count() > 0)
                    <div class="space-y-3">
                        <div class="border-b border-white/5 pb-2">
                            <h3 class="text-lg font-bold text-white">Tilmeldte events</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($events as $event)
                                <a href="{{ route('event.show', $event->slug) }}" wire:navigate class="group bg-[#121316] border border-white/5 rounded-xl p-4 hover:border-rose-500/30 transition-all flex items-center justify-between">
                                    <div class="space-y-1">
                                         <h4 class="text-white font-semibold text-sm group-hover:text-rose-400 transition-colors">{{ $event->title }}</h4>
                                         <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            {{ $event->start_time->format('d. M - H:i') }}
                                        </div>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-gray-400 group-hover:bg-rose-500/10 group-hover:text-rose-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
        </div>
        @endif

        <!-- Albums Tab -->
        @if($activeTab === 'albums')
        <div class="animate-fade-in space-y-6">
        @if(!$selectedAlbum)
            <!-- Albums Grid -->
            <div class="animate-fade-in space-y-6" id="public-albums-container">

                <!-- Loading Skeleton for Albums -->
                <div wire:loading.delay wire:target="$refresh,setActiveTab" class="hidden" wire:loading.class.remove="hidden">
                    <x-skeleton.album-grid :count="6" />
                </div>

                <!-- Actual Albums Content -->
                <div wire:loading.delay.class="hidden" wire:target="$refresh,setActiveTab">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($albums as $album)
                    @php
                        $isOwner = auth()->id() === $user->id;
                        $isLocked = $album->isLocked();
                        $unlockedAlbums = session('unlocked_albums', []);
                        // Check if album ID is key AND value matches current password hash
                        $isUnlocked = isset($unlockedAlbums[$album->id]) && $unlockedAlbums[$album->id] === $album->password;
                        $canNavigate = !$isLocked || $isOwner || $isUnlocked;
                    @endphp
                    
                    @if($canNavigate)
                        <a 
                            href="{{ route('member.profile.album', [$user->username, $album->slug]) }}"
                            wire:navigate
                            class="group relative bg-[#1a1b1e] rounded-xl overflow-hidden border border-white/5 hover:border-emerald-500/30 transition-all block"
                        >
                    @else
                        <div 
                            wire:click="selectAlbum({{ $album->id }})"
                            class="group relative bg-[#1a1b1e] rounded-xl overflow-hidden border border-white/5 hover:border-emerald-500/30 transition-all block cursor-pointer"
                        >
                    @endif
                        <!-- Cover Image -->
                        <div class="aspect-square bg-gradient-to-br from-gray-800 to-gray-900 relative overflow-hidden">
                            @if($album->isLocked())
                                @if(($isOwner || $isUnlocked) && $album->images->first())
                                    {{-- Show actual cover image for owner or unlocked guest --}}
                                    <img 
                                        src="{{ $album->images->first()->thumbnail_url }}" 
                                        class="w-full h-full object-cover {{ $isOwner ? 'blur-sm' : '' }} group-hover:scale-105 transition-transform duration-300"
                                        alt="{{ $album->name }}"
                                    >
                                    
                                    @if($isUnlocked && !$isOwner)
                                        {{-- Open Lock Indicator for Unlocked Guest --}}
                                        <div class="absolute inset-0 bg-black/20 backdrop-blur-[1px] flex items-center justify-center">
                                            <div class="text-center transform group-hover:scale-110 transition-transform duration-300">
                                                <div class="w-16 h-16 rounded-full bg-emerald-500/20 backdrop-blur-md flex items-center justify-center mx-auto mb-2 border border-emerald-500/30">
                                                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <p class="text-emerald-400 text-xs font-bold uppercase tracking-wider">Låst op</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($isOwner)
                                         <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                                            <div class="text-center">
                                                <svg class="w-8 h-8 text-white/50 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                @else
                                    {{-- Show placeholder for locked/no-access --}}
                                    @php
                                        $placeholder = match($user->gender?->slug) {
                                            'kvinde' => Vite::asset('resources/assets/user-female-nophoto.jpg'),
                                            'par' => Vite::asset('resources/assets/usercouple-nophoto.jpg'),
                                            default => Vite::asset('resources/assets/user-male-nophoto.jpg'),
                                        };
                                    @endphp
                                    <img 
                                        src="{{ $placeholder }}" 
                                        class="w-full h-full object-cover blur-[2px]"
                                        alt="{{ $album->name }}"
                                    >
                                    
                                    {{-- Closed Lock Indicator --}}
                                    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 text-yellow-500 mx-auto mb-2 drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <p class="text-white text-sm font-bold shadow-black drop-shadow-md">Låst album</p>
                                        </div>
                                    </div>
                                @endif
                            @elseif($album->images->first())
                                {{-- Show actual cover image for public albums --}}
                                <img 
                                    src="{{ $album->images->first()->thumbnail_url }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    alt="{{ $album->name }}"
                                >
                            @else
                                {{-- Empty album placeholder --}}
                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                    <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Album Info -->
                        <div class="p-4">
                            <h3 class="text-white font-semibold truncate">{{ $album->name }}</h3>
                            <p class="text-gray-400 text-sm mt-1">{{ $album->images_count }} billeder</p>
                        </div>
                    @if($canNavigate)
                        </a>
                    @else
                        </div>
                    @endif
                @endforeach
            </div>

                @if($albums->hasPages())
                    <div class="mt-8">
                        {{ $albums->links(data: ['scrollTo' => '#public-albums-container']) }}
                    </div>
                @endif
                </div> <!-- End Actual Albums Content wrapper -->
            </div>
        @else
            <!-- Selected Album View -->
            <div class="space-y-6" id="public-image-view-container" x-init="if(window.innerWidth < 768) $el.scrollIntoView({ behavior: 'smooth' })">
                <!-- Album Header -->
                <div class="flex items-center gap-4">
                    <a 
                        href="{{ route('member.profile.albums', $user->username) }}"
                        wire:navigate
                        class="p-2 hover:bg-white/5 rounded-lg transition-colors text-gray-400 hover:text-white"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                            {{ $selectedAlbum->name }}
                            @if($selectedAlbum->isLocked())
                                <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            @endif
                        </h2>
                        <p class="text-gray-400 text-sm mt-1">{{ $selectedAlbum->images->count() }} billeder</p>
                    </div>
                </div>

                <!-- Images Grid -->

                <!-- Loading Skeleton for Images -->
                <div wire:loading.delay wire:target="selectAlbum,unlockAlbum" class="hidden" wire:loading.class.remove="hidden">
                    <x-skeleton.image-grid :count="12" />
                </div>

                <!-- Actual Images Content -->
                <div wire:loading.delay.class="hidden" wire:target="selectAlbum,unlockAlbum">
                    @if($images->count() > 0)
                        @php
                            $lightboxImages = $allAlbumImages->map(fn($img) => [
                            'id' => $img->hash, // Use hash for frontend ID/URL
                            'url' => $img->url,
                            'albumName' => $selectedAlbum->name
                        ])->values()->toArray();
                    @endphp

                    <x-ui.lightbox :images="$lightboxImages" :initial-image-id="$selectedImageId">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($images as $image)
                                <div 
                                    @click="open({{ $loop->index }})"
                                    class="group relative aspect-square bg-[#121316] rounded-lg overflow-hidden border border-white/5 hover:border-emerald-500/30 transition-all cursor-pointer"
                                >
                                    <img 
                                        src="{{ $image->thumbnail_url }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        alt="Album billede"
                                    >
                                </div>
                            @endforeach
                        </div>
                    </x-ui.lightbox>

                    @if($images instanceof \Illuminate\Pagination\LengthAwarePaginator && $images->hasPages())
                        <div class="mt-8">
                            {{ $images->links(data: ['scrollTo' => '#public-image-view-container']) }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 bg-[#1a1b1e] rounded-xl border border-white/5">
                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-400">Dette album er tomt</p>
                    </div>
                @endif
                </div> <!-- End Actual Images Content wrapper -->
            </div>
        @endif
    @endif

        <!-- Friends Tab -->
        @if($activeTab === 'friends')
            <div class="space-y-6">

                <!-- Loading Skeleton for Friends -->
                <div wire:loading.delay wire:target="setActiveTab" class="hidden" wire:loading.class.remove="hidden">
                    <x-skeleton.user-list :count="6" />
                </div>

                <!-- Actual Friends Content -->
                <div wire:loading.delay.class="hidden" wire:target="setActiveTab">
                    @if(count($friends) > 0)
                        <h3 class="text-lg font-bold text-white">Venner ({{ $friends->total() }})</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($friends as $friend)
                            <x-user-list-item :user="$friend" />
                        @endforeach
                    </div>

                    @if($friends instanceof \Illuminate\Pagination\LengthAwarePaginator && $friends->hasPages())
                        <div class="mt-8">
                            {{ $friends->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <h3 class="text-white font-bold text-lg mb-2">Ingen venner</h3>
                        <p class="text-gray-400">Denne bruger har ikke tilføjet nogen venner endnu</p>
                    </div>
                @endif
                </div> <!-- End Actual Friends Content wrapper -->
            </div>
        @endif
    </div>

        </div> <!-- End Content Area -->
    </div> <!-- End Main Card Container -->


    <!-- Password Modal -->
    @if($showPasswordModal && $pendingAlbumId)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] flex items-center justify-center p-4" wire:click.self="closePasswordModal">
            <div class="bg-[#1a1b1e] rounded-2xl border border-yellow-500/20 p-6 max-w-md w-full shadow-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-yellow-500/10 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Låst album</h3>
                </div>
                <p class="text-gray-400 mb-4">Dette album er beskyttet med en adgangskode.</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Adgangskode</label>
                        <!-- Fake fields to trick browser -->
                        <input style="display:none" type="text" name="fakeusernameremembered"/>
                        <input style="display:none" type="password" name="fakepasswordremembered"/>
                        
                        <input 
                            type="password" 
                            wire:model="albumPassword"
                            wire:keydown.enter="unlockAlbum"
                            class="w-full px-4 py-2.5 bg-[#121316] border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-rose-500/50 transition-colors"
                            placeholder="Indtast adgangskode"
                            autofocus
                            autocomplete="off"
                            readonly
                            onfocus="this.removeAttribute('readonly');"
                            name="album_access_code_{{ Str::random(5) }}"
                        >
                        @if($passwordError)
                            <p class="text-red-400 text-sm mt-2">{{ $passwordError }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button 
                        wire:click="closePasswordModal"
                        class="flex-1 px-4 py-2.5 bg-white/5 text-gray-300 font-medium rounded-lg hover:bg-white/10 transition-colors"
                    >
                        Annuller
                    </button>
                    <button 
                        wire:click="unlockAlbum"
                        class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-semibold rounded-lg hover:bg-rose-700 transition-colors"
                    >
                        Lås op
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Confirmation Modal --}}
    @if($showConfirmationModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" wire:click="closeConfirmationModal"></div>
            
            <div class="relative bg-[#1a1b1e] border border-white/10 rounded-2xl w-full max-w-md p-6 shadow-2xl transform transition-all">
                <h3 class="text-xl font-bold text-white mb-2">{{ $confirmationTitle }}</h3>
                <p class="text-gray-400 mb-6">{{ $confirmationMessage }}</p>
                
                <div class="flex gap-3">
                    <button 
                        wire:click="closeConfirmationModal" 
                        class="flex-1 px-4 py-2.5 bg-[#25262b] text-gray-300 font-semibold rounded-lg hover:bg-[#2c2d32] border border-white/10 transition-colors"
                    >
                        Annuller
                    </button>
                    <button 
                        wire:click="proceedWithAction"
                        class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-semibold rounded-lg hover:bg-rose-700 transition-colors shadow-lg shadow-rose-500/20"
                    >
                        Bekræft
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Error Modal --}}
    @if($showErrorModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" wire:click="closeErrorModal"></div>
            
            <div class="relative bg-[#1a1b1e] border border-white/10 rounded-2xl w-full max-w-sm p-6 shadow-2xl transform transition-all text-center">
                <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h3 class="text-lg font-bold text-white mb-2">Fejl opstod</h3>
                <p class="text-gray-400 mb-6 text-sm">{{ $errorMessage }}</p>
                
                <button 
                    wire:click="closeErrorModal" 
                    class="w-full px-4 py-2.5 bg-[#25262b] text-white font-semibold rounded-lg hover:bg-[#2c2d32] border border-white/10 transition-colors"
                >
                    Luk
                </button>
            </div>
        </div>
    @endif

</div> <!-- End Main Card Container -->
</div> <!-- End Main Container -->
