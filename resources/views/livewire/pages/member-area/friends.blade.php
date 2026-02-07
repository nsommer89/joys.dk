<div class="max-w-4xl mx-auto py-12 px-4 md:px-0">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Venner</h1>
        <p class="text-gray-400">Administrer dine venskaber og forbindelser.</p>
    </div>

    <!-- Main Card Container -->
    <div class="bg-gradient-to-b from-[#1a1b1e]/95 via-[#1a1b1e]/98 to-[#1a1b1e] rounded-2xl border border-white/[0.08] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.4),0_2px_8px_rgba(0,0,0,0.2),inset_0_1px_0_rgba(255,255,255,0.05)] backdrop-blur-xl hover:shadow-[0_12px_48px_rgba(0,0,0,0.5),0_4px_16px_rgba(0,0,0,0.3)] hover:border-white/[0.12] transition-all duration-500">

            <!-- Tabs Navigation -->
            <div class="flex border-b border-white/[0.08] bg-gradient-to-b from-white/[0.03] to-transparent backdrop-blur-sm px-6">
                <a
                    href="{{ route('member.friends') }}"
                    wire:navigate
                    @class([
                        'relative px-8 py-4 border-b-2 font-semibold text-sm transition-all duration-500 ease-out group',
                        'border-rose-600 text-rose-500' => $activeTab === 'venner',
                        'border-transparent text-gray-400 hover:text-white' => $activeTab !== 'venner'
                    ])
                >
                    @if($activeTab === 'venner')
                        <div class="absolute inset-0 bg-gradient-to-t from-rose-500/5 to-transparent"></div>
                    @endif
                    <span class="relative">Venner</span>
                </a>
                <a
                    href="{{ route('member.friends.requests') }}"
                    wire:navigate
                    @class([
                        'relative px-8 py-4 border-b-2 font-semibold text-sm transition-all duration-500 ease-out group',
                        'border-rose-600 text-rose-500' => $activeTab === 'anmodninger',
                        'border-transparent text-gray-400 hover:text-white' => $activeTab !== 'anmodninger'
                    ])
                >
                    @if($activeTab === 'anmodninger')
                        <div class="absolute inset-0 bg-gradient-to-t from-rose-500/5 to-transparent"></div>
                    @endif
                    <span class="relative inline-flex items-center gap-2">
                        Anmodninger
                        @if(auth()->user()->receivedRequests()->where('status', 'pending')->count() > 0)
                            <span class="bg-gradient-to-r from-rose-500 to-rose-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-lg shadow-rose-500/30 animate-pulse">{{ auth()->user()->receivedRequests()->where('status', 'pending')->count() }}</span>
                        @endif
                    </span>
                </a>
            </div>

            <!-- Content Area -->
            <div class="p-8 md:p-10 min-h-[600px] bg-gradient-to-b from-transparent to-black/10">
                
                @if($activeTab === 'venner')

                    <!-- Loading Skeleton for Friends -->
                    <div wire:loading.delay wire:target="$refresh" class="hidden" wire:loading.class.remove="hidden">
                        <x-skeleton.user-list :count="6" />
                    </div>

                    <!-- Actual Friends Content -->
                    <div wire:loading.delay.class="hidden" wire:target="$refresh">
                        @if($friends->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($friends as $friend)
                                <x-user-list-item :user="$friend" />
                            @endforeach
                        </div>
                        
                            <div class="mt-8">
                                {{ $friends->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                            <div class="bg-white/5 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-2">Ingen venner endnu</h3>
                            <p class="text-gray-400 max-w-sm mx-auto mb-6">Du har ikke tilføjet nogen venner endnu. Udforsk andre profiler for at finde nye bekendtskaber.</p>
                            <a href="{{ route('member.explore') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 hover:to-rose-800 text-white font-bold rounded-xl transition-all duration-300 shadow-lg shadow-rose-500/30 hover:shadow-rose-500/40 hover:scale-105 active:scale-95">
                                Find venner
                            </a>
                        </div>
                    @endif
                    </div> <!-- End Actual Friends Content wrapper -->

                @elseif($activeTab === 'anmodninger')

                    <!-- Loading Skeleton for Requests -->
                    <div wire:loading.delay wire:target="$refresh,acceptRequest,declineRequest" class="hidden" wire:loading.class.remove="hidden">
                        <x-skeleton.friend-requests :count="5" />
                    </div>

                    <!-- Actual Requests Content -->
                    <div wire:loading.delay.class="hidden" wire:target="$refresh,acceptRequest,declineRequest">
                        @if($requests->count() > 0)
                            <div class="space-y-4">
                                @foreach($requests as $request)
                                <div class="group bg-gradient-to-br from-[#151619]/80 to-[#151619] p-4 rounded-xl border border-white/[0.08] flex flex-col md:flex-row md:items-center gap-4 hover:border-white/[0.12] hover:shadow-lg hover:shadow-black/20 transition-all duration-500 ease-out backdrop-blur-sm">
                                    <div class="flex items-center gap-4 flex-1">
                                        <a href="{{ route('member.profile.view', $request->sender->username) }}" wire:navigate>
                                            <x-avatar :user="$request->sender" size="w-12 h-12" class="ring-2 ring-white/10" show-status="true" />
                                        </a>
                                        <div>
                                            <h4 class="text-white font-bold">{{ $request->sender->username }}</h4>
                                            <p class="text-xs text-gray-500">
                                                {{ $request->sender->userProfile?->city ?? 'Ukendt by' }} 
                                                <span class="text-gray-600 mx-1">•</span> 
                                                {{ $request->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                                        <button wire:click="acceptRequest({{ $request->sender_id }})" class="flex-1 md:flex-none px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white text-xs font-bold rounded-lg transition-all duration-300 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:scale-105 active:scale-95">
                                            Accepter
                                        </button>
                                        <button wire:click="declineRequest({{ $request->sender_id }})" class="flex-1 md:flex-none px-4 py-2 bg-[#25262b] hover:bg-[#2c2d32] text-gray-400 hover:text-white text-xs font-bold rounded-lg transition-all duration-300 border border-white/10 hover:border-white/20 active:scale-95">
                                            Afvis
                                        </button>
                                        <a href="{{ route('member.profile.view', $request->sender->username) }}" wire:navigate class="flex-1 md:flex-none px-4 py-2 bg-[#25262b] hover:bg-[#2c2d32] text-white text-xs font-bold rounded-lg transition-all duration-300 border border-white/10 hover:border-white/20 text-center active:scale-95">
                                            Vis profil
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                            <div class="mt-8">
                                {{ $requests->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                            <div class="bg-white/5 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-2">Ingen anmodninger</h3>
                            <p class="text-gray-400 max-w-sm mx-auto">Du har ingen ventende venneanmodninger i øjeblikket.</p>
                        </div>
                    @endif
                    </div> <!-- End Actual Requests Content wrapper -->

                @endif
            </div>
        </div>
    </div>