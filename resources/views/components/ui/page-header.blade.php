@props(['title', 'description' => null])

<div class="relative overflow-hidden py-12 pb-8 md:py-16 md:pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 tracking-tight drop-shadow-lg">
            {!! $title !!}
        </h1>
        @if($description)
            <p class="text-base md:text-lg text-gray-300 max-w-2xl mx-auto leading-relaxed">
                {!! $description !!}
            </p>
        @endif
    </div>
</div>
