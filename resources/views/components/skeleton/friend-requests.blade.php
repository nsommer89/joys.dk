@props(['count' => 5])

<div class="space-y-4">
    @for($i = 0; $i < $count; $i++)
        <div class="bg-[#151619] p-4 rounded-xl border border-white/5 animate-pulse">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex items-center gap-4 flex-1">
                    <!-- Avatar skeleton -->
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-800/50 to-gray-900/50"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-5 bg-white/5 rounded w-32"></div>
                        <div class="h-3 bg-white/5 rounded w-24"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="flex-1 md:flex-none h-9 w-24 bg-white/5 rounded-lg"></div>
                    <div class="flex-1 md:flex-none h-9 w-20 bg-white/5 rounded-lg"></div>
                    <div class="flex-1 md:flex-none h-9 w-24 bg-white/5 rounded-lg"></div>
                </div>
            </div>
        </div>
    @endfor
</div>
