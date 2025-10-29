@extends('layouts.app')
@section('title', 'Daftar UKM')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

{{-- 🎨 Background Parallax --}}
<div class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-blue-100 min-h-screen">
  <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1470&q=80')] bg-cover bg-center opacity-10"></div>
  
  {{-- 💫 Hero Section --}}
  <div class="relative text-center py-16 px-6 md:px-12">
    <div class="max-w-3xl mx-auto">
      <h1 class="text-5xl md:text-6xl font-extrabold text-indigo-700 tracking-tight mb-3 animate-fadeInDown">
        Jelajahi UKM Eka Nanda Susila
      </h1>
      <p class="text-gray-600 text-lg md:text-xl animate-fadeInUp">
        Pilih UKM yang sesuai dengan passion-mu, jadilah bagian dari komunitas yang menginspirasi.
      </p>
    </div>
  </div>

  {{-- 🌟 Grid UKM Cards --}}
  <div class="relative max-w-7xl mx-auto px-6 pb-20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
    @forelse($ukms as $ukm)
      {{-- ✨ Bungkus seluruh card dalam <a> agar bisa diklik --}}
      <a href="{{ route('ukm.show', $ukm->id) }}"
        class="group block bg-white/60 backdrop-blur-xl border border-white/40 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:rotate-1 relative overflow-hidden">
        
        {{-- ✨ Gambar UKM --}}
        <div class="relative overflow-hidden">
          @if($ukm->logo)
            <img src="{{ asset('storage/'.$ukm->logo) }}" class="w-full h-56 object-cover transition-transform duration-700 group-hover:scale-110">
          @else
            <img src="https://via.placeholder.com/400x200?text=No+Logo" class="w-full h-56 object-cover transition-transform duration-700 group-hover:scale-110">
          @endif
          <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-80 transition-opacity duration-500"></div>
        </div>

        {{-- 🌱 Konten UKM --}}
        <div class="p-6 flex flex-col h-full relative z-10">
          <h2 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-indigo-700 transition-colors duration-300">
            {{ $ukm->nama }}
          </h2>
          <span class="inline-block bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded-full mb-3 font-medium">
            {{ $ukm->kategori ?? 'Umum' }}
          </span>

          <p class="text-gray-600 text-sm leading-relaxed flex-grow mb-4">
            {{ Str::limit($ukm->deskripsi, 100) }}
          </p>

          {{-- 🌈 Tombol Detail (visual saja, tidak perlu klik karena card sudah klik-able) --}}
          <div class="mt-auto">
            <div
              class="inline-flex items-center gap-2 text-indigo-600 font-semibold text-sm group-hover:text-blue-600 transition-all duration-300">
              <span>Lihat Detail</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>
        </div>

        {{-- ✨ Glow Effect di Hover --}}
        <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500 bg-gradient-to-r from-indigo-200/20 to-blue-200/10 blur-2xl"></div>
      </a>
    @empty
      <div class="col-span-3 text-center text-gray-500 mt-20">
        <p class="text-lg">Belum ada data UKM yang tersedia saat ini.</p>
      </div>
    @endforelse
  </div>
</div>

{{-- 🌟 Animasi Halus --}}
<style>
  @keyframes fadeInUp {
    0% {opacity: 0; transform: translateY(20px);}
    100% {opacity: 1; transform: translateY(0);}
  }
  @keyframes fadeInDown {
    0% {opacity: 0; transform: translateY(-20px);}
    100% {opacity: 1; transform: translateY(0);}
  }
  .animate-fadeInUp {animation: fadeInUp 1s ease-out;}
  .animate-fadeInDown {animation: fadeInDown 1s ease-out;}
</style>
@endsection
