    <!-- Login Modal - PERBAIKAN -->
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

            <!-- Error Alert untuk Login -->
            <div id="loginErrorAlert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 hidden">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="loginErrorMessage" class="font-medium"></span>
                </div>
            </div>

            <form id="loginForm" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <input type="email" name="email" id="loginEmail" placeholder="Email Kampus" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <span id="loginEmailError" class="error-message text-red-500 text-xs mt-1 block"></span>
                    </div>
                    
                    <!-- TAMBAH TOMBOL MATA DI SINI -->
                    <div class="relative">
                        <input type="password" name="password" id="loginPassword" placeholder="Password" required
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <!-- Tombol Mata -->
                        <button type="button" id="loginPasswordToggle"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button>
                        <span id="loginPasswordError" class="error-message text-red-500 text-xs mt-1 block"></span>
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

    <!-- Bagian Password REGISTER MODAL -->
    <div class="mt-6 space-y-4 border-t pt-4">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-2">Keamanan Akun</h3>

        <!-- Password dengan tombol mata -->
        <div class="relative">
            <input type="password" name="password" id="registerPassword" placeholder="Buat Password"
                required
                class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                    {{ $errors->has('password') && session('register_errors') ? 'border-red-500' : '' }}">
            <!-- Tombol Mata -->
            <button type="button" id="registerPasswordToggle"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-eye"></i>
            </button>
            @if($errors->has('password') && session('register_errors'))
                <p class="text-red-500 text-xs mt-1">{{ $errors->first('password') }}</p>
            @endif
            <p class="text-gray-500 text-xs mt-1">
                Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, angka, dan simbol (@$!%*?&)
            </p>
        </div>

        <!-- Confirm Password dengan tombol mata -->
        <div class="relative">
            <input type="password" name="password_confirmation" id="registerPasswordConfirmation"
                placeholder="Konfirmasi Password" required
                class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors
                    {{ $errors->has('password_confirmation') && session('register_errors') ? 'border-red-500' : '' }}">
            <!-- Tombol Mata -->
            <button type="button" id="registerPasswordConfirmationToggle"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-eye"></i>
            </button>
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
<button type="button" id="switchToLoginFromRegister"
    class="text-blue-600 hover:text-blue-700 font-semibold transition">
    Login di sini
</button>
                </p>
            </div>
        </div>
    </div>