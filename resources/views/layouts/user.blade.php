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

<body class="bg-gray-50 font-sans text-gray-800">
    <nav class="bg-white border-b shadow-md fixed top-0 w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-24">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div>
                        <img src="/img/logo_siukm.png" alt="SIUKM Logo">
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-6">
                        @auth
                            <a href="{{ route('user.dashboard') }}"
                                class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Home
                            </a>
                            <a href="{{ route('user.feeds.index') }}"
                                class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Feed
                            </a>
                            <a href="{{ route('user.events.index') }}"
                                class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Event
                            </a>
                            <a href="{{ route('user.ukm.list') }}"
                                class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                UKM
                            </a>
                        @else
                            <button onclick="showLoginModal()"
                                class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Home
                            </button>
                            <button onclick="showLoginModal()"
                                class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Feed
                            </button>
                            <button onclick="showLoginModal()"
                                class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Event
                            </button>
                            <button onclick="showLoginModal()"
                                class="text-[13px] text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                UKM
                            </button>
                        @endauth
                    </div>

                    <div class="flex items-center space-x-4 ml-4">
                        @auth
                            <div class="relative" id="profile-menu-container">
                                <button id="profile-menu-button"
                                    class="text-gray-500 hover:text-blue-600 transition-colors duration-200 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </button>

                                <div id="profile-dropdown"
                                    class="profile-dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 hidden opacity-0 transform translate-y-2 z-20">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('user.profile') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        My Profile
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <button onclick="showLoginModal()"
                                class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                Login
                            </button>
                        @endauth
                    </div>
                </div>
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
                    <h4 class="text-lg font-semibold text-white mb-4">Resource</h4>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Bantuan & FAQ</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Kebijakan
                                Privasi</a></li>
                        <li><a href="#"
                                class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Syarat
                                Penggunaan</a></li>
                        <li><a href="#"
                                class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Hubungi Kami</a>
                        </li>
                    </ul>
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

    <!-- Login Modal -->
    <div id="loginModal"
        class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[100] hidden backdrop-blur-sm">
        <div class="bg-white rounded-xl max-w-sm w-full mx-4 relative p-8 shadow-2xl transform transition-all duration-300 scale-95 opacity-0"
            id="loginModalContent">
            <button onclick="closeLoginModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
                <i class="fas fa-times text-lg"></i>
            </button>

            <div class="text-center mb-6">
                <img src="/img/logo_siukm.png" alt="SIUKM Logo" class="mx-auto h-10 mb-4">
                <h2 class="text-2xl font-extrabold text-gray-900">Selamat Datang Kembali</h2>
                <p class="text-sm text-gray-500 mt-1">Masuk untuk melanjutkan kegiatan UKM Anda.</p>
            </div>

            <form id="loginForm" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <input type="email" name="email" id="loginEmail" placeholder="Email Kampus" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <span id="loginEmailError" class="error-message"></span>
                    </div>
                    <div>
                        <input type="password" name="password" id="loginPassword" placeholder="Password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <span id="loginPasswordError" class="error-message"></span>
                    </div>
                    <button type="submit" id="loginSubmit"
                        class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        Login ke Akun
                    </button>
                </div>
            </form>

            <div class="text-center mt-6 pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <button type="button" onclick="showRegisterModal()"
                        class="text-blue-600 hover:text-blue-700 font-semibold transition">
                        Daftar di sini
                    </button>
                </p>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
<!-- Register Modal -->
<div id="registerModal"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[100] hidden backdrop-blur-sm">
    <div class="bg-white rounded-xl max-w-2xl w-full mx-4 relative p-8 shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0"
        id="registerModalContent">
        <button onclick="closeRegisterModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="text-center mb-6">
            <img src="/img/logo_siukm.png" alt="SIUKM Logo" class="mx-auto h-10 mb-4">
            <h2 class="text-2xl font-extrabold text-gray-900">Buat Akun SI-UKM Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Lengkapi data Anda untuk mendaftar sebagai anggota UKM.</p>
        </div>

        <!-- Error Alert untuk Register -->
        @if($errors->any() && session('register_errors'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="font-medium">Terjadi kesalahan:</span>
                </div>
                <ul class="list-disc list-inside mt-2 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="registerForm" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Kolom Kiri: Info Pribadi -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-2">Data Pribadi</h3>

                    <div>
                        <input type="text" name="name" id="registerName" placeholder="Nama Lengkap"
                            required value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                                   {{ $errors->has('name') && session('register_errors') ? 'border-red-500' : '' }}">
                        @if($errors->has('name') && session('register_errors'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div>
                        <input type="email" name="email" id="registerEmail" placeholder="Email Kampus"
                            required value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                                   {{ $errors->has('email') && session('register_errors') ? 'border-red-500' : '' }}">
                        @if($errors->has('email') && session('register_errors'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('email') }}</p>
                        @endif
                        <p class="text-gray-500 text-xs mt-1">Harus menggunakan @students.um.ac.id</p>
                    </div>

                    <div>
                        <input type="tel" name="phone" id="registerPhone" placeholder="No. HP" required
                            value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                                   {{ $errors->has('phone') && session('register_errors') ? 'border-red-500' : '' }}">
                        @if($errors->has('phone') && session('register_errors'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('phone') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Kolom Kanan: Info Akademik -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-2">Data Akademik</h3>

                    <div>
                        <input type="text" name="nim" id="registerNim"
                            placeholder="NIM (Nomor Induk Mahasiswa)" required value="{{ old('nim') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                                   {{ $errors->has('nim') && session('register_errors') ? 'border-red-500' : '' }}">
                        @if($errors->has('nim') && session('register_errors'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('nim') }}</p>
                        @endif
                        <p class="text-gray-500 text-xs mt-1">Minimal 12 karakter</p>
                    </div>

                    <div>
                        <input type="text" name="fakultas" id="registerFakultas" placeholder="Fakultas"
                            required value="{{ old('fakultas') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                                   {{ $errors->has('fakultas') && session('register_errors') ? 'border-red-500' : '' }}">
                        @if($errors->has('fakultas') && session('register_errors'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('fakultas') }}</p>
                        @endif
                    </div>

                    <div>
                        <input type="text" name="jurusan" id="registerJurusan" placeholder="Jurusan" required
                            value="{{ old('jurusan') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                                   {{ $errors->has('jurusan') && session('register_errors') ? 'border-red-500' : '' }}">
                        @if($errors->has('jurusan') && session('register_errors'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('jurusan') }}</p>
                        @endif
                    </div>

                    <div>
                        <input type="number" name="angkatan" id="registerAngkatan"
                            placeholder="Angkatan (Contoh: 2023)" required value="{{ old('angkatan') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                                   {{ $errors->has('angkatan') && session('register_errors') ? 'border-red-500' : '' }}">
                        @if($errors->has('angkatan') && session('register_errors'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('angkatan') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bagian Password -->
            <div class="mt-6 space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-2">Keamanan Akun</h3>

                <div>
                    <input type="password" name="password" id="registerPassword" placeholder="Buat Password"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                               {{ $errors->has('password') && session('register_errors') ? 'border-red-500' : '' }}">
                    @if($errors->has('password') && session('register_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('password') }}</p>
                    @endif
                    <p class="text-gray-500 text-xs mt-1">
                        Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, angka, dan simbol (@$!%*?&)
                    </p>
                </div>

                <div>
                    <input type="password" name="password_confirmation" id="registerPasswordConfirmation"
                        placeholder="Konfirmasi Password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                               {{ $errors->has('password_confirmation') && session('register_errors') ? 'border-red-500' : '' }}">
                    @if($errors->has('password_confirmation') && session('register_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>
            </div>

            <button type="submit" id="registerSubmit"
                class="w-full mt-6 bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition-colors duration-300 focus:outline-none focus:ring-4 focus:ring-green-300 disabled:opacity-50 disabled:cursor-not-allowed">
                Daftar Akun Sekarang
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-600">
                Sudah punya akun?
                <button type="button" onclick="showLoginModal()"
                    class="text-blue-600 hover:text-blue-700 font-semibold transition">
                    Login di sini
                </button>
            </p>
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
// DROPDOWN PROFILE TOGGLE  
// =================================================================
const profileButton = document.getElementById('profile-menu-button');
const profileDropdown = document.getElementById('profile-dropdown');
const profileContainer = document.getElementById('profile-menu-container');

function toggleProfileDropdown() {
    if (!profileButton || !profileDropdown) return;

    const isHidden = profileDropdown.classList.contains('hidden');
    
    if (isHidden) {
        profileDropdown.classList.remove('hidden', 'opacity-0', 'translate-y-2');
        profileDropdown.classList.add('opacity-100', 'translate-y-0');
    } else {
        profileDropdown.classList.remove('opacity-100', 'translate-y-0');
        profileDropdown.classList.add('opacity-0', 'translate-y-2');
        
        setTimeout(() => {
            profileDropdown.classList.add('hidden');
        }, 200);
    }
}

if (profileButton) {
    profileButton.addEventListener('click', toggleProfileDropdown);
}

document.addEventListener('click', function(event) {
    if (!profileContainer) return;
    
    const isClickInside = profileContainer.contains(event.target);
    
    if (!isClickInside && !profileDropdown.classList.contains('hidden')) {
        profileDropdown.classList.remove('opacity-100', 'translate-y-0');
        profileDropdown.classList.add('opacity-0', 'translate-y-2');
        
        setTimeout(() => {
            profileDropdown.classList.add('hidden');
        }, 200);
    }
});

// =================================================================
// VALIDATION RULES & FUNCTIONS - OPTIMIZED
// =================================================================
const validationRules = {
    loginEmail: {
        required: true,
        email: true,
        messages: {
            required: 'Email wajib diisi.',
            email: 'Format email tidak valid.'
        }
    },
    loginPassword: {
        required: true,
        messages: {
            required: 'Password wajib diisi.'
        }
    },
    registerName: {
        required: true,
        minLength: 2,
        maxLength: 255,
        messages: {
            required: 'Nama wajib diisi.',
            minLength: 'Nama minimal 2 karakter.',
            maxLength: 'Nama maksimal 255 karakter.'
        }
    },
    registerEmail: {
        required: true,
        email: true,
        pattern: /^[a-zA-Z0-9._%+-]+@students\.um\.ac\.id$/,
        messages: {
            required: 'Email wajib diisi.',
            email: 'Format email tidak valid.',
            pattern: 'Email harus menggunakan domain @students.um.ac.id.'
        }
    },
    registerPhone: {
        required: true,
        minLength: 11,
        maxLength: 15,
        pattern: /^[0-9+-\s()]+$/,
        messages: {
            required: 'Nomor HP wajib diisi.',
            minLength: 'Nomor HP minimal 11 digit.',
            maxLength: 'Nomor HP maksimal 15 digit.',
            pattern: 'Format nomor HP tidak valid.'
        }
    },
    registerNim: {
        required: true,
        minLength: 12,
        messages: {
            required: 'NIM wajib diisi.',
            minLength: 'NIM minimal 12 karakter.'
        }
    },
    registerFakultas: {
        required: true,
        minLength: 2,
        maxLength: 255,
        messages: {
            required: 'Fakultas wajib diisi.',
            minLength: 'Fakultas minimal 2 karakter.',
            maxLength: 'Fakultas maksimal 255 karakter.'
        }
    },
    registerJurusan: {
        required: true,
        minLength: 2,
        maxLength: 255,
        messages: {
            required: 'Jurusan wajib diisi.',
            minLength: 'Jurusan minimal 2 karakter.',
            maxLength: 'Jurusan maksimal 255 karakter.'
        }
    },
    registerAngkatan: {
        required: true,
        min: 2000,
        max: new Date().getFullYear() + 1,
        messages: {
            required: 'Angkatan wajib diisi.',
            min: 'Angkatan minimal tahun 2000.',
            max: `Angkatan maksimal tahun ${new Date().getFullYear() + 1}.`
        }
    },
    registerPassword: {
        required: true,
        minLength: 8,
        pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/,
        messages: {
            required: 'Password wajib diisi.',
            minLength: 'Password minimal 8 karakter.',
            pattern: 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol (@$!%*?&).'
        }
    },
    registerPasswordConfirmation: {
        required: true,
        match: 'registerPassword',
        messages: {
            required: 'Konfirmasi password wajib diisi.',
            match: 'Konfirmasi password tidak cocok.'
        }
    }
};

// Utility functions
function showError(inputId, message) {
    const input = document.getElementById(inputId);
    const errorSpan = document.getElementById(inputId + 'Error');
    if (input && errorSpan) {
        input.classList.add('error-input');
        input.classList.remove('success-input');
        errorSpan.textContent = message;
    }
}

function showSuccess(inputId) {
    const input = document.getElementById(inputId);
    const errorSpan = document.getElementById(inputId + 'Error');
    if (input && errorSpan) {
        input.classList.remove('error-input');
        input.classList.add('success-input');
        errorSpan.textContent = '';
    }
}

function clearError(inputId) {
    const input = document.getElementById(inputId);
    const errorSpan = document.getElementById(inputId + 'Error');
    if (input && errorSpan) {
        input.classList.remove('error-input', 'success-input');
        errorSpan.textContent = '';
    }
}

// Validation functions
function validateLoginFormSilent() {
    const email = document.getElementById('loginEmail');
    const password = document.getElementById('loginPassword');
    if (!email || !password) return false;
    
    const emailValid = email.value.trim() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    const passwordValid = password.value.length >= 1;
    
    return emailValid && passwordValid;
}

function validateRegisterFormSilent() {
    const requiredFields = [
        'registerName', 'registerEmail', 'registerPhone', 'registerNim',
        'registerFakultas', 'registerJurusan', 'registerAngkatan', 
        'registerPassword', 'registerPasswordConfirmation'
    ];
    
    for (let fieldId of requiredFields) {
        const field = document.getElementById(fieldId);
        if (!field || !field.value.trim()) return false;
    }
    
    const email = document.getElementById('registerEmail');
    const password = document.getElementById('registerPassword');
    const passwordConfirm = document.getElementById('registerPasswordConfirmation');
    const phone = document.getElementById('registerPhone');
    const nim = document.getElementById('registerNim');
    const angkatan = document.getElementById('registerAngkatan');
    const currentYear = new Date().getFullYear();
    
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) return false;
    if (!/@students\.um\.ac\.id$/.test(email.value)) return false;
    if (password.value.length < 8) return false;
    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/.test(password.value)) return false;
    if (password.value !== passwordConfirm.value) return false;
    if (phone.value.length < 11) return false;
    if (nim.value.length < 12) return false;
    if (angkatan.value < 2000 || angkatan.value > currentYear + 1) return false;
    
    return true;
}

function validateField(fieldId) {
    const rules = validationRules[fieldId];
    if (!rules) return true;

    const input = document.getElementById(fieldId);
    if (!input) return true;

    const value = input.value.trim();
    let isValid = true;

    clearError(fieldId);

    if (rules.required && !value) {
        showError(fieldId, rules.messages.required);
        return false;
    }

    if (!rules.required && !value) return true;

    // Email validation
    if (rules.email && value && fieldId === 'loginEmail') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showError(fieldId, rules.messages.email);
            isValid = false;
        }
    }

    // Pattern validation
    if (rules.pattern && value && fieldId !== 'loginEmail' && fieldId !== 'loginPassword') {
        if (!rules.pattern.test(value)) {
            showError(fieldId, rules.messages.pattern);
            isValid = false;
        }
    }

    // Length validation
    if (rules.minLength && value && !fieldId.includes('login') && value.length < rules.minLength) {
        showError(fieldId, rules.messages.minLength);
        isValid = false;
    }

    if (rules.maxLength && value && !fieldId.includes('login') && value.length > rules.maxLength) {
        showError(fieldId, rules.messages.maxLength);
        isValid = false;
    }

    // Number validation
    if (rules.min !== undefined && parseInt(value) < rules.min && !fieldId.includes('login')) {
        showError(fieldId, rules.messages.min);
        isValid = false;
    }

    if (rules.max !== undefined && parseInt(value) > rules.max && !fieldId.includes('login')) {
        showError(fieldId, rules.messages.max);
        isValid = false;
    }

    // Password confirmation
    if (rules.match && value && fieldId.includes('register')) {
        const matchField = document.getElementById(rules.match);
        if (matchField && value !== matchField.value) {
            showError(fieldId, rules.messages.match);
            isValid = false;
        }
    }

    if (isValid && value) showSuccess(fieldId);
    return isValid;
}

function validateForm(formType) {
    let isValid = true;
    const fields = Object.keys(validationRules).filter(field => field.startsWith(formType));
    fields.forEach(field => {
        if (!validateField(field)) isValid = false;
    });
    return isValid;
}

function updateSubmitButtons() {
    const loginSubmit = document.getElementById('loginSubmit');
    const registerSubmit = document.getElementById('registerSubmit');
    
    if (loginSubmit) loginSubmit.disabled = !validateLoginFormSilent();
    if (registerSubmit) registerSubmit.disabled = !validateRegisterFormSilent();
}

function setupRealTimeValidation() {
    Object.keys(validationRules).forEach(fieldId => {
        const input = document.getElementById(fieldId);
        if (input) {
            input.addEventListener('input', () => {
                validateField(fieldId);
                updateSubmitButtons();
            });
            input.addEventListener('blur', () => validateField(fieldId));
        }
    });

    const passwordField = document.getElementById('registerPassword');
    if (passwordField) {
        passwordField.addEventListener('input', () => {
            const confirmField = document.getElementById('registerPasswordConfirmation');
            if (confirmField && confirmField.value) validateField('registerPasswordConfirmation');
            updateSubmitButtons();
        });
    }
}

function setupFormSubmission() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateForm('login')) e.preventDefault();
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validateForm('register')) {
                e.preventDefault();
            } else {
                const submitBtn = document.getElementById('registerSubmit');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mendaftarkan...';
                
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 100);
            }
        });
    }
}

// =================================================================
// MODAL FUNCTIONS - OPTIMIZED
// =================================================================
function _animateModalIn(modalId, contentId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(contentId);
    if (!modal || !content) return;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    void content.offsetWidth;
    content.classList.remove('scale-95', 'opacity-0');
    content.classList.add('scale-100', 'opacity-100');
    clearFormErrors(modalId);
}

function _animateModalOut(modalId, contentId, callback = () => {}) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(contentId);
    if (!modal || !content) return;

    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        callback();
    }, 300);
}

function clearFormErrors(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const errorMessages = modal.querySelectorAll('.error-message');
    const errorInputs = modal.querySelectorAll('.error-input, .success-input');

    errorMessages.forEach(el => el.textContent = '');
    errorInputs.forEach(el => el.classList.remove('error-input', 'success-input'));
}

function checkBodyScroll() {
    const loginHidden = document.getElementById('loginModal').classList.contains('hidden');
    const registerHidden = document.getElementById('registerModal').classList.contains('hidden');
    if (loginHidden && registerHidden) document.body.style.overflow = 'auto';
}

function showLoginModal() {
    _animateModalIn('loginModal', 'loginModalContent');
}

function closeLoginModal() {
    _animateModalOut('loginModal', 'loginModalContent', checkBodyScroll);
}

function showRegisterModal() {
    if (!document.getElementById('loginModal').classList.contains('hidden')) {
        _animateModalOut('loginModal', 'loginModalContent', () => {
            _animateModalIn('registerModal', 'registerModalContent');
        });
    } else {
        _animateModalIn('registerModal', 'registerModalContent');
    }
}

function closeRegisterModal() {
    _animateModalOut('registerModal', 'registerModalContent', checkBodyScroll);
}

// =================================================================
// REGISTER MODAL SPECIFIC FUNCTIONS
// =================================================================
function setupRegisterValidation() {
    const registerForm = document.getElementById('registerForm');
    if (!registerForm) return;

    const emailField = document.getElementById('registerEmail');
    const nimField = document.getElementById('registerNim');
    const passwordField = document.getElementById('registerPassword');
    const confirmPasswordField = document.getElementById('registerPasswordConfirmation');
    const submitBtn = document.getElementById('registerSubmit');

    function validateRegisterForm() {
        let isValid = true;
        if (emailField && !validateEmail(emailField.value)) isValid = false;
        if (nimField && nimField.value.length < 12) isValid = false;
        if (passwordField && !validatePassword(passwordField.value)) isValid = false;
        if (confirmPasswordField && passwordField && confirmPasswordField.value !== passwordField.value) isValid = false;
        return isValid;
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const studentEmailRegex = /@students\.um\.ac\.id$/;
        return emailRegex.test(email) && studentEmailRegex.test(email);
    }

    function validatePassword(password) {
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/;
        return password.length >= 8 && passwordRegex.test(password);
    }

    function updateRegisterSubmitButton() {
        if (submitBtn) submitBtn.disabled = !validateRegisterForm();
    }

    const fields = [emailField, nimField, passwordField, confirmPasswordField];
    fields.forEach(field => {
        if (field) {
            field.addEventListener('input', updateRegisterSubmitButton);
            field.addEventListener('blur', updateRegisterSubmitButton);
        }
    });

    if (passwordField && confirmPasswordField) {
        passwordField.addEventListener('input', function() {
            if (confirmPasswordField.value) updateRegisterSubmitButton();
        });
    }

    updateRegisterSubmitButton();

    registerForm.addEventListener('submit', function(e) {
        if (!validateRegisterForm()) {
            e.preventDefault();
            return false;
        }

        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mendaftarkan...';
            
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }, 5000);
        }
    });
}

function clearRegisterErrors() {
    const errorInputs = document.querySelectorAll('#registerForm .border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    const errorMessages = document.querySelectorAll('#registerForm .text-red-500');
    errorMessages.forEach(msg => msg.remove());

    const errorAlerts = document.querySelectorAll('#registerForm .bg-red-100');
    errorAlerts.forEach(alert => alert.remove());
}

// Override showRegisterModal untuk clear errors
const originalShowRegisterModal = window.showRegisterModal;
window.showRegisterModal = function() {
    originalShowRegisterModal();
    setTimeout(() => {
        clearRegisterErrors();
        setupRegisterValidation();
    }, 100);
};

// =================================================================
// INITIALIZATION
// =================================================================
document.addEventListener('DOMContentLoaded', function() {
    // Cek jika ada error dari register form
    const hasRegisterErrors = {{ $errors->any() && session('register_errors') ? 'true' : 'false' }};
    if (hasRegisterErrors) showRegisterModal();

    // Initialize semua fungsi
    setupRealTimeValidation();
    setupFormSubmission();
    updateSubmitButtons();
    setupRegisterValidation();

    // Handle guest navigation
    const isGuest = {{ auth()->check() ? 'false' : 'true' }};
    if (isGuest) {
        document.querySelectorAll('nav button[onclick*="showLoginModal"]').forEach(btn => {
            btn.addEventListener('click', showLoginModal);
        });
    }

    // Event listeners untuk modal backdrop
    document.getElementById('loginModal').addEventListener('click', function(e) {
        const loginContent = document.getElementById('loginModalContent');
        if (!loginContent.contains(e.target)) closeLoginModal();
    });

    document.getElementById('registerModal').addEventListener('click', function(e) {
        const registerContent = document.getElementById('registerModalContent');
        if (!registerContent.contains(e.target)) closeRegisterModal();
    });

    // Close modal dengan Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLoginModal();
            closeRegisterModal();
            if (profileDropdown && !profileDropdown.classList.contains('hidden')) {
                toggleProfileDropdown();
            }
        }
    });
});
</script>
    @yield('scripts')
</body>

</html>
