<div class="max-w-4xl mx-auto py-12 px-4 md:px-0" 
     x-data="{ activeTab: 'profil', isDirty: false, isUploading: false }"
     x-on:input="isDirty = true"
     x-on:photo-processed.window="isDirty = true" 
     x-on:toast.window="if ($event.detail.type === 'success') isDirty = false">
    
    <!-- Title Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Min Profil</h1>
        <p class="text-gray-400">Administrer dine profiloplysninger og hvordan du fremstår på siden.</p>
    </div>

    <!-- Main Card Container -->
    <div class="bg-[#1a1b1e] rounded-2xl border border-white/5 overflow-hidden shadow-2xl">
        
        <!-- Tabs Navigation -->
        <div class="flex border-b border-white/10 bg-[#151619] px-6">
            <button 
                @click="activeTab = 'profil'"
                :class="activeTab === 'profil' ? 'border-rose-600 text-rose-500 bg-white/5' : 'border-transparent text-gray-400 hover:text-white hover:bg-white/5'"
                class="px-8 py-4 border-b-2 font-medium text-sm transition-all focus:outline-none cursor-pointer"
            >
                Profil
            </button>
            <button 
                @click="activeTab = 'galleri'"
                :class="activeTab === 'galleri' ? 'border-rose-600 text-rose-500 bg-white/5' : 'border-transparent text-gray-400 hover:text-white hover:bg-white/5'"
                class="px-8 py-4 border-b-2 font-medium text-sm transition-all focus:outline-none cursor-pointer"
            >
                Galleri
            </button>
        </div>

        <!-- Content Area -->
        <div class="p-8 md:p-10">
            <!-- Profil Tab -->
            <div x-show="activeTab === 'profil'" class="space-y-10 animate-fade-in relative">
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
                    class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-xl px-4"
                >
                    <div class="bg-[#1a1b1e]/90 backdrop-blur-xl border border-rose-500/20 rounded-2xl p-4 shadow-2xl flex items-center justify-between gap-6 ring-1 ring-white/5">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                            <p class="text-sm font-medium text-white">
                                <span class="hidden sm:inline">Du har ugemte ændringer</span>
                                <span class="sm:hidden">Husk at gemme</span>
                            </p>
                        </div>
                        <button 
                            @click="document.getElementById('submit-profile-button').click()"
                            :disabled="isUploading || @json($is_processing_photo)"
                            class="px-6 py-2 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-sm font-bold hover:bg-emerald-500/20 transition-all cursor-pointer whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span x-show="!isUploading && !@json($is_processing_photo)">Gem nu</span>
                            <span x-show="isUploading || @json($is_processing_photo)" x-cloak>Venter...</span>
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="save" class="space-y-10">
                    
                    <!-- Section: Avatar & General -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12">
                         <!-- Avatar Column -->
                         <div class="md:col-span-4 flex flex-col items-center md:items-start space-y-4"
                              @if($is_processing_photo) wire:poll.1000ms="pollPhotoStatus" @endif>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 text-center md:text-left w-full">Profilbillede</label>
                            

                            <div class="relative group">
                                <div class="w-40 h-40 rounded-full p-1 border-2 border-dashed border-white/20 group-hover:border-rose-500/50 transition-colors">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-[#121316] relative ring-4 ring-[#1a1b1e]">
                                        {{-- Profile Image Display --}}
                                        <div class="w-full h-full bg-[#121316] relative transition-opacity duration-300 {{ $is_processing_photo ? 'opacity-10' : '' }}" 
                                             wire:loading.class="opacity-10" wire:target="profile_photo">
                                            @if ($processed_photo_path)
                                                <img src="{{ Storage::url($processed_photo_path) }}" class="w-full h-full object-cover">
                                            @elseif (auth()->user()->profile_photo_path)
                                                <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                                    <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
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
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            
                            {{-- Text: Changes based on loading state --}}
                            <span wire:loading.remove wire:target="save, profile_photo">Gem profil</span>
                            <span wire:loading wire:target="save, profile_photo" class="hidden">Gemmer...</span>
                        </button>
                    </div>
                </form>
            </div>
    
<!-- Gallery Tab Content -->
<div x-show="activeTab === 'galleri'" x-cloak class="anim ate-fade-in">
    <div class="grid grid-cols-12 gap-6 min-h-[600px]">
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
