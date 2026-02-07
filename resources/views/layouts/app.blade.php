<!DOCTYPE html>
<html lang="da" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Joys.dk' }}</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#0f1013] text-white font-sans antialiased">
    {{-- Global Navigation Loading Bar (shows during wire:navigate) --}}
    <div id="navigation-loading-bar" class="fixed top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 via-pink-500 to-rose-500 z-[9999] shadow-lg shadow-rose-500/50 opacity-0 transition-opacity duration-200"></div>

    <div class="min-h-screen flex flex-col">
        {{-- Top Navigation --}}
        <x-navigation.main-nav />
        
        {{-- Main Wrapper with localized content width --}}
        <div class="flex-1 flex justify-center w-full relative">
            <div class="w-full max-w-[1600px] flex items-start">
                
                @auth
                    {{-- Desktop: Sidebar Navigation (In-Content) --}}
                    {{-- Only visible on desktop, sticky position with smooth scroll --}}
                    @persist('member-sidebar')
                        <div class="hidden md:block shrink-0 sticky top-6 z-30 ml-4 lg:ml-8 mt-6 mb-8 transition-all duration-300">
                            <x-navigation.member-sidebar />
                        </div>
                    @endpersist
                @endauth

                {{-- Page Content --}}
                <main class="flex-1 min-w-0 w-full">
                    {{ $slot }}
                </main>
            </div>
        </div>
            
        {{-- Footer --}}
        <x-footer />
    </div>
    
    @auth
        {{-- Mobile: Bottom Tab Bar --}}
        <x-navigation.member-tabbar class="md:hidden" />
    @endauth
    </div>
    
    {{-- Login Modal --}}
    <livewire:auth.login-modal />

    {{-- Mobile Navigation Drawer --}}
    <x-navigation.mobile-drawer />
    
    <x-ui.toast />

    @livewireScripts

    {{-- Alpine Global Stores --}}
    <script>
        document.addEventListener('alpine:init', () => {
            // Sidebar state store (persists across all navigations)
            Alpine.store('sidebar', {
                expanded: localStorage.getItem('sidebar-expanded') === 'true',

                toggle() {
                    this.expanded = !this.expanded;
                    localStorage.setItem('sidebar-expanded', this.expanded);
                },

                setExpanded(value) {
                    this.expanded = value;
                    localStorage.setItem('sidebar-expanded', this.expanded);
                }
            });
        });
    </script>

    {{-- Navigation Loading Bar Script --}}
    <script>
        document.addEventListener('livewire:init', () => {
            const loadingBar = document.getElementById('navigation-loading-bar');
            let timeout;

            // Show loading bar when navigation starts
            Livewire.hook('navigate', () => {
                // Small delay to avoid flashing for instant navigations
                timeout = setTimeout(() => {
                    loadingBar.style.opacity = '1';
                }, 100);
            });

            // Hide loading bar when navigation completes
            Livewire.hook('navigated', () => {
                clearTimeout(timeout);
                loadingBar.style.opacity = '0';

                // Dispatch event to update sidebar active states
                window.dispatchEvent(new CustomEvent('navigation-completed', {
                    detail: { path: window.location.pathname }
                }));
            });
        });
    </script>
</body>
</html>
