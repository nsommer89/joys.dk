<div class="max-w-4xl mx-auto py-12 px-4 md:px-0" 
     x-data="{ 
        isDirty: false, 
        isUploading: false,
        isProcessing: @json($is_processing_photo) 
     }"
     x-on:input="isDirty = true"
     x-on:processing-started.window="isProcessing = true; isUploading = false"
     x-on:processing-finished.window="isProcessing = false"
     x-on:livewire-upload-start="isUploading = true"
     x-on:livewire-upload-finish="setTimeout(() => isUploading = false, 1000)"
     x-on:livewire-upload-error="isUploading = false"
     x-on:photo-processed.window="isDirty = true" 
     x-on:toast.window="if ($event.detail.type === 'success') isDirty = false">
    
    <!-- Title Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Rediger profil</h1>
        <p class="text-gray-400">Administrer dine profiloplysninger og hvordan du fremstår på siden.</p>
    </div>

    <!-- Main Card Container -->
    <div class="bg-gradient-to-b from-[#1a1b1e]/95 via-[#1a1b1e]/98 to-[#1a1b1e] rounded-2xl border border-white/[0.08] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.4),0_2px_8px_rgba(0,0,0,0.2),inset_0_1px_0_rgba(255,255,255,0.05)] backdrop-blur-xl hover:shadow-[0_12px_48px_rgba(0,0,0,0.5),0_4px_16px_rgba(0,0,0,0.3)] hover:border-white/[0.12] transition-all duration-500">

        <!-- Tabs Navigation -->
        <div class="flex border-b border-white/[0.08] bg-gradient-to-b from-white/[0.03] to-transparent backdrop-blur-sm px-6">
            <a
                href="{{ route('member.profile.edit') }}"
                wire:navigate
                @class([
                    'relative px-8 py-4 border-b-2 font-semibold text-sm transition-all duration-500 ease-out group',
                    'border-rose-600 text-rose-500' => $activeTab === 'profil',
                    'border-transparent text-gray-400 hover:text-white' => $activeTab !== 'profil'
                ])
            >
                @if($activeTab === 'profil')
                    <div class="absolute inset-0 bg-gradient-to-t from-rose-500/5 to-transparent"></div>
                @endif
                <span class="relative">Rediger profil</span>
            </a>
            <a
                href="{{ route('member.profile.edit.albums') }}"
                wire:navigate
                @class([
                    'relative px-8 py-4 border-b-2 font-semibold text-sm transition-all duration-500 ease-out group',
                    'border-rose-600 text-rose-500' => $activeTab === 'albums',
                    'border-transparent text-gray-400 hover:text-white' => $activeTab !== 'albums'
                ])
            >
                @if($activeTab === 'albums')
                    <div class="absolute inset-0 bg-gradient-to-t from-rose-500/5 to-transparent"></div>
                @endif
                <span class="relative">Mine albums</span>
            </a>
        </div>

        <!-- Content Area -->
        <div class="p-8 md:p-10 min-h-[600px] bg-gradient-to-b from-transparent to-black/10">
            <!-- Profil Tab -->
            @if($activeTab === 'profil')
            <div class="space-y-10 animate-fade-in relative">
                <!-- Floating Save Bar (Stable during background tasks) -->
                <div 
                    x-cloak
                    x-show="isDirty"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="translate-y-20 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-20 opacity-0"
                    class="fixed bottom-4 md:bottom-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-xl px-5 md:px-0"
                >
                    <div class="bg-gradient-to-r from-[#1a1b1e]/95 to-[#1a1b1e]/90 backdrop-blur-2xl border border-rose-500/30 rounded-xl md:rounded-2xl p-3 md:p-4 shadow-[0_12px_48px_rgba(225,29,72,0.15)] flex items-center justify-between gap-4 md:gap-6 ring-1 ring-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                            <p class="text-sm font-medium text-white">
                                <span class="hidden sm:inline">Du har ugemte ændringer</span>
                                <span class="sm:hidden">Husk at gemme</span>
                            </p>
                        </div>
                        <button 
                            @click="document.getElementById('submit-profile-button').click()"
                            wire:loading.attr="disabled"
                            wire:target="save, profile_photo, zip_code, confirmDelete"
                            :disabled="isUploading || isProcessing"
                            class="px-6 py-2 bg-gradient-to-r from-emerald-500/10 to-emerald-600/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-sm font-bold hover:from-emerald-500/20 hover:to-emerald-600/20 hover:border-emerald-500/40 hover:shadow-lg hover:shadow-emerald-500/20 transition-all duration-300 cursor-pointer whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed min-w-[100px] hover:scale-105 active:scale-95"
                        >
                            {{-- State: Content determined by Javascript (Idle / Processing) --}}
                            <span wire:loading.remove wire:target="save, profile_photo, zip_code, confirmDelete">
                                <span x-text="(isUploading || isProcessing) ? 'Venter...' : 'Gem nu'"></span>
                            </span>

                            {{-- State: Active User Action Request (Livewire) --}}
                            <span wire:loading wire:target="save, profile_photo, zip_code, confirmDelete" x-cloak>Venter...</span>
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="save" class="space-y-10" wire:poll.2000ms="pollPhotoStatus">
                    
                    <!-- Section: Avatar & General -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12">
                         <!-- Avatar Column -->
                         <div class="md:col-span-4 flex flex-col items-center md:items-start space-y-4">
                            <div class="border-b border-white/5 pb-4 mb-4 md:w-full">
                                <h3 class="text-lg font-medium text-white text-center md:text-left">Profilbillede</h3>
                                <p class="text-sm text-gray-500 text-center md:text-left">Dit ansigt udadtil.</p>
                            </div>
                            

                            <div class="relative group">
                                <div class="w-40 h-40 rounded-full p-1 border-2 border-dashed border-white/20 group-hover:border-rose-500/50 transition-colors">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-[#121316] relative ring-4 ring-[#1a1b1e]">
                                        {{-- Profile Image Display --}}
                                        <div class="w-full h-full bg-[#121316] relative transition-opacity duration-300 {{ $is_processing_photo ? 'opacity-10' : '' }}" 
                                             wire:loading.class="opacity-10" wire:target="profile_photo">
                                            @if ($processed_photo_path)
                                                {{-- Shows the processed image waiting to be saved --}}
                                                <img src="{{ Storage::url($processed_photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                {{-- Shows current profile photo (or gender-specific placeholder) --}}
                                                <img src="{{ auth()->user()->profile_photo_url }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        
                                        <!-- Overlay / Picker -->
                                        @if(!$is_processing_photo)
                                            <label class="absolute inset-0 flex flex-col items-center justify-center bg-black/60 opacity-0 group-hover:opacity-100 transition-all cursor-pointer z-10" 
                                                wire:loading.class="!hidden" wire:target="profile_photo">
                                                <svg class="w-8 h-8 text-white mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                <span class="text-xs font-medium text-white">Skift billede</span>
                                                <input type="file" wire:model="profile_photo" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp"
                                                    x-on:livewire-upload-start="isUploading = true"
                                                    x-on:livewire-upload-finish="isUploading = false"
                                                    x-on:livewire-upload-error="isUploading = false">
                                            </label>

                                        @endif

                                        <!-- Unified Loading & Processing Overlay -->
                                        <div wire:key="profile-photo-loader"
                                             class="absolute inset-0 z-20 flex-col items-center justify-center bg-black/70 rounded-full text-rose-500 {{ $is_processing_photo ? 'flex' : 'hidden' }}"
                                             wire:loading.class.remove="hidden"
                                             wire:loading.class="flex"
                                             wire:target="profile_photo">
                                            <div class="flex flex-col items-center gap-3">
                                                <svg class="animate-spin h-10 w-10 text-rose-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-[10px] font-bold uppercase tracking-widest">
                                                    @if($is_processing_photo)
                                                        Behandler...
                                                    @else
                                                        Uploader...
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->profile_photo_path)
                                <button 
                                    type="button"
                                    wire:click="requestDeleteProfilePhoto" 
                                    class="group flex items-center gap-2 px-4 py-2 mt-4 bg-red-500/5 hover:bg-red-500/10 border border-red-500/20 rounded-lg text-xs font-medium text-red-500 hover:text-red-400 transition-all focus:outline-none"
                                >
                                    <svg class="w-4 h-4 opacity-70 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Fjern profilbillede
                                </button>
                            @endif
                        </div>
                        
                         <!-- Location & Info Column -->
                         <div class="md:col-span-8 space-y-6">
                            <div class="border-b border-white/5 pb-4 mb-4">
                                <h3 class="text-lg font-medium text-white">Generelt</h3>
                                <p class="text-sm text-gray-500">Grundlæggende oplysninger om din profil.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Brugernavn</label>
                                    <div class="relative">
                                        <input type="text" value="{{ $username }}" disabled class="w-full bg-[#151619] border border-white/5 rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed">
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-gray-600 font-bold uppercase tracking-widest">Kan ikke ændres</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Postnummer</label>
                                    <input type="text" 
                                        wire:model.live.debounce.500ms="zip_code" 
                                        inputmode="numeric" 
                                        pattern="[0-9]*" 
                                        maxlength="4" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)"
                                        class="w-full bg-[#121316] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:border-white/20 focus:outline-none transition-colors"
                                        placeholder="f.eks. 8600">
                                    @error('zip_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">By</label>
                                    <input type="text" wire:model="city" class="w-full bg-[#121316] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:border-white/20 focus:outline-none transition-colors">
                                    @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                         </div>
                    </div>

                    <div class="border-t border-white/5"></div>

                    <!-- Dynamic Person Fields -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-white">Personlige Detaljer</h3>
                                <p class="text-sm text-gray-500">Fortæl lidt om {{ $is_couple ? 'jer' : 'dig' }} selv.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 {{ $is_couple ? 'lg:grid-cols-2' : '' }} gap-8">
                            @foreach($persons as $index => $person)
                                <div class="bg-[#121316]/50 p-6 rounded-2xl border border-white/5 hover:border-white/10 transition-colors space-y-5">
                                    <div class="flex items-center mb-2">
                                        <span class="text-sm font-bold text-gray-300 uppercase tracking-wide">
                                            @if($is_couple) 
                                                {{ $index === 0 ? 'Mand' : 'Kvinde' }} 
                                            @else 
                                                Dine oplysninger
                                            @endif
                                        </span>
                                    </div>
        
                                    <!-- Name -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Navn / Kaldenavn</label>
                                        <input type="text" wire:model="persons.{{ $index }}.name" class="w-full bg-[#1a1b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-white/20 focus:outline-none transition-colors">
                                        @error("persons.{$index}.name") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
        
                                    <!-- Age -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Alder</label>
                                        <input type="number" wire:model="persons.{{ $index }}.age" class="w-full bg-[#1a1b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-white/20 focus:outline-none transition-colors">
                                        @error("persons.{$index}.age") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
        
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Height -->
                                        <div class="space-y-2">
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Højde (cm)</label>
                                            <input type="number" wire:model="persons.{{ $index }}.height" class="w-full bg-[#1a1b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-white/20 focus:outline-none transition-colors">
                                             @error("persons.{$index}.height") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <!-- Weight -->
                                        <div class="space-y-2">
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Vægt (kg)</label>
                                            <input type="number" wire:model="persons.{{ $index }}.weight" class="w-full bg-[#1a1b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-white/20 focus:outline-none transition-colors">
                                            @error("persons.{$index}.weight") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-white/5"></div>
    
                    <!-- Description -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white">Fritekst</h3>
                            <p class="text-sm text-gray-500">Skriv en indbydende tekst som andre medlemmer kan læse.</p>
                        </div>
                        <div class="relative">
                            <textarea wire:model="description" rows="6" class="w-full bg-[#121316] border border-white/10 rounded-xl px-6 py-4 text-white focus:border-white/20 focus:outline-none transition-colors placeholder-gray-600 resize-none leading-relaxed" placeholder="Fortæl lidt om hvad du/I søger, jeres interesser osv..."></textarea>
                        </div>
                         @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="border-t border-white/5"></div>

                    <!-- Preferences (Tags) -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-white">Præferencer</h3>
                            <p class="text-sm text-gray-500">Vælg de tags der passer bedst på dig/jer.</p>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($allPreferences as $pref)
                                <label class="cursor-pointer group">
                                    <input type="checkbox" wire:model="selected_preferences" value="{{ $pref->id }}" class="sr-only peer">
                                    <div class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300
                                        bg-[#121316] border border-white/5 text-gray-500 
                                        peer-checked:bg-emerald-500/[0.08] peer-checked:border-emerald-500/50 peer-checked:text-emerald-400 peer-checked:shadow-[0_0_20px_rgba(16,185,129,0.08)]
                                        hover:border-white/20 hover:text-gray-300 peer-checked:hover:border-emerald-500/70 peer-checked:hover:text-emerald-300
                                        select-none flex items-center justify-center min-w-[100px] text-center">
                                        {{ $pref->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                         @if($allPreferences->isEmpty())
                            <p class="text-sm text-gray-600 italic">Ingen præferencer oprettet endnu.</p>
                         @endif
                    </div>
    
                    <!-- Submit -->
                    <div class="pt-8 border-t border-white/5 flex items-center justify-end">
                        <button type="submit" 
                            id="submit-profile-button"
                            wire:loading.attr="disabled" 
                            wire:target="save, profile_photo"
                            @if($is_processing_photo) disabled @endif
                            class="w-[200px] h-[52px] border border-emerald-500/30 text-emerald-400 font-bold text-[14px] rounded-xl hover:bg-emerald-500/10 hover:border-emerald-500/60 hover:text-emerald-300 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-3 group cursor-pointer">
                            
                            {{-- Icon: Checkmark when idle, Spinner when loading --}}
                            <svg wire:loading.remove wire:target="save, profile_photo" class="h-5 w-5 text-emerald-400 group-hover:text-emerald-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            
                            <svg wire:loading wire:target="save, profile_photo" class="animate-spin h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            
                            {{-- Text: Changes based on loading state --}}
                            <span wire:loading.remove wire:target="save, profile_photo">Gem profil</span>
                            <span wire:loading wire:target="save, profile_photo" class="hidden">Gemmer...</span>
                        </button>
                    </div>
                </form>
            </div>
    @endif

    <!-- Gallery Tab Content -->
    @if($activeTab === 'albums')
    <div class="animate-fade-in">
    @if(!$selectedAlbum)
        <!-- Albums Grid View -->
        <div class="space-y-6" id="albums-container">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">Mine albums</h2>
                    <p class="text-gray-400 text-sm mt-1">Opret og administrer dine billedalbums (maks. 25)</p>
                </div>
                <button 
                    wire:click="$set('showCreateAlbumModal', true)"
                    class="px-4 py-2.5 bg-emerald-500 text-white text-sm font-semibold rounded-lg hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/20 flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nyt album
                </button>
            </div>

            <!-- Loading Skeleton for Albums -->
            <div wire:loading.delay wire:target="$refresh" class="hidden" wire:loading.class.remove="hidden">
                <x-skeleton.album-grid :count="6" />
            </div>

            <!-- Actual Content -->
            <div wire:loading.delay.class="hidden" wire:target="$refresh">
                @if(count($albums) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($albums as $album)
                            <a
                                href="{{ route('member.profile.edit.album', $album->slug) }}"
                                wire:navigate
                                class="group relative bg-[#121316] rounded-xl overflow-hidden border border-white/5 hover:border-rose-500/30 transition-all block"
                            >
                            <!-- Cover Image -->
                            <div class="aspect-square bg-gradient-to-br from-gray-800 to-gray-900 relative overflow-hidden">
                                @if($album->images->first())
                                    <img 
                                        src="{{ $album->images->first()->thumbnail_url }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        alt="{{ $album->name }}"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Lock Indicator -->
                                @if($album->isLocked())
                                    <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm rounded-lg px-2 py-1">
                                        <svg class="w-4 h-4 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Album Info -->
                            <div class="p-4">
                                <h3 class="text-white font-semibold truncate">{{ $album->name }}</h3>
                                <p class="text-gray-400 text-sm mt-1">{{ $album->images_count }} billeder</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if($albums->hasPages())
                    <div class="mt-8">
                        {{ $albums->links(data: ['scrollTo' => '#albums-container']) }}
                    </div>
                @endif
            @else
                <div class="text-center py-20">
                    <svg class="w-20 h-20 text-gray-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-400 mb-2">Ingen albums endnu</h3>
                    <p class="text-gray-500 mb-6">Opret dit første album for at komme i gang</p>
                    <button 
                        wire:click="$set('showCreateAlbumModal', true)"
                        class="px-6 py-3 bg-emerald-500 text-white font-semibold rounded-lg hover:bg-emerald-600 transition-colors inline-flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Opret album
                    </button>
                </div>
            @endif
            </div> <!-- End Actual Content wrapper -->
        </div>
    @else
        <!-- Selected Album View -->
        <div class="space-y-6" id="album-view-container" x-init="if(window.innerWidth < 768) $el.scrollIntoView({ behavior: 'smooth' })">
            <!-- Album Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a 
                        href="{{ route('member.profile.edit.albums') }}"
                        wire:navigate
                        class="p-2 hover:bg-white/5 rounded-lg transition-colors text-gray-400 hover:text-white"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-3">
                            @if($editingAlbumId === $selectedAlbum->id)
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="text" 
                                        wire:model="editingAlbumName"
                                        class="bg-[#121316] border border-white/10 rounded-lg px-3 py-1 text-white text-lg focus:outline-none focus:border-rose-500"
                                        wire:keydown.enter="updateAlbumName"
                                        wire:keydown.escape="cancelEditingAlbum"
                                        autofocus
                                    >
                                    <button wire:click="updateAlbumName" class="text-emerald-500 hover:text-emerald-400">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    <button wire:click="cancelEditingAlbum" class="text-rose-500 hover:text-rose-400">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <h2 class="text-2xl font-bold text-white flex items-center gap-2 group cursor-pointer" wire:click="startEditingAlbum({{ $selectedAlbum->id }})">
                                    {{ $selectedAlbum->name }}
                                    <svg class="w-4 h-4 text-gray-500 group-hover:text-white transition-colors opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    @if($selectedAlbum->isLocked())
                                        <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    @endif
                                </h2>
                            @endif
                        </div>
                        <p class="text-gray-400 text-sm mt-1">{{ $selectedAlbum->images()->count() }} / 50 billeder</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button 
                        wire:click="openEditPasswordModal"
                        class="px-4 py-2 bg-white/5 text-gray-300 text-sm font-medium rounded-lg hover:bg-white/10 transition-colors border border-white/10 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Rediger kode
                    </button>
                    <button 
                        wire:click="confirmDeleteAlbum({{ $selectedAlbum->id }})"
                        class="px-4 py-2 bg-red-500/10 text-red-400 text-sm font-medium rounded-lg hover:bg-red-500/20 transition-colors border border-red-500/20"
                    >
                        Slet album
                    </button>
                </div>
            </div>

            <!-- Upload Section -->
            <div class="bg-[#121316] rounded-xl border border-white/5 p-6">
                <h3 class="text-white font-semibold mb-4 text-center md:text-left">Upload billeder</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-white/10 rounded-xl cursor-pointer hover:border-rose-500/50 hover:bg-rose-500/5 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-rose-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mb-1 text-sm text-gray-400 group-hover:text-white transition-colors">
                                    <span class="font-bold">Tryk for at vælge billeder</span>
                                </p>
                                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold opacity-40">Maks. 12MB per billede</p>
                            </div>
                            <input 
                                type="file" 
                                wire:model="uploadedImages" 
                                multiple 
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="hidden"
                            >
                        </label>
                    </div>

                    @if(count($uploadedImages) > 0)
                        <div class="flex items-center justify-between p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-emerald-400 font-medium">{{ count($uploadedImages) }} billede(r) valgt</span>
                            </div>
                            <button wire:click="$set('uploadedImages', [])" class="text-xs text-gray-500 hover:text-white transition-colors">Ryd</button>
                        </div>
                        
                        <button 
                            wire:click="uploadImages({{ $selectedAlbum->id }})"
                            class="w-full px-4 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20"
                        >
                            Start upload
                        </button>
                    @endif
                </div>
            </div>

            <!-- Images Grid -->
            @php
                $hasProcessingImages = $images->where('is_processing', true)->count() > 0;
            @endphp

            <!-- Loading Skeleton for Images -->
            <div wire:loading.delay wire:target="selectAlbum,refreshAlbumImages" class="hidden" wire:loading.class.remove="hidden">
                <x-skeleton.image-grid :count="12" />
            </div>

            <!-- Actual Images Content -->
            <div wire:loading.delay.class="hidden" wire:target="selectAlbum,refreshAlbumImages">
                @if($images->count() > 0)
                    @php
                        $lightboxImages = $allAlbumImages->map(fn($img) => [
                            'id' => $img->hash,
                            'url' => $img->url,
                            'albumName' => $selectedAlbum->name
                        ])->values()->toArray();
                    @endphp

                    <x-ui.lightbox 
                        :images="$lightboxImages" 
                        :initial-image-id="$selectedImageId"
                        wire:key="lightbox-{{ count($lightboxImages) . '-' . ($lightboxImages[count($lightboxImages)-1]['id'] ?? 'empty') }}"
                    >
                        <div 
                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                            @if($hasProcessingImages) wire:poll.2s="refreshAlbumImages" @endif
                        >
                            @foreach($images as $image)
                                <div class="group relative aspect-square bg-[#121316] rounded-lg overflow-hidden border border-white/5">
                                    @if($image->is_processing)
                                        <!-- Processing State -->
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8 animate-spin mb-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-xs">Behandler...</span>
                                        </div>
                                    @else
                                        @php
                                           $lIndex = $allAlbumImages->search(fn($i) => $i->id === $image->id);
                                        @endphp
                                        <!-- Image -->
                                        <div class="w-full h-full cursor-pointer" @click="open({{ $lIndex }})">
                                            <img 
                                                src="{{ $image->thumbnail_url }}" 
                                                class="w-full h-full object-cover"
                                                alt="Album billede"
                                            >
                                        </div>
                                        <!-- Delete Button -->
                                        <button 
                                            wire:click="requestDeleteImage({{ $image->id }})"
                                            @click.stop
                                            class="absolute top-2 right-2 p-1.5 bg-red-500/90 hover:bg-red-600 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity z-10"
                                        >
                                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </x-ui.lightbox>

                    @if($images instanceof \Illuminate\Pagination\LengthAwarePaginator && $images->hasPages())
                        <div class="mt-8">
                            {{ $images->links(data: ['scrollTo' => '#album-view-container']) }}
                        </div>
                    @endif
                @else
                <div class="text-center py-12 bg-[#121316] rounded-xl border border-white/5">
                    <svg class="w-16 h-16 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-400">Ingen billeder i dette album endnu</p>
                </div>
            @endif
            </div> <!-- End Actual Images Content wrapper -->
        </div>
    @endif
    @endif
    
        </div> <!-- End Content Area -->
    </div> <!-- End Main Card Container -->

    <!-- Create Album Modal -->
    @if($showCreateAlbumModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] flex items-center justify-center p-4" wire:click.self="$set('showCreateAlbumModal', false)">
            <div class="bg-[#1a1b1e] rounded-2xl border border-white/10 p-6 max-w-md w-full shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-4">Opret nyt album</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Album navn</label>
                        <input 
                            type="text" 
                            wire:model="newAlbumName"
                            class="w-full px-4 py-2.5 bg-[#121316] border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500/50 transition-colors"
                            placeholder="F.eks. Sommer 2024"
                        >
                        @error('newAlbumName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Adgangskode (valgfri)</label>
                        <!-- Fake fields -->
                        <input style="display:none" type="text" name="fakeusernamenew"/>
                        <input style="display:none" type="password" name="fakepasswordnew"/>

                        <input 
                            type="password" 
                            wire:model="newAlbumPassword"
                            class="w-full px-4 py-2.5 bg-[#121316] border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500/50 transition-colors"
                            placeholder="Lad stå tom for tilgængeligt for alle medlemmer"
                            autocomplete="off"
                            readonly
                            onfocus="this.removeAttribute('readonly');"
                            name="new_album_pw_{{ Str::random(5) }}"
                        >
                        @error('newAlbumPassword') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Beskyt albummet med en adgangskode</p>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button 
                        wire:click="$set('showCreateAlbumModal', false)"
                        class="flex-1 px-4 py-2.5 bg-white/5 text-gray-300 font-medium rounded-lg hover:bg-white/10 transition-colors"
                    >
                        Annuller
                    </button>
                    <button 
                        wire:click="createAlbum"
                        class="flex-1 px-4 py-2.5 bg-emerald-500 text-white font-semibold rounded-lg hover:bg-emerald-600 transition-colors"
                    >
                        Opret
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteAlbumModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] flex items-center justify-center p-4" wire:click.self="$set('showDeleteAlbumModal', false)">
            <div class="bg-[#1a1b1e] rounded-2xl border border-red-500/20 p-6 max-w-md w-full shadow-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-red-500/10 rounded-lg">
                        <svg class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Slet album?</h3>
                </div>
                <p class="text-gray-400 mb-6">Er du sikker på at du vil slette dette album? Alle billeder vil blive permanent slettet. Denne handling kan ikke fortrydes.</p>
                <div class="flex gap-3">
                    <button 
                        wire:click="$set('showDeleteAlbumModal', false)"
                        class="flex-1 px-4 py-2.5 bg-white/5 text-gray-300 font-medium rounded-lg hover:bg-white/10 transition-colors"
                    >
                        Annuller
                    </button>
                    <button 
                        wire:click="deleteAlbum"
                        class="flex-1 px-4 py-2.5 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-colors"
                    >
                        Slet album
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Password Modal -->
    @if($showEditPasswordModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] flex items-center justify-center p-4" wire:click.self="$set('showEditPasswordModal', false)">
            <div class="bg-[#1a1b1e] rounded-2xl border border-white/10 p-6 max-w-md w-full shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-4">Rediger adgangskode</h3>
                
                <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-3 mb-4">
                    <p class="text-xs text-yellow-200">
                        <span class="font-bold">OBS:</span> Skifter du koden, mister alle tidligere gæster adgangen, indtil de indtaster den nye kode.
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Ny adgangskode</label>
                        <!-- Fake fields -->
                        <input style="display:none" type="text" name="fakeusernameedit"/>
                        <input style="display:none" type="password" name="fakepasswordedit"/>

                        <input 
                            type="password" 
                            wire:model="editAlbumPassword"
                            class="w-full px-4 py-2.5 bg-[#121316] border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500/50 transition-colors"
                            placeholder="Lad stå tom for at fjerne koden"
                            autocomplete="off"
                            readonly
                            onfocus="this.removeAttribute('readonly');"
                            name="edit_album_pw_{{ Str::random(5) }}"
                        >
                        @error('editAlbumPassword') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Hvis du efterlader feltet tomt, bliver albummet tilgængeligt for alle medlemmer. Trykker du annuller vil alting været uændret.</p>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button 
                        wire:click="$set('showEditPasswordModal', false)"
                        class="flex-1 px-4 py-2.5 bg-white/5 text-gray-300 font-medium rounded-lg hover:bg-white/10 transition-colors"
                    >
                        Annuller
                    </button>
                    <button 
                        wire:click="updateAlbumPassword"
                        class="flex-1 px-4 py-2.5 bg-emerald-500 text-white font-semibold rounded-lg hover:bg-emerald-600 transition-colors"
                    >
                        Gem ændringer
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- General Delete Confirmation Modal -->
    @if($showDeleteConfirmation)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] flex items-center justify-center p-4" wire:click.self="$set('showDeleteConfirmation', false)">
             <div class="bg-[#1a1b1e] rounded-2xl border border-white/10 p-6 max-w-sm w-full shadow-2xl">
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Er du sikker?</h3>
                    <p class="text-gray-400 text-sm mb-6">
                        Er du sikker på, at du vil slette dette {{ $deleteConfirmationAction === 'profile_photo' ? 'profilbillede' : 'billede' }}? Handlingen kan ikke fortrydes.
                    </p>
                    
                    <div class="flex gap-3">
                        <button wire:click="$set('showDeleteConfirmation', false)" class="flex-1 px-4 py-2.5 bg-white/5 text-gray-300 font-medium rounded-lg hover:bg-white/10 transition-colors">
                            Annuller
                        </button>
                        <button wire:click="confirmDelete" class="flex-1 px-4 py-2.5 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                            Slet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
</div>
</div>

@script
<script>
    $wire.on('validation-failed', () => {
        setTimeout(() => {
            const firstError = document.querySelector('.text-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 100);
    });
</script>
@endscript

</div> <!-- End Main Component Container -->
