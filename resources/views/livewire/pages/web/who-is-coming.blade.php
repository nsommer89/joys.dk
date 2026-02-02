<div class="min-h-screen">
    {{-- Hero Section --}}
    <x-ui.page-header 
        title="Hvem <span class='text-accent'>kommer i dag?</span>" 
        description="Se hvem du kan møde, meld din ankomst og deltag i fællesskabet."
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- Right Column (Calendar) - On mobile this comes first usually, but design asked for redesign. Let's keep it right or swap? Standard is content left. --}}
            {{-- Let's put Calendar on the RIGHT (col-span-4) and Feed on LEFT (col-span-8) for standard reading --}}
            
            {{-- Feed Column --}}
            <div class="lg:col-span-8 space-y-8 order-2 lg:order-1">
                {{-- Input Area --}}
                <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-accent opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Skriv en besked
                    </h3>
                    <div class="relative">
                        <textarea 
                            placeholder="Fortæl hvornår du kommer og hvem du glæder dig til at se..." 
                            class="w-full bg-black/20 text-gray-200 px-4 py-4 rounded-xl border border-white/10 focus:border-accent/50 focus:ring-1 focus:ring-accent/50 focus:outline-none resize-none h-32 placeholder-gray-500 transition-all font-light"
                        ></textarea>
                        <div class="absolute bottom-3 right-3 flex items-center gap-3">
                            <button class="bg-accent hover:bg-accent-hover text-white px-6 py-2 rounded-lg text-sm font-semibold shadow-lg shadow-accent/20 transition-all transform hover:-translate-y-0.5">
                                Send besked
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Messages List --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-2">
                        <h3 class="text-white font-bold text-xl">Seneste beskeder</h3>
                        <div class="flex gap-2 text-sm">
                            <button class="text-accent font-medium">Alle</button>
                            <button class="text-gray-500 hover:text-gray-300 transition-colors">Kun ankomster</button>
                        </div>
                    </div>

                    @php
                        $messages = [
                            [
                                'name' => 'Tom',
                                'date' => '30-01-2026 14:57',
                                'message' => 'Vi kommer ca kl 16 på mandag. Glæder mig til at se jer alle sammen!',
                                'type' => 'male',
                                'avatar_bg' => 'bg-blue-500/20 text-blue-400'
                            ],
                            [
                                'name' => 'Hulapalu',
                                'date' => '30-01-2026 11:07',
                                'message' => 'På grund af vejret aflyser vi vores besøg i klubben i aften. Vi kommer i stedet på mandag. Ønsker jer en god aften!',
                                'type' => 'couple',
                                'avatar_bg' => 'bg-purple-500/20 text-purple-400'
                            ],
                            [
                                'name' => 'Mona',
                                'date' => '28-01-2026 15:51',
                                'message' => 'Kigger forbi torsdag omkring kl 14.00. Har kage med!',
                                'type' => 'female',
                                'avatar_bg' => 'bg-pink-500/20 text-pink-400'
                            ],
                             [
                                'name' => 'Parret88',
                                'date' => '28-01-2026 12:30',
                                'message' => 'Vi er spændte på vores første besøg i aften. Håber I tager godt imod os.',
                                'type' => 'couple',
                                'avatar_bg' => 'bg-purple-500/20 text-purple-400'
                            ],
                        ];
                    @endphp

                    @foreach($messages as $msg)
                        <div class="glass-card p-5 rounded-xl border border-white/5 hover:border-white/10 transition-all group">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full {{ $msg['avatar_bg'] }} flex items-center justify-center ring-2 ring-black">
                                        <span class="font-bold text-sm">{{ substr($msg['name'], 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-white font-bold text-sm truncate group-hover:text-accent transition-colors">{{ $msg['name'] }}</h4>
                                        <span class="text-[10px] text-gray-500 font-mono">{{ $msg['date'] }}</span>
                                    </div>
                                    <p class="text-gray-300 text-sm leading-relaxed">{{ $msg['message'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Calendar Column --}}
            <div class="lg:col-span-4 order-1 lg:order-2">
                <div class="sticky top-24 space-y-6">
                    {{-- Calendar Widget --}}
                    <div class="glass-card-intense rounded-2xl overflow-hidden shadow-2xl shadow-black/50 border border-white/10" x-data="{ currentMonth: 'Januar 2026' }">
                        {{-- Header --}}
                        <div class="bg-black/40 p-4 flex items-center justify-between border-b border-white/5">
                            <button class="p-1 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            </button>
                            <span class="text-white font-bold" x-text="currentMonth">Januar 2026</span>
                            <button class="p-1 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>

                        {{-- Weekday Headers --}}
                        <div class="grid grid-cols-7 text-center py-3 bg-black/20 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                            <div>Man</div><div>Tir</div><div>Ons</div><div>Tor</div><div>Fre</div><div>Lør</div><div>Søn</div>
                        </div>

                        {{-- Days --}}
                        <div class="p-4 bg-gradient-to-b from-transparent to-black/20">
                            <div class="grid grid-cols-7 gap-y-4 gap-x-2 text-sm">
                                {{-- Empty placeholders --}}
                                <div class="text-center py-1 opacity-20">29</div>
                                <div class="text-center py-1 opacity-20">30</div>
                                <div class="text-center py-1 opacity-20">31</div>
                                
                                {{-- Days 1-31 --}}
                                @for ($i = 1; $i <= 31; $i++)
                                    @php
                                        $hasEvent = in_array($i, [19, 23, 26, 30]); // Fake logic
                                        $isToday = $i === 30; // Fake today
                                    @endphp
                                    <div class="flex flex-col items-center">
                                        <button class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium transition-all relative
                                            {{ $isToday ? 'bg-accent text-white shadow-lg shadow-accent/40 scale-110 font-bold' : ($hasEvent ? 'text-white hover:bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5') }}
                                        ">
                                            {{ $i }}
                                            @if($hasEvent && !$isToday)
                                                <span class="absolute -bottom-1 w-1 h-1 rounded-full bg-accent"></span>
                                            @endif
                                        </button>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="p-4 border-t border-white/5 bg-black/20">
                            <button class="w-full bg-white/5 hover:bg-white/10 border border-white/10 text-white py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Meld din ankomst
                            </button>
                        </div>
                    </div>

                    {{-- Stats / Info --}}
                    <div class="glass-card p-5 rounded-2xl">
                        <h4 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-4">I dag i klubben</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 rounded-lg bg-white/5 border border-white/5">
                                <div class="text-2xl font-bold text-white mb-0.5">14</div>
                                <div class="text-[10px] text-gray-500">Tilmeldte</div>
                            </div>
                            <div class="text-center p-3 rounded-lg bg-white/5 border border-white/5">
                                <div class="text-2xl font-bold text-accent mb-0.5">8</div>
                                <div class="text-[10px] text-gray-500">Par</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>