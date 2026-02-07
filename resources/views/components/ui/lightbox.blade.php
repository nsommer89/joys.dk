@props(['images' => [], 'initialImageId' => null])

<div x-data="lightbox(@js($images), @js($initialImageId))" class="contents" {{ $attributes }}>
    {{ $slot }}

    <template x-teleport="body">
        <div 
            x-show="isOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md flex items-center justify-center p-4"
            style="display: none; touch-action: manipulation;"
            @keydown.window.escape="close"
            @keydown.window.arrow-right="next"
            @keydown.window.arrow-left="prev"
        >
            <!-- Controls -->
            <button @click="close" class="absolute top-4 right-4 z-[110] p-2 text-white/50 hover:text-white transition-colors rounded-full hover:bg-white/10">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <button @click="prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-[110] p-3 text-white/50 hover:text-white transition-colors rounded-full hover:bg-white/10">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>

            <button @click="next" class="absolute right-4 top-1/2 -translate-y-1/2 z-[110] p-3 text-white/50 hover:text-white transition-colors rounded-full hover:bg-white/10">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            
            <!-- Mobile Tap Areas (now redundant but kept for extra touch area if needed, though swipe is better) -->
            <!-- Removing Mobile Tap Areas in favor of Swipe and explicit buttons -->
            <!-- <div class="absolute inset-y-0 left-0 w-1/4 z-[105]" @click="prev" style="touch-action: manipulation"></div> -->
            <!-- <div class="absolute inset-y-0 right-0 w-1/4 z-[105]" @click="next" style="touch-action: manipulation"></div> -->

            <!-- Image Container -->
            <div 
                class="relative w-full h-full flex flex-col items-center justify-center pointer-events-none"
                @touchstart="touchStart($event)"
                @touchend="touchEnd($event)"
            >
                <div 
                    class="relative max-w-full max-h-full pointer-events-auto" 
                    x-show="isOpen" 
                    x-transition:enter="transition ease-out duration-300 transform" 
                    x-transition:enter-start="opacity-0 scale-95" 
                    x-transition:enter-end="opacity-100 scale-100"
                >
                    <img 
                        :src="activeImage.url" 
                        class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl bg-[#0f1013]"
                        style="touch-action: manipulation"
                    >
                    
                    <!-- Caption -->
                    <div class="mt-4 text-center">
                        <p class="text-white font-bold text-lg" x-text="activeImage.albumName"></p>
                        <p class="text-white/50 text-sm">
                            <span x-text="activeIndex + 1"></span> / <span x-text="total"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@script
<script>
    Alpine.data('lightbox', (images, initialImageId = null) => ({
        isOpen: false,
        activeIndex: 0,
        images: images,
        baseUrl: '',
        queryString: '',
        touchStartX: 0,
        touchEndX: 0,

        init() {
            // Store current query string (e.g. ?page=2)
            this.queryString = window.location.search;
            
            // Determine base URL (Album URL without image hash)
            // We use the pathname directly to avoid issues with query params in split
            const pathname = window.location.pathname;
            
            if (initialImageId) {
                // If we have an initial ID, and it's in the URL, strip it.
                // We assume the URL structure ends with /hash
                // Check if pathname ends with the ID
                if (pathname.endsWith('/' + initialImageId)) {
                    this.baseUrl = pathname.substring(0, pathname.lastIndexOf('/'));
                } else {
                    // Fallback: assume current path is base
                    this.baseUrl = pathname;
                }
                
                // Ensure type match by converting both to string
                const index = this.images.findIndex(img => String(img.id) === String(initialImageId));
                if (index !== -1) {
                    this.activeIndex = index;
                    this.isOpen = true;
                    document.body.classList.add('overflow-hidden');
                }
            } else {
                // We are at the album root
                // Remove trailing slash if present for consistency
                this.baseUrl = pathname.endsWith('/') ? pathname.slice(0, -1) : pathname;
            }

            this.$watch('activeIndex', (value) => {
                if (this.isOpen) {
                    this.updateUrl(value);
                }
            });

            this.$watch('isOpen', (value) => {
                if (!value) {
                    // Revert to base URL + original query string
                    history.pushState(null, '', this.baseUrl + this.queryString);
                    document.body.classList.remove('overflow-hidden');
                } else {
                    document.body.classList.add('overflow-hidden');
                    this.updateUrl(this.activeIndex);
                }
            });
        },

        updateUrl(index) {
            const img = this.images[index];
            if(img) {
               // Append id to base path, then append query string
               history.pushState(null, '', this.baseUrl + '/' + img.id + this.queryString);
            }
        },

        get activeImage() {
            return this.images[this.activeIndex] || {};
        },

        get total() {
            return this.images.length;
        },

        open(index) {
            this.activeIndex = index;
            this.isOpen = true;
        },

        close() {
            this.isOpen = false;
        },

        next() {
            this.activeIndex = (this.activeIndex + 1) % this.total;
        },

        prev() {
            this.activeIndex = (this.activeIndex - 1 + this.total) % this.total;
        },

        touchStart(e) {
            this.touchStartX = e.changedTouches[0].screenX;
        },

        touchEnd(e) {
            this.touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        },

        handleSwipe() {
            // Minimum swipe distance
            if (Math.abs(this.touchEndX - this.touchStartX) < 50) return;

            if (this.touchEndX < this.touchStartX) {
                // Swiped left, show next
                this.next();
            }

            if (this.touchEndX > this.touchStartX) {
                // Swiped right, show prev
                this.prev();
            }
        }
    }));
</script>
@endscript
