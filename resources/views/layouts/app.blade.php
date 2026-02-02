<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <div class="min-h-screen flex flex-col">
        {{-- Top Navigation --}}
        <x-navigation.main-nav />
        
        {{-- Main Wrapper with localized content width --}}
        <div class="flex-1 flex justify-center w-full relative">
            <div class="w-full max-w-[1600px] flex items-start">
                
                @auth
                    {{-- Desktop: Sidebar Navigation (In-Content) --}}
                    {{-- Only visible on desktop, sticky position --}}
                    <div class="hidden md:block shrink-0 sticky top-24 z-30 ml-4 lg:ml-8 mt-8 mb-8">
                        <x-navigation.member-sidebar />
                    </div>
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
</body>
</html>
