@props(['count' => 6])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @for($i = 0; $i < $count; $i++)
        <div class="group relative bg-[#121316] rounded-xl overflow-hidden border border-white/5 animate-pulse">
            <!-- Cover Image Skeleton -->
            <div class="aspect-square bg-gradient-to-br from-gray-800/50 to-gray-900/50 relative overflow-hidden">
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-700/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <!-- Album Info Skeleton -->
            <div class="p-4 space-y-2">
                <div class="h-5 bg-white/5 rounded w-3/4"></div>
                <div class="h-4 bg-white/5 rounded w-1/2"></div>
            </div>
        </div>
    @endfor
</div>
