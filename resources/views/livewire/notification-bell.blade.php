<div class="relative" x-data="{ open: false }">
    <button 
        @click="open = !open" 
        @click.away="open = false" 
        class="relative p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-white/10"
    >
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        @if($this->unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-[#1a1b1e]"></span>
        @endif
    </button>

    <div 
        x-show="open" 
        style="display: none;"
        class="absolute right-0 mt-2 w-80 bg-[#1a1b1e] border border-white/10 rounded-xl shadow-xl z-50 overflow-hidden"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
    >
        <div class="p-3 border-b border-white/10 flex justify-between items-center">
            <h3 class="text-sm font-bold text-white">Notifikationer</h3>
            @if($this->unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-rose-400 hover:text-rose-300 transition-colors">
                    Marker alt som læst
                </button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($this->notifications as $notification)
                <div class="p-4 border-b border-white/5 hover:bg-white/5 transition-colors {{ $notification->read_at ? 'opacity-50' : '' }}">
                    @if(isset($notification->data['type']) && $notification->data['type'] == 'friend_request')
                        <div class="flex gap-3">
                            <div class="shrink-0">
                                @if(isset($notification->data['sender_avatar']))
                                    <img src="{{ $notification->data['sender_avatar'] }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white/10">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white font-bold">
                                        {{ substr($notification->data['sender_name'] ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-300">
                                    <span class="font-bold text-white">{{ $notification->data['sender_name'] }}</span> sendte en venneanmodning.
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                
                                <div class="flex gap-2 mt-2">
                                    <a href="{{ $notification->data['action_url'] }}" wire:navigate class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition-colors">
                                        Vis anmodning
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Fallback for generic notifications --}}
                        <div class="text-sm text-gray-300">
                             {{ $notification->data['message'] ?? 'Ny notifikation' }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 text-sm">
                    Ingen notifikationer
                </div>
            @endforelse
        </div>
    </div>
</div>
