@props(['count' => 12])

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @for($i = 0; $i < $count; $i++)
        <div class="group relative aspect-square bg-[#121316] rounded-lg overflow-hidden border border-white/5 animate-pulse">
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800/30 to-gray-900/30">
                <svg class="w-8 h-8 text-gray-700/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    @endfor
</div>
