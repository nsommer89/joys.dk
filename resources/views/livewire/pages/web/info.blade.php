<div class="min-h-screen">
    {{-- Hero Section --}}
    <x-ui.page-header 
        title="Info & <span class='text-accent'>Praktisk</span>" 
        description="Her finder du alt hvad du skal vide om regler, priser, åbningstider og meget mere."
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex flex-col lg:flex-row gap-4 lg:gap-8">
            {{-- Left Sidebar Navigation --}}
            <div class="lg:w-64 flex-shrink-0 relative z-20">
                <nav class="sticky top-24 space-y-1">
                    {{-- Mobile Dropdown (Replaces horizontal scroll for better responsiveness) --}}
                    <div class="lg:hidden mb-2 relative" x-data="{ open: false }">
                        <label class="block text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1 ml-1">Find information her...</label>
                        <button 
                            @click="open = !open"
                            @click.outside="open = false"
                            class="w-full flex items-center justify-between bg-[#1b1c20] border border-white/20 rounded-lg px-4 py-2.5 text-white font-medium shadow-lg active:scale-[0.99] transition-all group hover:border-accent/50"
                        >
                            <div class="flex items-center gap-3">
                                <span class="bg-accent/10 text-accent p-1 rounded-md group-hover:bg-accent group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold">{{ $this->getSections()[$section] ?? 'Vælg emne' }}</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 group-hover:text-white" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute top-full left-0 right-0 mt-3 bg-[#1b1c20] border border-white/10 rounded-xl shadow-2xl shadow-black z-50 overflow-hidden divide-y divide-white/5 ring-1 ring-white/10"
                            style="display: none;"
                        >
                            @foreach($this->getSections() as $key => $label)
                                <a 
                                    href="{{ route('info', $key) }}"
                                    wire:navigate
                                    @click="open = false"
                                    class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-white/5 transition-all flex items-center justify-between group {{ $section === $key ? 'bg-accent/10' : '' }}"
                                >
                                    <span class="{{ $section === $key ? 'text-accent' : 'text-gray-300 group-hover:text-white' }}">
                                        {{ $label }}
                                    </span>
                                    @if($section === $key)
                                        <svg class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Desktop Menu --}}
                    <div class="hidden lg:block space-y-1 bg-[#121316] rounded-xl p-2 border border-white/5">
                        @foreach($this->getSections() as $key => $label)
                            <a 
                                href="{{ route('info', $key) }}"
                                wire:navigate
                                class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-all flex items-center justify-between group {{ $section === $key ? 'bg-accent !text-white hover:!text-white shadow-lg shadow-accent/20' : 'text-gray-400 hover:!text-white hover:bg-white/5' }}"
                            >
                                {{ $label }}
                                @if($section === $key)
                                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </nav>
            </div>

            {{-- Main Content Area --}}
            <div class="flex-1">
                <div class="glass-card-intense rounded-xl p-6 md:p-8 lg:min-h-[500px] relative overflow-hidden">
                    {{-- Loading State --}}
                    <div wire:loading class="absolute inset-0 z-50 bg-[#121316]/50 backdrop-blur-sm flex items-center justify-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-8 h-8 border-4 border-accent border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-sm text-gray-400 font-medium">Henter indhold...</span>
                        </div>
                    </div>

                    {{-- Content Sections --}}
                    <div class="prose prose-invert max-w-none">
                        @switch($section)
                            @case('om-joys')
                                <h2 class="text-2xl font-bold text-white mb-4">Om Joys</h2>
                                <p class="text-gray-300 leading-relaxed mb-4">
                                    Joys er kendt for sin intime, hyggelige og imødekommende stemning. Lokalerne ligger i Åbyhøj ved Århus tæt på motorvejen.
                                </p>
                                <p class="text-gray-300 leading-relaxed mb-4">
                                    Klubben ligger anonymt gemt af vejen med en diskret facade og gode parkeringsforhold.
                                </p>
                                <ul class="list-disc list-inside text-gray-300 space-y-2 mb-6">
                                    <li>450 kvm "legeplads"</li>
                                    <li>Store omklædningsrum med badefaciliteter</li>
                                    <li>Lækker bar med stort udvalg</li>
                                    <li>Rygning tilladt i rygerum</li>
                                </ul>
                                <img src="https://images.unsplash.com/photo-1517457373958-b7bdd4587205?w=800&h=400&fit=crop" alt="Joys Interior" class="w-full rounded-lg shadow-lg mb-6">
                                @break

                            @case('aabningstider')
                                <h2 class="text-2xl font-bold text-white mb-4">Åbningstider</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white/5 p-4 rounded-lg">
                                        <h3 class="font-bold text-accent mb-2">Fredag & Lørdag</h3>
                                        <p class="text-white">20:00 - 02:00</p>
                                        <p class="text-sm text-gray-400 mt-1">Dørene lukker kl. 22:00</p>
                                    </div>
                                    <div class="bg-white/5 p-4 rounded-lg">
                                        <h3 class="font-bold text-accent mb-2">Søndag (Udvalgte)</h3>
                                        <p class="text-white">16:00 - 20:00</p>
                                        <p class="text-sm text-gray-400 mt-1">Se kalender for datoer</p>
                                    </div>
                                </div>
                                <p class="text-gray-400 text-sm mt-6">
                                    * Åbningstider kan variere ved specielle events. Tjek altid vores eventkalender.
                                </p>
                                @break

                            @case('regler')
                                <h2 class="text-2xl font-bold text-white mb-6">Regler & Etik</h2>
                                <div class="grid gap-4">
                                    {{-- Respekt --}}
                                    <div class="bg-white/5 rounded-xl p-6 border border-white/5 flex gap-4 items-start hover:bg-white/[0.07] transition-colors">
                                        <div class="p-3 bg-accent/10 rounded-lg text-accent">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white mb-2">Respekt</h3>
                                            <p class="text-gray-400 text-sm leading-relaxed">
                                                Respekter altid et "nej". Et nej er et nej, uanset situationen. Vi værner om vores medlemmers grænser.
                                            </p>
                                        </div>
                                    </div>
                                    
                                    {{-- Fotografering --}}
                                    <div class="bg-white/5 rounded-xl p-6 border border-white/5 flex gap-4 items-start hover:bg-white/[0.07] transition-colors">
                                        <div class="p-3 bg-red-500/10 rounded-lg text-red-500">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <line x1="3" y1="3" x2="21" y2="21" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white mb-2">Fotografering Forbudt</h3>
                                            <p class="text-gray-400 text-sm leading-relaxed">
                                                Fotografering er strengt forbudt på hele området. Mobiltelefoner skal blive i skabet eller være slukkede.
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Hygiejne --}}
                                    <div class="bg-white/5 rounded-xl p-6 border border-white/5 flex gap-4 items-start hover:bg-white/[0.07] transition-colors">
                                        <div class="p-3 bg-blue-500/10 rounded-lg text-blue-500">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white mb-2">Hygiejne</h3>
                                            <p class="text-gray-400 text-sm leading-relaxed">
                                                Vi forventer god personlig hygiejne. Benyt badefaciliteterne inden leg for alles skyld.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @break

                            @case('priser')
                                <h2 class="text-2xl font-bold text-white mb-4">Priser & Medlemskab</h2>
                                    {{-- Desktop Table --}}
                                    <div class="hidden md:block overflow-hidden rounded-xl border border-white/10">
                                        <table class="min-w-full divide-y divide-white/10">
                                            <thead class="bg-white/5">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Pris</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-white/10 text-gray-300">
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">Single herre (Medlemskab/år)</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-white font-bold">500,-</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">Single kvinde / Par (Medlemskab/år)</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-white font-bold">Gratis</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">Entré (Varierer efter event)</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-white font-bold">150,- til 350,-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Mobile Cards --}}
                                    <div class="md:hidden space-y-3">
                                        <div class="bg-white/5 rounded-xl p-4 border border-white/5 flex justify-between items-center">
                                            <div>
                                                <h4 class="text-white font-semibold">Single herre</h4>
                                                <p class="text-xs text-gray-400 mt-0.5">Medlemskab pr. år</p>
                                            </div>
                                            <div class="text-accent font-bold text-lg">500,-</div>
                                        </div>
                                        <div class="bg-white/5 rounded-xl p-4 border border-white/5 flex justify-between items-center">
                                            <div>
                                                <h4 class="text-white font-semibold">Single kvinde / Par</h4>
                                                <p class="text-xs text-gray-400 mt-0.5">Medlemskab pr. år</p>
                                            </div>
                                            <div class="text-accent font-bold text-lg">Gratis</div>
                                        </div>
                                        <div class="bg-white/5 rounded-xl p-4 border border-white/5 flex justify-between items-center">
                                            <div>
                                                <h4 class="text-white font-semibold">Entré</h4>
                                                <p class="text-xs text-gray-400 mt-0.5">Varierer efter event</p>
                                            </div>
                                            <div class="text-white font-bold text-right">
                                                <span class="block">150,-</span>
                                                <span class="text-xs text-gray-400 font-normal">til 350,-</span>
                                            </div>
                                        </div>
                                    </div>
                                @break

                            @case('hjaelp')
                                <h2 class="text-2xl font-bold text-white mb-4">Hjælp & FAQ</h2>
                                <div class="space-y-4">
                                    <details class="group bg-white/5 rounded-lg open:bg-white/10 transition-all">
                                        <summary class="flex items-center justify-between p-4 font-medium text-white cursor-pointer list-none">
                                            <span>Er der dresscode?</span>
                                            <svg class="w-5 h-5 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </summary>
                                        <div class="px-4 pb-4 text-gray-300">
                                            Ja, vi har dresscode til de fleste events. Sexet undertøj, læder, latex eller pænt tøj. Ingen alm. gadetøj/jeans.
                                        </div>
                                    </details>
                                    <details class="group bg-white/5 rounded-lg open:bg-white/10 transition-all">
                                        <summary class="flex items-center justify-between p-4 font-medium text-white cursor-pointer list-none">
                                            <span>Kan man komme som nybegynder?</span>
                                            <svg class="w-5 h-5 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </summary>
                                        <div class="px-4 pb-4 text-gray-300">
                                            Absolut! Vi tager godt imod nye. Du er velkommen til at kontakte os inden for en rundvisning eller "intro-snak".
                                        </div>
                                    </details>
                                </div>
                                @break

                            @case('samkoersel')
                                <h2 class="text-2xl font-bold text-white mb-4">Samkørsel</h2>
                                <p class="text-gray-300 mb-4">
                                    Vi opfordrer til samkørsel for både miljøets skyld og for at møde nye mennesker. 
                                    Som medlem kan du bruge vores interne forum til at koordinere kørsel.
                                </p>
                                <div class="bg-accent/10 border border-accent/20 p-4 rounded-lg flex items-start gap-4">
                                    <svg class="w-6 h-6 text-accent mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-white">Log ind for at se samkørselsforum</h4>
                                        <p class="text-sm text-gray-400 mt-1">Du skal være medlem for at få adgang til denne funktion.</p>
                                        <button @click="$dispatch('open-login-modal')" class="text-accent hover:text-white text-sm font-semibold mt-2 transition-colors">Log ind her &rarr;</button>
                                    </div>
                                </div>
                                @break

                            @case('kontakt')
                                <h2 class="text-2xl font-bold text-white mb-4">Kontakt Os</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h3 class="font-bold text-white mb-2">Adresse</h3>
                                        <p class="text-gray-400">
                                            Joys<br>
                                            Elkjærvej 30<br>
                                            8230 Åbyhøj
                                        </p>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white mb-2">Kontaktinfo</h3>
                                        <p class="text-gray-400">
                                            Email: <a href="mailto:klubjoys@gmail.com" class="text-accent hover:underline">klubjoys@gmail.com</a><br>
                                            Telefon: +45 XX XX XX XX
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-8">
                                    <h3 class="font-bold text-white mb-4">Send os en besked</h3>
                                    <form class="space-y-4">
                                        <input type="text" placeholder="Dit navn" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition-all">
                                        <input type="email" placeholder="Din email" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition-all">
                                        <textarea rows="4" placeholder="Din besked" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition-all"></textarea>
                                        <button type="submit" class="bg-accent hover:bg-accent-hover text-white font-medium px-6 py-3 rounded-lg transition-all w-full md:w-auto">
                                            Send besked
                                        </button>
                                    </form>
                                </div>
                                @break
                                
                            @default
                                <p class="text-gray-400">Vælg et punkt fra menuen.</p>
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
