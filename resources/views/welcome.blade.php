@extends('layouts.user')

@section('content')
<div class="w-full bg-gradient-to-r from-white to-gray-50">
    
    {{-- Container Utama yang Menghitung Tinggi Tersisa --}}
    <div 
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center ml-[5%]" 
        style="min-height: calc(100vh - 130px);" {{-- 64px adalah tinggi Navbar (h-16) --}}
    >
        <div class="flex flex-col lg:flex-row items-center justify-between w-full py-16 lg:py-0">
            <div class="lg:w-1/2 text-center lg:text-left mb-8 lg:mb-0 lg:pr-12">
                <h1 class="text-5xl md:text-6xl lg:text-5xl font-extrabold mb-2 leading-tight">
                    <span class="text-orange-600">SIUKM</span>
                </h1>
                <h2 class="text-3xl md:text-4xl lg:text-4xl font-semibold text-gray-800 mb-6 leading-tight">
                    Sistem Informasi<br>Unit Kegiatan Mahasiswa
                </h2>
                <p class="text-xl md:text-sm text-gray-600 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Jelajahi beragam UKM yang sesuai dengan minat dan bakatmu, daftar dengan mudah, dan nikmati pengalaman kampus yang lebih bermakna.
                </p>
                
                @auth
                    <a href="{{ route('home') }}" class="px-10 py-4 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-700 transition duration-300 inline-flex items-center text-lg">
                        <i class="fas fa-tachometer-alt mr-3"></i>Lihat Dashboard
                    </a>
                @else
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('home') }}" class="px-10 py-4 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-700 transition duration-300 inline-flex items-center text-lg">
                            <i class="fas fa-rocket mr-3"></i>Jelajahi UKM
                        </a>
                    </div>
                @endauth
            </div>

            <div class="lg:w-1/2 relative flex justify-center lg:justify-end mt-8 lg:mt-0">
                <div class="w-full max-w-xl">
                    <img 
                        src="{{ asset('img/welcome.png') }}" 
                        alt="Ilustrasi SIUKM" 
                        class="w-full h-auto max-h-[400px] object-contain transform hover:scale-105 transition duration-300"
                        onerror="this.style.display='none'; document.getElementById('fallback-illustration').style.display='block';"
                    >
                    
                    <div id="fallback-illustration" class="hidden relative w-full max-w-xl h-80 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-6xl text-gray-400 mb-4">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="text-gray-500 text-lg">Ilustrasi SIUKM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transform {
        transition: all 0.3s ease;
    }
    
    /* Ganti h-screen dan min-h-screen bawaan karena kita pakai calc() */
    /* .min-h-screen dan .h-screen tidak diperlukan di sini lagi */
</style>
@endsection