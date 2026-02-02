<footer class="relative bg-[#0f1013] border-t border-white/5 pt-16 pb-8 overflow-hidden shadow-[0_-10px_40px_rgba(0,0,0,0.5)] z-20">
    {{-- Glow effect --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-px bg-gradient-to-r from-transparent via-accent/50 to-transparent shadow-[0_0_20px_rgba(225,29,72,0.3)]"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Main Footer Content --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-8 mb-16 text-center">
            
            {{-- Column 1: Links --}}
            <div class="flex flex-col items-center justify-start space-y-4">
                <a href="https://www.altertystys.dk" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors text-sm font-medium tracking-wide hover:underline decoration-accent/50 underline-offset-4">
                    www.altertystys.dk
                </a>
                <a href="mailto:klubjoys@gmail.com" class="text-gray-400 hover:text-white transition-colors text-sm font-medium tracking-wide hover:underline decoration-accent/50 underline-offset-4">
                    klubjoys@gmail.com
                </a>
                <a href="https://www.gclub.dk" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors text-sm font-medium tracking-wide hover:underline decoration-accent/50 underline-offset-4">
                    www.gclub.dk
                </a>
            </div>

            {{-- Column 2: Checkpoint --}}
            <div class="flex flex-col items-center justify-start">
                <span class="text-gray-500 text-xs uppercase tracking-widest font-semibold mb-3">Book tid til test</span>
                <a href="https://checkpoint.dk" target="_blank" rel="noopener noreferrer" class="group relative inline-block">
                    <div class="absolute inset-0 bg-accent/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <img 
                        src="https://joys.dk/images/checkpointlogo.png" 
                        alt="Checkpoint" 
                        class="h-12 w-auto relative z-10 opacity-80 group-hover:opacity-100 transition-opacity"
                    >
                </a>
            </div>

            {{-- Column 3: Company Info --}}
            <div class="flex flex-col items-center justify-start space-y-2 text-sm text-gray-500">
                <p class="font-medium text-white">D.M.T ApS</p>
                <p>Elkjærvej 30</p>
                <p>8230 Åbyhøj</p>
                <p class="pt-1">CVR: <span class="font-mono text-gray-400">33492448</span></p>
            </div>
        </div>

        {{-- Divider --}}
        <div class="h-px w-full bg-gradient-to-r from-transparent via-white/5 to-transparent mb-8"></div>

        {{-- Bottom Bar --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-600 font-medium">
            <p>&copy; {{ date('Y') }} Joys.dk. Alle rettigheder forbeholdes.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-gray-400 transition-colors">Politik</a>
                <a href="#" class="hover:text-gray-400 transition-colors">Betingelser</a>
            </div>
        </div>
    </div>
</footer>
