<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="relative bg-[#1a1b1e] rounded-2xl overflow-hidden border border-white/5">
        <!-- Cover/Background (Optional, using gradient for now) -->
        <div class="h-32 bg-gradient-to-r from-emerald-900/40 to-black"></div>
        
        <div class="px-6 pb-6 pt-0 relative flex flex-col md:flex-row items-center md:items-end -mt-12 gap-6">
            <!-- Avatar -->
            <div class="relative">
                @if ($user->profile_photo_path)
                    <img src="{{ Storage::url($user->profile_photo_path) }}" class="w-32 h-32 rounded-full object-cover ring-4 ring-[#1a1b1e]">
                @else
                    <div class="w-32 h-32 rounded-full bg-[#121316] flex items-center justify-center text-gray-500 ring-4 ring-[#1a1b1e]">
                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                @endif
                
                @if(Cache::has('user-is-online-' . $user->id))
                     <div class="absolute bottom-2 right-2 w-5 h-5 bg-emerald-500 border-4 border-[#1a1b1e] rounded-full"></div>
                @endif
            </div>
            
            <!-- Basic Info -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-2xl font-bold text-white">{{ $user->username }}</h1>
                <div class="text-gray-400 text-sm mt-1 flex items-center justify-center md:justify-start gap-2">
                    @if($profile?->city)
                        <span>{{ $profile->city }}</span>
                    @endif
                    @if($user->gender)
                        <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                        <span>{{ $user->gender->name }}</span>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                 @if(auth()->id() === $user->id)
                    <a href="{{ route('member.profile.edit') }}" class="px-4 py-2 bg-[#25262b] text-white text-sm font-medium rounded-lg hover:bg-[#2c2d32] transition-colors border border-white/10">
                        Rediger Profil
                    </a>
                 @else
                    <!-- Friend/Message actions can be added here -->
                    <button class="px-4 py-2 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/20">
                        Send Besked
                    </button>
                 @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="md:col-span-2 space-y-8">
            <!-- Description -->
            <div class="bg-[#1a1b1e] p-6 rounded-2xl border border-white/5 space-y-4">
                <h3 class="text-lg font-bold text-white">Fritekst</h3>
                <div class="prose prose-invert prose-sm max-w-none text-gray-400">
                    {!! nl2br(e($profile?->description ?? 'Ingen beskrivelse endnu.')) !!}
                </div>
            </div>

            <!-- Preferences -->
             @if($user->preferences->isNotEmpty())
                <div class="bg-[#1a1b1e] p-6 rounded-2xl border border-white/5 space-y-4">
                    <h3 class="text-lg font-bold text-white">Præferencer</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->preferences as $pref)
                            <span class="px-3 py-1.5 rounded-full text-sm bg-[#25262b] border border-white/5 text-emerald-400">
                                {{ $pref->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar / Stats -->
        <div class="space-y-6">
            @foreach($persons as $index => $person)
                <div class="bg-[#1a1b1e] p-6 rounded-2xl border border-white/5 space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                         <div class="p-2 bg-[#25262b] rounded-lg text-emerald-500">
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
                                    ♂
                               @elseif($inferredGender === 'kvinde')
                                    ♀
                               @else
                                    ⚥
                               @endif
                         </div>
                         <h3 class="text-lg font-bold text-white">{{ $person->name ?? (($user->gender?->slug === 'par') ? 'Person ' . ($index + 1) : $user->username) }}</h3>
                    </div>

                    <div class="space-y-4">
                        @if($person->age)
                            <div class="flex justify-between items-center py-2 border-b border-white/5">
                                <span class="text-gray-500 text-sm">Alder</span>
                                <span class="text-white font-medium">{{ $person->age }} år</span>
                            </div>
                        @endif
                        
                        @if($person->height)
                            <div class="flex justify-between items-center py-2 border-b border-white/5">
                                <span class="text-gray-500 text-sm">Højde</span>
                                <span class="text-white font-medium">{{ $person->height }} cm</span>
                            </div>
                        @endif

                        @if($person->weight)
                            <div class="flex justify-between items-center py-2 border-b border-white/5">
                                <span class="text-gray-500 text-sm">Vægt</span>
                                <span class="text-white font-medium">{{ $person->weight }} kg</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
