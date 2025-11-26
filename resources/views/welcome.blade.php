@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-gray-800 mb-6">SISTEM INFORMASI UKM</h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Platform digital untuk mengelola Unit Kegiatan Mahasiswa di kampus Anda. 
                Daftar, kelola, dan eksplor berbagai UKM yang tersedia.
            </p>
            
            <div class="flex justify-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        <i class="fas fa-tachometer-alt mr-2"></i>Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        <i class="fas fa-user-plus mr-2"></i>Register
                    </a>
                @endauth
            </div>
        </div>

        <!-- Features -->
        <div class="mt-20 grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <i class="fas fa-users text-4xl text-blue-600 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Kelola UKM</h3>
                <p class="text-gray-600">Admin dan staff dapat mengelola data UKM dengan mudah</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <i class="fas fa-calendar-alt text-4xl text-green-600 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Event & Kegiatan</h3>
                <p class="text-gray-600">Publikasikan event dan kegiatan UKM kepada mahasiswa</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <i class="fas fa-user-graduate text-4xl text-purple-600 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Pendaftaran Anggota</h3>
                <p class="text-gray-600">Proses pendaftaran anggota UKM yang terdigitalisasi</p>
            </div>
        </div>
    </div>
</div>
@endsection