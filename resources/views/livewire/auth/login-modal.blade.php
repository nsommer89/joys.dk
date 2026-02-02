<div 
    x-data="{ show: false, view: @entangle('view') }"
    x-show="show" 
    @open-login-modal.window="show = true; view = 'login'"
    @keydown.escape.window="show = false"
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
>
    {{-- Backdrop with soft Blur --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>
    
    {{-- Modal Content --}}
    <div class="flex h-full sm:min-h-full items-center justify-center p-0 sm:p-4">
        <div 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative glass-card-intense rounded-none sm:rounded-2xl p-6 sm:p-8 w-full h-full sm:h-auto max-w-none sm:max-w-md flex flex-col justify-center"
        >
            {{-- Close Button --}}
            <button 
                @click="show = false" 
                class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors"
                type="button"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <x-logo class="h-12 w-auto" />
            </div>

            {{-- Login View --}}
            <div x-show="view === 'login'">
                {{-- Header --}}
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-white">Log ind</h2>
                    <p class="mt-2 text-sm text-gray-400">Velkommen tilbage til Joys</p>
                </div>
                
                {{-- Error Message --}}
                @if($error)
                    <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/50">
                        <p class="text-sm text-red-400">{{ $error }}</p>
                    </div>
                @endif
                
                {{-- Login Form --}}
                <form wire:submit.prevent="login" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            wire:model="email" 
                            class="w-full px-4 py-3 rounded-lg surface-dp4 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all"
                            placeholder="din@email.dk"
                        >
                        @error('email') 
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p> 
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Adgangskode
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            wire:model="password" 
                            class="w-full px-4 py-3 rounded-lg surface-dp4 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all"
                            placeholder="••••••••"
                        >
                        @error('password') 
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p> 
                        @enderror
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="remember" class="rounded border-gray-700 bg-surface text-accent focus:ring-accent focus:ring-offset-surface">
                            <span class="ml-2 text-sm text-gray-400">Husk mig</span>
                        </label>
                        
                        <button type="button" wire:click="$set('view', 'forgot-password')" class="text-sm text-accent hover:text-accent-hover transition-colors">
                            Glemt adgangskode?
                        </button>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full bg-accent hover:bg-accent-hover text-white font-medium py-3 px-4 rounded-lg transition-colors ripple"
                    >
                        Log ind
                    </button>
                </form>
            </div>

            {{-- Forgot Password View --}}
            <div x-show="view === 'forgot-password'" x-cloak>
                {{-- Header --}}
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-white">Glemt adgangskode</h2>
                    <p class="mt-2 text-sm text-gray-400">Indtast din email for at nulstille din adgangskode</p>
                </div>

                 @if (session()->has('status'))
                    <div class="mb-4 p-4 rounded-lg bg-green-500/10 border border-green-500/50">
                        <p class="text-sm text-green-400">{{ session('status') }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="sendPasswordResetLink" class="space-y-4">
                    <div>
                        <label for="forgot-email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="forgot-email" 
                            wire:model="email" 
                            class="w-full px-4 py-3 rounded-lg surface-dp4 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all"
                            placeholder="din@email.dk"
                        >
                        @error('email') 
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p> 
                        @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-accent hover:bg-accent-hover text-white font-medium py-3 px-4 rounded-lg transition-colors ripple"
                    >
                        Send nulstillingslink
                    </button>

                    <div class="text-center mt-4">
                        <button type="button" wire:click="$set('view', 'login')" class="text-sm text-gray-400 hover:text-white transition-colors">
                            Tilbage til log ind
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
