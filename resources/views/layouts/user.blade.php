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
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
    {{-- PERBAIKAN: Menambahkan 'fixed', 'top-0', 'w-full', dan 'z-10' --}}
    <nav class="bg-white border-b shadow-md fixed top-0 w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-24">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div>
                        {{-- Asumsi path gambar sudah benar --}}
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
                            {{-- Mengganti button onclick untuk menu navigasi --}}
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
                            {{-- PERBAIKAN: Hapus kelas 'group', Tambahkan 'id' untuk trigger JS --}}
                            <div class="relative" id="profile-menu-container">
                                <button id="profile-menu-button"
                                    class="text-gray-500 hover:text-blue-600 transition-colors duration-200 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </button>

                                {{-- PERBAIKAN: Hapus 'group-hover:block', Tambahkan 'id' dan 'profile-dropdown-menu' untuk JS --}}
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

    {{-- Hapus style .relative:hover .absolute karena kita menggunakan JS toggle --}}

    {{-- PERBAIKAN: Menambahkan padding top (pt-16) setara tinggi navbar (h-16) agar konten tidak tertutup --}}
    <main class="max-w-7xl mx-auto pt-16"> 
        @yield('content')
    </main>

    {{-- KODE FOOTER DAN MODAL SELANJUTNYA TETAP SAMA --}}
{{-- PERBAIKAN FOOTER START --}}
<footer class="bg-gray-900 mt-20"> {{-- Ganti ke dark solid background --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"> {{-- Tambah padding vertikal --}}
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 border-b border-gray-700 pb-10">
            
            {{-- Kolom 1: Logo & Deskripsi --}}
            <div class="col-span-2 md:col-span-1 pr-6">
                <div class="mb-4">
                    {{-- Ganti warna logo atau pastikan logo_siukm.png kontras dengan background gelap --}}
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

            {{-- Kolom 2: Navigasi Cepat --}}
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Navigasi</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Home</a></li>
                    <li><a href="{{ route('user.ukm.list') }}" class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Daftar UKM</a></li>
                    <li><a href="{{ route('user.events.index') }}" class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Event Kampus</a></li>
                    <li><a href="{{ route('user.feeds.index') }}" class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Feed Berita</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Resources --}}
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Resource</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Bantuan & FAQ</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Syarat Penggunaan</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-orange-600 transition-colors">Hubungi Kami</a></li>
                </ul>
            </div>
            
            {{-- Kolom 4: Placeholder (Bisa diisi informasi kampus, peta, dll.) --}}
            <div class="col-span-2 md:col-span-1">
                <h4 class="text-lg font-semibold text-white mb-4">Info Kampus</h4>
                <p class="text-gray-400 text-sm">
                    Gunakan SIUKM untuk mengelola informasi, pendaftaran, dan kegiatan unit mahasiswa.
                    <br><br>
                    <span class="text-xs text-gray-500">Dikelola oleh BEM/DPM.</span>
                </p>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="mt-8 pt-4 text-center">
            <p class="text-gray-500 text-sm">&copy; 2024 SIUKM - All rights reserved.</p>
        </div>
    </div>
</footer>
{{-- PERBAIKAN FOOTER END --}}

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

{{-- PERBAIKAN: Login Modal (Lebih Ringkas & Berfokus) --}}
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[100] hidden backdrop-blur-sm">
    <div class="bg-white rounded-xl max-w-sm w-full mx-4 relative p-8 shadow-2xl transform transition-all duration-300 scale-95 opacity-0" 
         id="loginModalContent">
        <button onclick="closeLoginModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
            <i class="fas fa-times text-lg"></i>
        </button>
        
        <div class="text-center mb-6">
            {{-- Tambahkan Logo --}}
            <img src="/img/logo_siukm.png" alt="SIUKM Logo" class="mx-auto h-10 mb-4"> 
            <h2 class="text-2xl font-extrabold text-gray-900">Selamat Datang Kembali</h2>
            <p class="text-sm text-gray-500 mt-1">Masuk untuk melanjutkan kegiatan UKM Anda.</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <input type="email" name="email" placeholder="Email Kampus" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">

                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-4 focus:ring-blue-300">
                    Login ke Akun
                </button>
            </div>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-600">
                Belum punya akun? 
                <button type="button" onclick="showRegisterModal()" class="text-blue-600 hover:text-blue-700 font-semibold transition">
                    Daftar di sini
                </button>
            </p>
        </div>
    </div>
</div>

{{-- PERBAIKAN: Register Modal (Lebih Terstruktur & Responsif) --}}
<div id="registerModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[100] hidden backdrop-blur-sm">
    {{-- Tambahkan max-h-full dan overflow-y-auto untuk skenario layar kecil --}}
    <div class="bg-white rounded-xl max-w-2xl w-full mx-4 relative p-8 shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0"
         id="registerModalContent">
        <button onclick="closeRegisterModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
            <i class="fas fa-times text-lg"></i>
        </button>
        
        <div class="text-center mb-6">
            {{-- Tambahkan Logo --}}
            <img src="/img/logo_siukm.png" alt="SIUKM Logo" class="mx-auto h-10 mb-4"> 
            <h2 class="text-2xl font-extrabold text-gray-900">Buat Akun SI-UKM Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Lengkapi data Anda untuk mendaftar sebagai anggota UKM.</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Kolom Kiri: Info Pribadi --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-2">Data Pribadi</h3>
                    <input type="text" name="name" placeholder="Nama Lengkap" required
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">

                    <input type="email" name="email" placeholder="Email Kampus" required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">

                    <input type="tel" name="phone" placeholder="No. HP" required
                        value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                </div>

                {{-- Kolom Kanan: Info Akademik --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-2">Data Akademik</h3>
                    <input type="text" name="nim" placeholder="NIM (Nomor Induk Mahasiswa)" required
                        value="{{ old('nim') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">

                    <input type="text" name="fakultas" placeholder="Fakultas" required
                        value="{{ old('fakultas') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">

                    <input type="text" name="jurusan" placeholder="Jurusan" required
                        value="{{ old('jurusan') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">

                    <input type="number" name="angkatan" placeholder="Angkatan (Contoh: 2023)" required
                        value="{{ old('angkatan') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                </div>
            </div>

            {{-- Bagian Password --}}
            <div class="mt-6 space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-2">Keamanan Akun</h3>
                <input type="password" name="password" placeholder="Buat Password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">

                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>

            <button type="submit"
                class="w-full mt-6 bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition-colors duration-300 focus:outline-none focus:ring-4 focus:ring-green-300">
                Daftar Akun Sekarang
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-600">
                Sudah punya akun? 
                <button type="button" onclick="showLoginModal()" class="text-blue-600 hover:text-blue-700 font-semibold transition">
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
                // Pastikan yang dihapus hanya flash message (berdasarkan warna)
                if (msg.classList.contains('bg-green-500') || msg.classList.contains('bg-red-500')) {
                    msg.remove();
                }
            });
        }, 5000);

        // =================================================================
        // PERBAIKAN: DROPDOWN PROFILE TOGGLE (CLICK)
        // =================================================================
        const profileButton = document.getElementById('profile-menu-button');
        const profileDropdown = document.getElementById('profile-dropdown');
        const profileContainer = document.getElementById('profile-menu-container');

        function toggleProfileDropdown() {
            if (!profileButton || !profileDropdown) return;

            const isHidden = profileDropdown.classList.contains('hidden');

            if (isHidden) {
                // Show dropdown
                profileDropdown.classList.remove('hidden', 'opacity-0', 'translate-y-2');
                profileDropdown.classList.add('opacity-100', 'translate-y-0');
            } else {
                // Hide dropdown
                profileDropdown.classList.remove('opacity-100', 'translate-y-0');
                profileDropdown.classList.add('opacity-0', 'translate-y-2');

                // Beri waktu untuk transisi sebelum menambahkan 'hidden'
                setTimeout(() => {
                    profileDropdown.classList.add('hidden');
                }, 200); 
            }
        }

        if (profileButton) {
            profileButton.addEventListener('click', toggleProfileDropdown);
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!profileContainer) return; // Exit if user is guest

            const isClickInside = profileContainer.contains(event.target);

            // Jika klik terjadi di luar container profil dan dropdown sedang terbuka (tidak hidden)
            if (!isClickInside && !profileDropdown.classList.contains('hidden')) {
                // Close dropdown
                profileDropdown.classList.remove('opacity-100', 'translate-y-0');
                profileDropdown.classList.add('opacity-0', 'translate-y-2');
                
                // Beri waktu untuk transisi sebelum menambahkan 'hidden'
                setTimeout(() => {
                    profileDropdown.classList.add('hidden');
                }, 200); 
            }
        });


        // =================================================================
        // MODAL FUNCTIONS (Login & Register)
        // =================================================================

// =================================================================
// MODAL FUNCTIONS (Login & Register) DENGAN TRANSISI
// =================================================================

function _animateModalIn(modalId, contentId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(contentId);
    if (!modal || !content) return;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Dipaksa reflow
    void content.offsetWidth; 

    // Animasi masuk
    content.classList.remove('scale-95', 'opacity-0');
    content.classList.add('scale-100', 'opacity-100');
}

function _animateModalOut(modalId, contentId, callback = () => {}) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(contentId);
    if (!modal || !content) return;

    // Animasi keluar
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');

    // Tunggu transisi selesai (200ms seperti di CSS)
    setTimeout(() => {
        modal.classList.add('hidden');
        callback();
    }, 300); // 300ms untuk amankan transisi 200ms
}

function checkBodyScroll() {
    // Hanya kembalikan scroll jika kedua modal tertutup
    const loginHidden = document.getElementById('loginModal').classList.contains('hidden');
    const registerHidden = document.getElementById('registerModal').classList.contains('hidden');
    
    if (loginHidden && registerHidden) {
        document.body.style.overflow = 'auto';
    }
}

function showLoginModal() {
    _animateModalIn('loginModal', 'loginModalContent');
}

function closeLoginModal() {
    _animateModalOut('loginModal', 'loginModalContent', checkBodyScroll);
}

function showRegisterModal() {
    // Tutup login modal dulu dengan animasi keluar
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

// Close modal ketika klik outside (diperbarui)
document.getElementById('loginModal').addEventListener('click', function(e) {
    // Pastikan klik terjadi di latar belakang, bukan di konten modal
    const loginContent = document.getElementById('loginModalContent');
    if (!loginContent.contains(e.target)) closeLoginModal();
});

document.getElementById('registerModal').addEventListener('click', function(e) {
    // Pastikan klik terjadi di latar belakang, bukan di konten modal
    const registerContent = document.getElementById('registerModalContent');
    if (!registerContent.contains(e.target)) closeRegisterModal();
});

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
                // Tambahan: tutup juga profile dropdown jika terbuka
                if (profileDropdown && !profileDropdown.classList.contains('hidden')) {
                    toggleProfileDropdown(); 
                }
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