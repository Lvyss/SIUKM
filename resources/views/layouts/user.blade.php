<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-UKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Reset */
        * {
            margin: 0;
            padding: 0;
            border: 0;
            box-sizing: border-box;
        }
/* Nonaktifkan tombol mata bawaan browser */
input[type="password"]::-webkit-credentials-auto-fill-button,
input[type="password"]::-webkit-caps-lock-indicator,
input[type="password"]::-webkit-credentials-auto-fill-button,
input[type="password"]::-webkit-strong-password-auto-fill-button,
input[type="password"]::-webkit-strong-password-auto-fill-button {
  display: none !important;
}

/* Untuk Firefox dan browser lain */
input[type="password"][data-show-password="false"] {
  -moz-text-security: disc;
}

/* Atau cara yang lebih general */
input[type="password"] {
  /* Untuk non-webkit browsers */
  &::-ms-reveal,
  &::-ms-clear {
    display: none;
  }
}
        /* Scrollbar Hide for Utility */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Line Clamp Utilities */
        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }

        .line-clamp-4 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 4;
        }

        /* Styling tambahan untuk dropdown yang akan di-toggle oleh JS */
        .profile-dropdown-menu {
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
        }

        /* Error styles */
        .error-input {
            border-color: #ef4444 !important;
            background-color: #fef2f2;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: block;
        }

        .success-input {
            border-color: #10b981 !important;
            background-color: #f0fdf4;
        }
        
    </style>
</head>

<body class="bg-[#ececec] font-sans text-gray-800">
    
    <nav class="bg-white border-b shadow-md fixed top-0 w-full z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-24">
            <div class="flex justify-between items-center h-16">

                <div class="flex items-center">
                    <div>
                        <img src="/img/logo_siukm.png" alt="SIUKM Logo" class="h-8">
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-12">
                    <div class="flex items-center space-x-12">
                        @auth
                            <a href="{{ route('user.dashboard') }}"
                                class="text-[13px] text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200">Home</a>
                            <a href="{{ route('user.feeds.index') }}"
                                class="text-[13px] text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200">Feed</a>
                            <a href="{{ route('user.events.index') }}"
                                class="text-[13px] text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200">Event</a>
                            <a href="{{ route('user.ukm.list') }}"
                                class="text-[13px] text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200">UKM</a>
                        @else
                            <button onclick="showLoginModal()"
                                class="text-[13px] text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200">Home</button>
                            <button onclick="showLoginModal()"
                                class="text-[13px] text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200">Feed</button>
                            <button onclick="showLoginModal()"
                                class="text-[13px] text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200">Event</button>
                            <button onclick="showLoginModal()"
                                class="text-[13px] text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200">UKM</button>
                        @endauth
                    </div>

                    <div class="flex items-center space-x-4 ml-4">
                        @auth
                            <div class="relative" id="profile-menu-container-desktop">
                                <button id="profile-menu-button-desktop"
                                    class="text-gray-500 hover:text-orange-600 transition-colors duration-200 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </button>

                                <div id="profile-dropdown-desktop"
                                    class="profile-dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 hidden opacity-0 transform translate-y-2 z-30">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                                    </div>
                                    <a href="{{ route('user.profile') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">My Profile</a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <button onclick="showLoginModal()"
                                class="text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200">                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg></button>
                        @endauth
                    </div>
                </div>

                <div class="flex items-center md:hidden space-x-4">
                    
                    <div class="flex items-center">
                        @auth
                            <div class="relative" id="profile-menu-container-mobile">
                                <button id="profile-menu-button-mobile"
                                    class="text-gray-500 hover:text-orange-600 transition-colors duration-200 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </button>
                                <div id="profile-dropdown-mobile"
                                    class="profile-dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 hidden opacity-0 transform translate-y-2 z-30">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                                    </div>
                                    <a href="{{ route('user.profile') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">My Profile</a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <button onclick="showLoginModal()"
                                class="text-gray-600 hover:text-orange-600 font-medium transition-colors duration-200 text-sm">Login</button>
                        @endauth
                    </div>

                    <button id="mobile-menu-button" type="button"
                        class="p-2 rounded-md text-gray-500 hover:text-orange-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <div class="hidden md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t">
                @auth
                    <a href="{{ route('user.dashboard') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">Home</a>
                    <a href="{{ route('user.feeds.index') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">Feed</a>
                    <a href="{{ route('user.events.index') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">Event</a>
                    <a href="{{ route('user.ukm.list') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">UKM</a>
                @else
                    <button onclick="showLoginModal()"
                        class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">Home</button>
                    <button onclick="showLoginModal()"
                        class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">Feed</button>
                    <button onclick="showLoginModal()"
                        class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">Event</button>
                    <button onclick="showLoginModal()"
                        class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">UKM</button>
                @endauth
            </div>
        </div>
    </nav>
    
    <main class="max-w-7xl mx-auto pt-16">
        @yield('content')
    </main>

    <footer class="bg-gray-900 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 border-b border-gray-700 pb-10">
                <div class="col-span-2 md:col-span-1 pr-6">
                    <div class="mb-4">
                        <img src="/img/logo_siukm.png" alt="SIUKM Logo" class="h-8">
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Sistem Informasi Unit Kegiatan Mahasiswa (SIUKM).
                        Jelajahi minat, raih prestasi, dan bentuk komunitas di kampusmu.
                    </p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-orange-600 transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-orange-600 transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-orange-600 transition-colors">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Navigasi</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}"
                                class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Home</a></li>
                        <li><a href="{{ route('user.ukm.list') }}"
                                class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Daftar UKM</a>
                        </li>
                        <li><a href="{{ route('user.events.index') }}"
                                class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Event Kampus</a>
                        </li>
                        <li><a href="{{ route('user.feeds.index') }}"
                                class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Feed Berita</a>
                        </li>
                    </ul>
                </div>

                <div>
                    </div>


                <div class="col-span-2 md:col-span-1">
                    <h4 class="text-lg font-semibold text-white mb-4">Info Kampus</h4>
                    <p class="text-gray-400 text-sm">
                        Gunakan SIUKM untuk mengelola informasi, pendaftaran, dan kegiatan unit mahasiswa.
                        <br><br>
                        <span class="text-xs text-gray-500">Dikelola oleh BEM/DPM.</span>
                    </p>
                </div>
            </div>

            <div class="mt-8 pt-4 text-center">
                <p class="text-gray-500 text-sm">&copy; 2024 SIUKM - All rights reserved.</p>
            </div>
        </div>
    </footer>

    @if (session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif


    @include('layouts.partials.modals')

    @include('layouts.partials.scripts')

    @yield('scripts')
    

</body>

</html>