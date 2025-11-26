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
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
    <nav class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-24">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="text-blue-600">
                        <span class="text-3xl font-extrabold">SIUKM</span>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-6">
                        @auth
                            <a href="{{ route('user.dashboard') }}" class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Home
                            </a>
                            <a href="{{ route('user.feeds.index') }}" class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Feed
                            </a>
                            <a href="{{ route('user.events.index') }}" class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Event
                            </a>
                            <a href="{{ route('user.ukm.list') }}" class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                UKM
                            </a>
                        @else
                            <button onclick="showLoginModal()" class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Home
                            </button>
                            <button onclick="showLoginModal()" class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Feed
                            </button>
                            <button onclick="showLoginModal()" class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Event
                            </button>
                            <button onclick="showLoginModal()" class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                UKM
                            </button>
                        @endauth
                    </div>
                    
                    <div class="flex items-center space-x-4 ml-4">
                        @auth
                            <div class="relative group">
                                <button class="text-gray-500 hover:text-blue-600 transition-colors duration-200 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </button>
                                
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 hidden group-hover:block z-20">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        My Profile
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <button onclick="showLoginModal()" class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Login
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <style>
        .relative:hover .absolute {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>

    <main class="max-w-7xl mx-auto">
        @yield('content')
    </main>

    <footer class="bg-gradient-to-br from-gray-700 to-black border-t mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="font-bold text-xl text-blue-600">
                        <span class="text-2xl font-extrabold">SIUKM</span>
                    </div>
                    <p class="text-gray-400 text-sm mt-2">Sistem Informasi Unit Kegiatan Mahasiswa</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-6 pt-6 text-center">
                <p class="text-gray-500 text-sm">&copy; 2024 SIUKM. All rights reserved.</p>
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

    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden">
        <div class="bg-white rounded-xl max-w-md w-full mx-4 relative">
            <div class="p-6">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Login ke SI-UKM</h2>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <input type="email" name="email" placeholder="Email" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <input type="password" name="password" placeholder="Password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">
                            Login
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <button onclick="showRegisterModal()" class="text-blue-600 hover:text-blue-700">
                        Belum punya akun? Daftar di sini
                    </button>
                </div>

                <button onclick="closeLoginModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="registerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden">
        <div class="bg-white rounded-xl max-w-2xl w-full mx-4 relative max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Daftar Akun SI-UKM</h2>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <input type="text" name="name" placeholder="Nama Lengkap" required
                                value="{{ old('name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                            <input type="email" name="email" placeholder="Email" required
                                value="{{ old('email') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                            <input type="tel" name="phone" placeholder="No. HP" required
                                value="{{ old('phone') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="space-y-4">
                            <input type="text" name="nim" placeholder="NIM" required
                                value="{{ old('nim') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                            <input type="text" name="fakultas" placeholder="Fakultas" required
                                value="{{ old('fakultas') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                            <input type="text" name="jurusan" placeholder="Jurusan" required
                                value="{{ old('jurusan') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                            <input type="number" name="angkatan" placeholder="Angkatan" required
                                value="{{ old('angkatan') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mt-4 space-y-4">
                        <input type="password" name="password" placeholder="Password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button type="submit"
                        class="w-full mt-6 bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition">
                        Daftar Akun
                    </button>
                </form>

                <div class="text-center mt-4">
                    <button onclick="showLoginModal()" class="text-blue-600 hover:text-blue-700">
                        Sudah punya akun? Login di sini
                    </button>
                </div>

                <button onclick="closeRegisterModal()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>


    <script>
        // =================================================================
        // FLASH MESSAGE AUTOHIDE
        // =================================================================
        setTimeout(() => {
            const messages = document.querySelectorAll('.fixed');
            messages.forEach(msg => {
                if (msg.classList.contains('bg-green-500') || msg.classList.contains('bg-red-500')) {
                    msg.remove();
                }
            });
        }, 5000);

        // =================================================================
        // DROPDOWN FUNCTIONALITY (Profile)
        // =================================================================
        // Keep dropdown open on hover
        const profileGroup = document.querySelector('.group');
        if (profileGroup) {
            profileGroup.addEventListener('mouseenter', () => {
                const dropdown = profileGroup.querySelector('.group-hover\\:block');
                if (dropdown) {
                    dropdown.classList.remove('hidden');
                }
            });

            profileGroup.addEventListener('mouseleave', () => {
                const dropdown = profileGroup.querySelector('.group-hover\\:block');
                if (dropdown) {
                    dropdown.classList.add('hidden');
                }
            });
        }
        
        // =================================================================
        // MODAL FUNCTIONS (Login & Register)
        // =================================================================

        function showLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
            // Hanya kembalikan scroll jika register modal juga tertutup
            if (document.getElementById('registerModal').classList.contains('hidden')) {
                document.body.style.overflow = 'auto';
            }
        }

        function showRegisterModal() {
            closeLoginModal(); // Tutup login modal sebelum buka register
            document.getElementById('registerModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.add('hidden');
            // Hanya kembalikan scroll jika login modal juga tertutup
            if (document.getElementById('loginModal').classList.contains('hidden')) {
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal ketika klik outside
        document.getElementById('loginModal').addEventListener('click', function(e) {
            if (e.target === this) closeLoginModal();
        });

        document.getElementById('registerModal').addEventListener('click', function(e) {
            if (e.target === this) closeRegisterModal();
        });

        // Close modal dengan Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoginModal();
                closeRegisterModal();
            }
        });

        // Handle klik menu navbar dan button untuk guest (sama persis dengan logic Anda)
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah user guest
            const isGuest = {{ auth()->check() ? 'false' : 'true' }};
            
            if (isGuest) {
                // Handle klik menu navbar (Home, Feed, Event, UKM)
                const navButtons = document.querySelectorAll('nav button[onclick*="showLoginModal"]');
                navButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        // Tidak perlu preventDefault karena sudah berupa button
                        showLoginModal();
                    });
                });
            }
        });
    </script>

    @yield('scripts')
</body>

</html>