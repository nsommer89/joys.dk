@props(['count' => 6])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @for($i = 0; $i < $count; $i++)
        <div class="bg-[#121316] rounded-xl border border-white/5 p-6 animate-pulse">
            <div class="flex items-center gap-4 mb-4">
                <!-- Avatar skeleton -->
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-800/50 to-gray-900/50"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-5 bg-white/5 rounded w-3/4"></div>
                    <div class="h-4 bg-white/5 rounded w-1/2"></div>
                </div>
            </div>
            <div class="h-10 bg-white/5 rounded-lg"></div>
        </div>
    @endfor
</div>
