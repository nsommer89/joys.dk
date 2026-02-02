@props(['title', 'description' => null, 'actions' => null])

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                {!! $title !!}
            </h1>
            @if($description)
                <p class="text-gray-400 mt-1 text-sm md:text-base max-w-2xl">
                    {!! $description !!}
                </p>
            @endif
        </div>
        @if($actions)
            <div class="flex items-center gap-3">
                {{ $actions }}
            </div>
        @endif
    </div>
    {{-- Subtle divider to separate header from content --}}
    <div class="h-px w-full bg-gradient-to-r from-white/10 to-transparent mt-6"></div>
</div>
