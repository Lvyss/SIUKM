<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Siukm Exclusive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Color: Soft Cream */
        .lux-bg {
            background-color: #f3f4f6;
            /* gray-100 - Background Konten */
        }

        /* Deep Gold/Cognac Accent */
        .lux-gold-dark-bg {
            background-color: #b45309;
            /* Amber-700 - Warna Sidebar */
        }

        .lux-gold-light-text {
            color: #fcd34d;
            /* Amber-300 - Text di Sidebar */
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

        /* Sidebar Link Active Style (Putih terang di Cognac) */
        .lux-sidebar-link-active {
            background-color: #92400e;
            /* Sedikit lebih gelap dari Amber-700 */
            border-left: 4px solid #fcd34d;
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
    </style>
</head>

<body class="lux-bg text-gray-800 antialiased">
    <div class="flex min-h-screen">

        {{-- 1. SIDEBAR (DEFAULT: DESKTOP, MOBILE: OVERLAY/HIDDEN) --}}
        
        {{-- Konten Sidebar - Dipisahkan untuk Reusabilitas --}}
        @php
            $currentRoute = request()->route()->getName();
            $navLinks = [
                ['route' => 'admin.dashboard', 'icon' => 'fas fa-shield-alt', 'label' => 'Dashboard'],
                ['route' => 'admin.users.index', 'icon' => 'fas fa-user-circle', 'label' => 'User'],
                ['route' => 'admin.staff.index', 'icon' => 'fas fa-sitemap', 'label' => 'Staff'],
                ['route' => 'admin.categories.index', 'icon' => 'fas fa-boxes', 'label' => 'Category'],
                ['route' => 'admin.ukms.index', 'icon' => 'fas fa-award', 'label' => 'UKM'],
                ['route' => 'admin.events.index', 'icon' => 'fas fa-calendar-check', 'label' => 'Event'],
                ['route' => 'admin.feeds.index', 'icon' => 'fas fa-paper-plane', 'label' => 'Feeds'],
                [
                    'route' => 'admin.registrations.index',
                    'icon' => 'fas fa-file-invoice',
                    'label' => 'Regristation',
                ],
            ];
        @endphp
        
        {{-- Kontainer Sidebar Desktop (Tampil di lg: ke atas) --}}
        <div id="sidebar-container-desktop"
            class="w-64 lux-gold-dark-bg text-white shadow-xl flex-col sticky top-0 h-screen z-30 hidden lg:flex">
            
<div class="p-5 border-b border-amber-800">
    <h1 class="text-2xl font-black tracking-widest text-white">Admin Siukm</h1>
    <p class="text-sm text-white/80 mt-1">{{ auth()->user()->email }}</p>
    <p class="text-xs lux-gold-light-text mt-1">Exclusive Console</p>
</div>

            <nav class="mt-4 flex-grow overflow-y-auto">
                @foreach ($navLinks as $link)
                    @php
                        $isActive = str_contains($currentRoute, $link['route']);
                    @endphp
                    <a href="{{ route($link['route']) }}"
                        class="flex items-center py-3 px-5 text-amber-200 hover:bg-amber-800 transition duration-200 
                            {{ $isActive ? 'lux-sidebar-link-active' : '' }}">
                        <i class="{{ $link['icon'] }} w-5 mr-3"></i>{{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto pt-4 border-t border-amber-800 p-4">
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
            class="fixed inset-y-0 left-0 w-64 lux-gold-dark-bg text-white shadow-xl flex-col z-40 lg:hidden">
            
<div class="p-5 border-b border-amber-800">
    <h1 class="text-2xl font-black tracking-widest text-white">Admin Siukm</h1>
    <p class="text-sm text-white/80 mt-1">{{ auth()->user()->email }}</p>
    <p class="text-xs lux-gold-light-text mt-1">Exclusive Console</p>
</div>

            <nav class="mt-4 flex-grow overflow-y-auto">
                @foreach ($navLinks as $link)
                    @php
                        $isActive = str_contains($currentRoute, $link['route']);
                    @endphp
                    <a href="{{ route($link['route']) }}" onclick="toggleSidebar()"
                        class="flex items-center py-3 px-5 text-amber-200 hover:bg-amber-800 transition duration-200 
                            {{ $isActive ? 'lux-sidebar-link-active' : '' }}">
                        <i class="{{ $link['icon'] }} w-5 mr-3"></i>{{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto pt-4 border-t border-amber-800 p-4">
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
{{-- MOBILE HEADER --}}
<header class="lux-bg flex items-center justify-between p-4 shadow-md sticky top-0 z-20 lg:hidden border-b border-gray-200">
    <button id="sidebar-toggle" 
            onclick="window.toggleSidebar && window.toggleSidebar(); event.stopPropagation(); return false;" 
            class="text-gray-800 p-2 rounded-lg hover:bg-gray-200">
        <i class="fas fa-bars text-xl"></i>
    </button>
    <div class="flex items-center">
        <h1 class="text-lg font-bold text-gray-800">Admin Siukm</h1>
    </div>
</header>

            <div class="p-4 sm:p-8">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Flash Messages (tetap) --}}
    @if (session('success'))
        <div id="flash-message"
            class="fixed top-6 right-6 p-3 rounded-lg z-50 text-sm transition-all duration-300 lux-neumorphic border-l-4 border-green-500">
            <i class="fas fa-check-circle mr-2 text-green-500"></i> <span
                class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div id="flash-message"
            class="fixed top-6 right-6 p-3 rounded-lg z-50 text-sm transition-all duration-300 lux-neumorphic border-l-4 border-red-500">
            <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i> <span
                class="font-semibold">{{ session('error') }}</span>
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

    // Export fungsi ke window object agar bisa diakses dari halaman lain
    window.toggleSidebar = toggleSidebar;
    
    // Event delegation untuk semua toggle buttons
    document.addEventListener('click', function(event) {
        // Toggle via button
        if (event.target.closest('#sidebar-toggle')) {
            event.preventDefault();
            event.stopPropagation();
            toggleSidebar();
        }
        
        // Close via backdrop
        if (event.target.closest('#sidebar-backdrop')) {
            toggleSidebar();
        }
    });

    // Initialize
    checkDesktopView();
    window.addEventListener('resize', checkDesktopView);

    // PERBAIKI FLASH MESSAGE - HANYA HAPUS FLASH MESSAGE SAJA!
    setTimeout(() => {
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            flashMessage.style.opacity = '0';
            setTimeout(() => flashMessage.remove(), 300);
        }
    }, 5000);
</script>
</body>

</html>