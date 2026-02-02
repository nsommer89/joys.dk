<div
    x-data="{ 
        show: false, 
        message: '', 
        type: 'success',
        timeout: null 
    }"
    x-on:toast.window="
        message = $event.detail.message; 
        type = $event.detail.type || 'success';
        show = true;
        clearTimeout(timeout);
        timeout = setTimeout(() => show = false, 3000);
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-4 md:translate-x-4"
    class="fixed top-6 md:top-8 right-4 left-4 md:left-auto md:right-8 z-[110] w-auto max-w-[calc(100vw-2rem)] md:max-w-sm"
    style="display: none;"
>
    <div :class="{
        'bg-emerald-500 text-white': type === 'success',
        'bg-red-500 text-white': type === 'error',
        'bg-blue-500 text-white': type === 'info',
        'bg-yellow-500 text-black': type === 'warning'
    }" class="rounded-2xl shadow-2xl p-3 md:p-4 flex items-center gap-3 border border-white/10">
        <div class="flex-shrink-0">
            <template x-if="type === 'success'">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
        </div>
        <div class="flex-1 font-medium text-sm" x-text="message"></div>
        <button @click="show = false" class="text-white/50 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
