<div class="min-h-screen" x-data="{ 
    lightboxOpen: false, 
    currentImage: '', 
    currentIndex: 0,
    images: [],
    init() {
        this.images = [...document.querySelectorAll('[data-gallery-image]')].map(el => el.dataset.galleryImage);
    },
    openLightbox(imageSrc, index) {
        this.currentImage = imageSrc;
        this.currentIndex = index;
        this.lightboxOpen = true;
        document.body.classList.add('overflow-hidden');
    },
    closeLightbox() {
        this.lightboxOpen = false;
        document.body.classList.remove('overflow-hidden');
    },
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        this.currentImage = this.images[this.currentIndex];
    },
    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        this.currentImage = this.images[this.currentIndex];
    }
}">
    {{-- Hero Section --}}
    <x-ui.page-header 
        title="Joys <span class='text-accent'>Galleri</span>" 
        description="Få et indblik i stemningen og de fysiske rammer hos Joys.<span class='block mt-2 text-sm text-gray-400 font-medium'>* Vi vægter diskretion højt. Der tages aldrig billeder af gæster.</span>"
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        @if($images->count() > 0)
            {{-- Gallery Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
                @foreach($images as $index => $image)
                    <div 
                        class="group relative aspect-square overflow-hidden rounded-xl bg-gray-900 cursor-pointer"
                        @click="openLightbox('{{ Storage::url($image->image_path) }}', {{ $index }})"
                        data-gallery-image="{{ Storage::url($image->image_path) }}"
                    >
                        <img 
                            src="{{ Storage::url($image->image_path) }}" 
                            alt="Galleri billede {{ $index + 1 }}" 
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                            loading="lazy"
                        >
                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform group-hover:scale-100 scale-90">
                                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($images->hasPages())
                <div class="mt-12">
                    {{ $images->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="glass-card rounded-xl p-12 text-center border border-white/5">
                <div class="inline-flex items-center justify-center p-4 bg-white/5 rounded-full mb-4 text-gray-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white mb-2">Galleri under opbygning</h2>
                <p class="text-gray-400">Vi arbejder på at udvælge de bedste billeder af vores faciliteter.</p>
            </div>
        @endif
    </div>

    {{-- Lightbox Modal --}}
    <div 
        x-show="lightboxOpen" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="closeLightbox()"
        @keydown.arrow-right.window="next()"
        @keydown.arrow-left.window="prev()"
        class="fixed inset-0 z-[9999] flex items-center justify-center"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/95 backdrop-blur-xl" @click="closeLightbox()"></div>
        
        {{-- Close Button --}}
        <button 
            @click="closeLightbox()" 
            class="absolute top-4 right-4 z-50 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white transition-all duration-300 hover:scale-110"
        >
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Navigation Arrows - Cursor Pointer added --}}
        <button 
            @click="prev()" 
            class="absolute left-4 z-50 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white transition-all duration-300 hover:scale-110 cursor-pointer"
        >
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button 
            @click="next()" 
            class="absolute right-4 z-50 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white transition-all duration-300 hover:scale-110 cursor-pointer"
        >
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        {{-- Image Container --}}
        <div class="relative z-10 max-w-[90vw] max-h-[85vh] flex items-center justify-center p-4">
            <img 
                :src="currentImage" 
                alt="Galleri billede" 
                class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
            >
        </div>
    </div>
</div>
