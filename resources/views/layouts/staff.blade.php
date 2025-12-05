<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - Siukm Exclusive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Color: Soft Cream */
        .lux-bg {
            background-color: #f3f4f6;
            /* gray-100 - Background Konten */
        }

        /* Deep Indigo Accent (Untuk Staff) */
        .lux-indigo-dark-bg {
            background-color: #3730a3;
            /* Indigo-800 - Warna Sidebar Staff */
        }

        .lux-indigo-light-text {
            color: #a5b4fc;
            /* Indigo-300 - Text di Sidebar */
        }

        /* Neumorphism Shadow Mix (Hanya untuk konten/card) */
        .lux-neumorphic {
            border-radius: 12px;
            background: #f3f4f6;
            box-shadow:
                6px 6px 12px #d1d2d5,
                -6px -6px 12px #ffffff;
            transition: all 0.3s ease;
        }

        .lux-neumorphic-card:hover {
            box-shadow:
                8px 8px 16px #c4c5c9,
                -8px -8px 16px #ffffff;
            transform: translateY(-2px);
        }

        /* Sidebar Link Active Style (Putih terang di Indigo) */
        .lux-sidebar-link-active {
            background-color: #312e81;
            /* Sedikit lebih gelap dari Indigo-800 */
            border-left: 4px solid #818cf8;
            /* Garis terang sebagai highlight */
            font-weight: 700;
            color: #ffffff;
        }

        /* TRANSISI UNTUK SIDEBAR MOBILE */
        #mobile-sidebar {
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }

        #mobile-sidebar.open {
            transform: translateX(0);
        }

        /* Animasi flash message */
        .slide-in-from-top {
            animation: slideIn 0.5s ease-out forwards;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="lux-bg text-gray-800 antialiased">
    <div class="flex min-h-screen">

        {{-- 1. SIDEBAR (DEFAULT: DESKTOP, MOBILE: OVERLAY/HIDDEN) --}}
        
        {{-- Konten Sidebar - Dipisahkan untuk Reusabilitas --}}
        @php
            $currentRoute = request()->route()->getName();
            $navLinks = [
                ['route' => 'staff.dashboard', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
                ['route' => 'staff.ukms.index', 'icon' => 'fas fa-users', 'label' => 'My UKM'],
                ['route' => 'staff.events.index', 'icon' => 'fas fa-calendar', 'label' => 'Events'],
                ['route' => 'staff.feeds.index', 'icon' => 'fas fa-newspaper', 'label' => 'Feeds'],
                ['route' => 'staff.registrations.index', 'icon' => 'fas fa-clipboard-list', 'label' => 'Registrations'],
            ];
        @endphp
        
        {{-- Kontainer Sidebar Desktop (Tampil di lg: ke atas) --}}
        <div id="sidebar-container-desktop"
            class="w-64 lux-indigo-dark-bg text-white shadow-xl flex-col sticky top-0 h-screen z-30 hidden lg:flex">
            
            <div class="p-5 border-b border-indigo-700">
                <h1 class="text-2xl font-black tracking-widest text-white">Staff Siukm</h1>
                <p class="text-sm text-white/80 mt-1">{{ auth()->user()->name }}</p>
                <p class="text-xs lux-indigo-light-text mt-1">Staff Console</p>
            </div>

            <nav class="mt-4 flex-grow overflow-y-auto">
                @foreach ($navLinks as $link)
                    @php
                        $isActive = str_contains($currentRoute, str_replace('*', '', $link['route']));
                    @endphp
                    <a href="{{ route($link['route']) }}"
                        class="flex items-center py-3 px-5 text-indigo-200 hover:bg-indigo-700 transition duration-200 
                            {{ $isActive ? 'lux-sidebar-link-active' : '' }}">
                        <i class="{{ $link['icon'] }} w-5 mr-3"></i>{{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto pt-4 border-t border-indigo-700 p-4">
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="w-full text-left py-2 px-3 bg-red-600 hover:bg-red-700 transition duration-200 text-white rounded-lg font-semibold text-sm shadow-md">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>

        </div>

        {{-- Kontainer Sidebar Mobile (Fixed Overlay, Hidden by default) --}}
        <div id="mobile-sidebar"
            class="fixed inset-y-0 left-0 w-64 lux-indigo-dark-bg text-white shadow-xl flex-col z-40 lg:hidden">
            
            <div class="p-5 border-b border-indigo-700">
                <h1 class="text-2xl font-black tracking-widest text-white">Staff Siukm</h1>
                <p class="text-sm text-white/80 mt-1">{{ auth()->user()->name }}</p>
                <p class="text-xs lux-indigo-light-text mt-1">Staff Console</p>
            </div>

            <nav class="mt-4 flex-grow overflow-y-auto">
                @foreach ($navLinks as $link)
                    @php
                        $isActive = str_contains($currentRoute, str_replace('*', '', $link['route']));
                    @endphp
                    <a href="{{ route($link['route']) }}" onclick="toggleSidebar()"
                        class="flex items-center py-3 px-5 text-indigo-200 hover:bg-indigo-700 transition duration-200 
                            {{ $isActive ? 'lux-sidebar-link-active' : '' }}">
                        <i class="{{ $link['icon'] }} w-5 mr-3"></i>{{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto pt-4 border-t border-indigo-700 p-4">
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="w-full text-left py-2 px-3 bg-red-600 hover:bg-red-700 transition duration-200 text-white rounded-lg font-semibold text-sm shadow-md">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>

        </div>

        {{-- 2. OVERLAY BACKDROP (MOBILE) --}}
        <div id="sidebar-backdrop"
            class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" onclick="toggleSidebar()">
        </div>

        {{-- 3. KONTEN UTAMA --}}
        <div class="flex-1">
            
            {{-- MOBILE HEADER / TOGGLE BUTTON (Hanya Tampil di Mobile) --}}
{{-- MOBILE HEADER / TOGGLE BUTTON --}}
<header class="lux-bg flex items-center justify-between p-4 shadow-md sticky top-0 z-20 lg:hidden border-b border-gray-200">
    <button id="sidebar-toggle" class="text-gray-800 p-2 rounded-lg hover:bg-gray-200">
        <i class="fas fa-bars text-xl"></i>
    </button>
    <div class="flex items-center">
        <h1 class="text-lg font-bold text-gray-800">Staff Siukm</h1>
        <div class="ml-3 text-sm text-gray-600 hidden sm:block">
            {{ auth()->user()->name }}
        </div>
    </div>
</header>

            <div class="p-4 sm:p-8">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Flash Messages dengan animasi slide in --}}
    @if (session('success'))
        <div class="fixed top-4 right-4 bg-purple-600 text-white px-6 py-3 rounded-lg shadow-xl z-50 slide-in-from-top">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-xl z-50 slide-in-from-top">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

<script>
    // SIDEBAR TOGGLE - VERSION SECURE
    const sidebar = document.getElementById('mobile-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    
    function checkDesktopView() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('open');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function toggleSidebar() {
        const isOpen = sidebar.classList.toggle('open');
        backdrop.classList.toggle('hidden', !isOpen);
        document.body.classList.toggle('overflow-hidden', isOpen);
    }

    // Event delegation yang lebih baik
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle button event
        const toggleBtn = document.getElementById('sidebar-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleSidebar();
            });
        }
        
        // Backdrop click event
        if (backdrop) {
            backdrop.addEventListener('click', function() {
                toggleSidebar();
            });
        }
        
        // Sidebar links close on mobile
        const sidebarLinks = document.querySelectorAll('#mobile-sidebar a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    toggleSidebar();
                }
            });
        });
    });

    // Initialize
    checkDesktopView();
    window.addEventListener('resize', checkDesktopView);

    // Flash message cleanup yang AMAN
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            // Hanya hapus flash messages dengan class slide-in-from-top
            const flashMessages = document.querySelectorAll('.slide-in-from-top');
            flashMessages.forEach(msg => {
                msg.style.opacity = '0';
                msg.style.transition = 'opacity 0.5s ease-out';
                setTimeout(() => {
                    if (msg.parentNode) {
                        msg.remove();
                    }
                }, 500);
            });
        }, 4500);
    });
</script>
</body>
</html>