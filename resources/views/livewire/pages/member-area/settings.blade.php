<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <x-ui.member-page-header title="Indstillinger" description="Administrer dine personlige indstillinger" />
        
        <div class="surface-dp4 rounded-xl p-8">
            {{-- Account Settings --}}
            <div class="mb-8">
                <h2 class="text-xl font-bold text-white mb-4">Konto</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Kontakt support for at ændre din email</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Brugernavn</label>
                        <input type="text" value="{{ auth()->user()->username }}" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Kontakt support for at ændre dit brugernavn</p>
                    </div>
                </div>
            </div>

            {{-- Privacy Settings --}}
            <div class="mb-8 pt-8 border-t border-white/10">
                <h2 class="text-xl font-bold text-white mb-4">Privatliv</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white font-medium">Vis profil for andre medlemmer</p>
                            <p class="text-sm text-gray-500">Tillad andre at se din basisprofil</p>
                        </div>
                        <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-accent transition-colors">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-6"></span>
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white font-medium">Vis online status</p>
                            <p class="text-sm text-gray-500">Lad andre se når du er aktiv</p>
                        </div>
                        <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-700 transition-colors">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-1"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Notification Settings --}}
            <div class="pt-8 border-t border-white/10">
                <h2 class="text-xl font-bold text-white mb-4">Notifikationer</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white font-medium">Email notifikationer</p>
                            <p class="text-sm text-gray-500">Modtag emails om nye beskeder og events</p>
                        </div>
                        <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-accent transition-colors">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-6"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="mt-8 pt-8 border-t border-white/10 flex justify-end gap-3">
                <button class="px-6 py-2.5 rounded-lg border border-white/10 text-white hover:bg-white/5 transition-all">
                    Annuller
                </button>
                <button class="px-6 py-2.5 rounded-lg bg-accent hover:bg-accent-hover text-white font-medium transition-all">
                    Gem ændringer
                </button>
            </div>
        </div>
    </div>
</div>
