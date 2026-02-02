<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        {{-- Welcome Header --}}
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white">Velkommen tilbage, {{ auth()->user()->username }}</h1>
            <p class="text-gray-400 mt-1">Her er din oversigt</p>
        </div>
        
        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            {{-- Messages --}}
            <div class="glass-card rounded-lg p-5 hover-elevate transition-all cursor-pointer" onclick="window.location.href='{{ route('member.messages') }}'">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 bg-accent/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <span class="bg-accent/20 text-accent text-xs font-semibold px-2 py-0.5 rounded">Ny</span>
                </div>
                <p class="text-2xl font-bold text-white mb-1">3</p>
                <p class="text-sm text-gray-400">Nye beskeder</p>
            </div>
            
            {{-- Events --}}
            <div class="glass-card rounded-lg p-5 hover-elevate transition-all cursor-pointer" onclick="window.location.href='{{ route('events') }}'">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 bg-emerald-600/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-white mb-1">2</p>
                <p class="text-sm text-gray-400">Kommende events</p>
            </div>
            
            {{-- Profile views --}}
            <div class="glass-card rounded-lg p-5 hover-elevate transition-all">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <span class="text-xs text-emerald-400 font-medium">+12%</span>
                </div>
                <p class="text-2xl font-bold text-white mb-1">147</p>
                <p class="text-sm text-gray-400">Profil visninger</p>
            </div>
            
            {{-- Friends --}}
            <div class="glass-card rounded-lg p-5 hover-elevate transition-all cursor-pointer" onclick="window.location.href='{{ route('member.explore') }}'">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-white mb-1">24</p>
                <p class="text-sm text-gray-400">Venner</p>
            </div>
        </div>
        
        {{-- Action Cards --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-white mb-4">Hurtige handlinger</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Upload Photo --}}
                <div class="glass-card rounded-lg p-5 hover-elevate transition-all cursor-pointer group">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-accent/20 transition-colors">
                            <svg class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white mb-1 group-hover:text-accent transition-colors">Upload billede</h3>
                            <p class="text-sm text-gray-400">Tilføj billeder til din profil</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
                
                {{-- Edit Profile --}}
                <div class="glass-card rounded-lg p-5 hover-elevate transition-all cursor-pointer group" onclick="window.location.href='{{ route('member.profile.edit') }}'">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-600/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600/20 transition-colors">
                            <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white mb-1 group-hover:text-blue-400 transition-colors">Rediger profil</h3>
                            <p class="text-sm text-gray-400">Opdater dine oplysninger</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
                
                {{-- Browse Events --}}
                <div class="glass-card rounded-lg p-5 hover-elevate transition-all cursor-pointer group" onclick="window.location.href='{{ route('events') }}'">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-emerald-600/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-600/20 transition-colors">
                            <svg class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white mb-1 group-hover:text-emerald-400 transition-colors">Find events</h3>
                            <p class="text-sm text-gray-400">Se alle kommende begivenheder</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Recent Activity --}}
        <div class="glass-card rounded-lg p-6">
            <h2 class="text-xl font-semibold text-white mb-4">Seneste aktivitet</h2>
            {{-- Empty state --}}
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-400 mb-2">Ingen aktivitet endnu</p>
                <p class="text-sm text-gray-500">Din aktivitet vises her når du begynder at bruge platformen</p>
            </div>
        </div>
    </div>
</div>
