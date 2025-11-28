@extends('layouts.user')

@section('content')
<div class="px-4 sm:px-6 lg:px-24 py-10">
    <section class="mb-12 flex justify-between items-center">
        <div class="w-1/2">
            <h1 class="text-5xl font-bold leading-tight text-gray-900">
                Temukan Tempatmu, Wujudkan Potensimu, Raih Mimpimu!
            </h1>
        </div>
        <div class="w-1/2 text-right">
            <p class="text-lg text-gray-600 mb-6 ml-auto max-w-sm">
                Bergabunglah dengan UKM untuk mengasah minat, bakat, dan kepemimpinanmu. Kampus bukan hanya soal kuliah. Tapi juga tentang bertumbuh, bersosial, dan berkarya!
            </p>
            @auth
                <a href="{{ route('user.ukm.list') }}" class="inline-block px-6 py-3 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-700 transition duration-300">
                    Jelajahi UKM
                </a>
            @else
                <button onclick="showLoginModal()" class="inline-block px-6 py-3 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-700 transition duration-300">
                    Jelajahi UKM
                </button>
            @endauth
        </div>
    </section>

    <hr class="my-10">

    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Trending Event</h2>
            @auth
                <a href="{{ route('user.events.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">Lihat Semua →</a>
            @else
                <button onclick="showLoginModal()" class="text-blue-600 hover:text-blue-700 font-medium">Lihat Semua →</button>
            @endauth
        </div>

        <div class="relative">
            <div class="overflow-hidden">
                <div class="flex space-x-8 overflow-x-auto pb-4 scrollbar-hide" id="events-slider">
                    @foreach($trendingEvents as $event)
                    <div class="flex-shrink-0 w-1/2">
                        @auth
                            <div class="flex border rounded-lg shadow-md overflow-hidden bg-white hover:shadow-lg transition-all duration-300 cursor-pointer event-card"
                                data-event-id="{{ $event->id }}"
                                data-event-title="{{ $event->title }}"
                                data-event-date="{{ $event->event_date->format('d M Y') }}"
                                data-event-time="{{ $event->event_time }}"
                                data-event-location="{{ $event->location }}"
                                data-event-description="{{ $event->description }}"
                                data-event-ukm="{{ $event->ukm->name }}"
                                data-event-poster="{{ $event->poster_image ?? '' }}"
                                data-event-created="{{ $event->created_at->diffForHumans() }}">
                        @else
                            <div class="flex border rounded-lg shadow-md overflow-hidden bg-white hover:shadow-lg transition-all duration-300 cursor-pointer event-card-guest"
                                onclick="showLoginModal()">
                        @endauth
                            
                            <div class="w-2/5 bg-gray-100 relative flex justify-center items-center">
                                @if($event->poster_image)
                                    <img src="{{ $event->poster_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="w-3/5 p-6 space-y-3">
                                <h3 class="text-2xl font-semibold line-clamp-2">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $event->event_date->format('d M Y') }} • {{ $event->event_time }}</p>
                                <p class="text-sm text-gray-600 line-clamp-3">{{ Str::limit($event->description, 120) }}</p>
                                <p class="text-sm text-blue-600 mt-2">{{ $event->ukm->name }}</p>
                                <div class="flex justify-between items-center mt-4">
                                    <span class="text-xs text-gray-500">{{ $event->created_at->diffForHumans() }}</span>
                                    <span class="text-blue-600 text-sm font-medium">
                                        @auth
                                            Klik untuk detail →
                                        @else
                                            Login untuk detail →
                                        @endauth
                                    </span>
                                </div>
                            </div>
                        @auth
                            </div>
                        @else
                            </div>
                        @endauth
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="flex justify-center mt-6 space-x-2">
                @foreach($trendingEvents as $key => $event)
                <div class="carousel-dot {{ $key === 0 ? 'active' : '' }}"></div>
                @endforeach
            </div>
        </div>
    </section>
    <hr class="my-10">

    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Feed</h2>
            @auth
                <a href="{{ route('user.feeds.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">Lihat Semua →</a>
            @else
                <button onclick="showLoginModal()" class="text-blue-600 hover:text-blue-700 font-medium">Lihat Semua →</button>
            @endauth
        </div>

        <div class="grid grid-cols-4 gap-6">
            @foreach($trendingFeeds as $feed)
            @auth
                <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-all duration-300 cursor-pointer feed-card"
                    data-feed-id="{{ $feed->id }}"
                    data-feed-title="{{ $feed->title }}"
                    data-feed-content="{{ $feed->content }}"
                    data-feed-image="{{ $feed->image ?? '' }}"
                    data-feed-ukm="{{ $feed->ukm->name }}"
                    data-feed-created="{{ $feed->created_at->format('d M Y, H:i') }}"
                    data-feed-views="1.2k">
            @else
                <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-all duration-300 cursor-pointer feed-card-guest"
                    onclick="showLoginModal()">
            @endauth
                
                <div class="w-full h-48 bg-gray-200 flex justify-center items-center text-gray-500 mb-4 rounded overflow-hidden">
                    @if($feed->image)
                        <img src="{{ $feed->image }}" alt="{{ $feed->title }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 5h8a2 2 0 002-2V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    @endif
                </div>
                
                <div class="flex items-center mb-2">
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium mr-2">
                        {{ $feed->ukm->name }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $feed->created_at->diffForHumans() }}</span>
                </div>
                
                <h3 class="text-lg font-semibold mb-2 line-clamp-1">{{ $feed->title }}</h3>
                <p class="text-sm text-gray-600 line-clamp-3">
                    {{ Str::limit($feed->content, 100) }}
                </p>
                
                <div class="flex justify-between items-center mt-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="far fa-eye mr-1"></i>
                        <span>1.2k dilihat</span>
                    </div>
                    <span class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center cursor-pointer">
                        @auth
                            Baca Selengkapnya 
                        @else
                            Login untuk baca →
                        @endauth
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </span>
                </div>
            @auth
                </div>
            @else
                </div>
            @endauth
            @endforeach
        </div>

        @if($trendingFeeds->count() == 0)
        <div class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 5h8a2 2 0 002-2V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <p class="text-gray-600 text-lg">Belum ada feed trending</p>
            @auth
                <a href="{{ route('user.feeds.index') }}" class="text-blue-600 hover:text-blue-700 font-medium mt-2 inline-block">
                    Jelajahi Feed Lainnya →
                </a>
            @else
                <button onclick="showLoginModal()" class="text-blue-600 hover:text-blue-700 font-medium mt-2">
                    Jelajahi Feed Lainnya →
                </button>
            @endauth
        </div>
        @endif
    </section>

    <hr class="my-10">

    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">UKM Terpopuler</h2>
            @auth
                <a href="{{ route('user.ukm.list') }}" class="text-blue-600 hover:text-blue-700 font-medium">Lihat Semua UKM →</a>
            @else
                <button onclick="showLoginModal()" class="text-blue-600 hover:text-blue-700 font-medium">Lihat Semua UKM →</button>
            @endauth
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($ukms as $ukm)
            @auth
                <a href="{{ route('user.ukm.detail', $ukm->id) }}" class="ukm-card block bg-white rounded-xl shadow-lg overflow-hidden transform hover:shadow-2xl hover:scale-[1.02] transition duration-300">
            @else
                <div onclick="showLoginModal()" class="ukm-card bg-white rounded-xl shadow-lg overflow-hidden transform hover:shadow-2xl hover:scale-[1.02] transition duration-300 cursor-pointer">
            @endauth
                
                    <div class="h-36 relative bg-gray-100 flex items-center justify-center">
                        {{-- 1. COVER IMAGE/BACKGROUND --}}
                        @if($ukm->logo)
                            {{-- Menggunakan logo sebagai cover, pastikan ada properti 'cover_image' jika memang berbeda --}}
                            <img src="{{ $ukm->logo }}" alt="Cover {{ $ukm->name }}" class="w-full h-full object-cover brightness-75 blur-sm opacity-80">
                        @else
                            {{-- Placeholder jika tidak ada cover image --}}
                            <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center">
                                <i class="fas fa-camera text-4xl text-gray-500"></i>
                            </div>
                        @endif

                        {{-- Category Badge --}}
                        <div class="absolute top-3 right-3 z-10 ukm-badge transition duration-300">
                            <span class="px-3 py-1 bg-blue-600/90 text-white text-xs font-semibold rounded-full shadow-md">
                                {{ $ukm->category->name ?? 'Umum' }}
                            </span>
                        </div>

                        {{-- 2. CIRCULAR LOGO --}}
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-24 h-24 border-4 border-white rounded-full overflow-hidden bg-white shadow-xl z-20 ukm-logo-container transition-all duration-300">
                            @if($ukm->logo)
                                <img src="{{ $ukm->logo }}" alt="Logo {{ $ukm->name }}" class="w-full h-full object-cover p-1">
                            @else
                                {{-- Placeholder jika tidak ada logo --}}
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <i class="fas fa-users text-2xl text-gray-500"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Detail Content Section --}}
                    <div class="pt-16 pb-6 px-4 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-1 line-clamp-1">{{ $ukm->name }}</h3>
                        <p class="text-sm text-gray-500 mb-4 h-10 overflow-hidden line-clamp-2">{{ Str::limit($ukm->description, 80) }}</p>
                        
                        <div class="flex flex-col items-center justify-center space-y-1 text-sm text-gray-500 mb-4 border-t pt-3 mt-3">
                            @php
                                // Asumsi relasi registrations()->where('status', 'approved') tersedia
                                $memberCount = $ukm->registrations()->where('status', 'approved')->count() ?? ($ukm->approved_members_count ?? $ukm->members_count ?? 0);
                            @endphp
                            
                            {{-- Member Count --}}
                            <div class="flex items-center text-gray-700 font-semibold">
                                <i class="fas fa-user-friends mr-2 text-xs text-blue-500"></i>
                                <span class="text-sm">{{ $memberCount }} Anggota Aktif</span>
                            </div>

                            @if($ukm->contact_person)
                                <div class="flex items-center text-blue-600/90 text-xs">
                                    <i class="fas fa-phone-alt mr-2 text-xs"></i>
                                    <span>{{ $ukm->contact_person }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4">
                            <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-lg">
                                <i class="fas fa-info-circle mr-2"></i> Lihat Detail
                            </span>
                        </div>
                    </div>

            @auth
                </a>
            @else
                </div>
            @endauth
            @endforeach
        </div>

        @if($ukms->count() == 0)
        <div class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-600 text-lg">Belum ada UKM terdaftar</p>
            @auth
                <a href="{{ route('user.ukm.list') }}" class="text-blue-600 hover:text-blue-700 font-medium mt-2 inline-block">
                    Jelajahi UKM Lainnya →
                </a>
            @else
                <button onclick="showLoginModal()" class="text-blue-600 hover:text-blue-700 font-medium mt-2">
                    Jelajahi UKM Lainnya →
                </button>
            @endauth
        </div>
        @endif
    </section>

    {{-- MODALS TETAP DI SINI --}}
    {{-- START NEW ASYMMETRIC EVENT DETAIL MODAL --}}
    <dialog id="eventModal" 
        class="bg-white rounded-2xl shadow-2xl p-0 w-full max-w-5xl max-h-[90vh] 
            backdrop:bg-black/40 backdrop:backdrop-blur-sm 
            transform transition-all duration-300 opacity-0 scale-95 flex flex-col overflow-hidden">
        
        <div class="relative flex h-full">
            
            {{-- KOLOM KIRI: Judul, Meta, dan Konten Teks --}}
            <div class="w-7/12 flex-shrink-0 p-8 overflow-y-auto max-h-[90vh] pb-20">

                {{-- Header/Judul --}}
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6 leading-tight" id="modalTitle">Judul Event</h1>
                
                {{-- UKM & Meta Info Block --}}
                <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-3">
                    
                    {{-- UKM Info / Organizer --}}
                    <div class="flex items-center text-base font-semibold text-gray-700">
                        <i class="fas fa-users mr-3 text-blue-600 w-4"></i>
                        <span id="modalUkm" class="font-bold text-blue-600">Nama UKM (Organizer)</span>
                    </div>
                    
                    {{-- Tanggal, Waktu, Lokasi --}}
                    <div class="space-y-3 pt-2 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-3 text-blue-500 w-4"></i>
                            <span id="modalDate">Tanggal Event</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock mr-3 text-green-500 w-4"></i>
                            <span id="modalTime">Waktu Event</span>
                        </div>

                        <div class="flex items-start text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-3 text-red-500 mt-1 w-4"></i>
                            <span id="modalLocation" class="font-medium">Lokasi Event</span>
                        </div>
                    </div>
                </div>
                
                {{-- Konten Utama (Deskripsi) --}}
                <div class="prose max-w-none text-gray-700 leading-relaxed text-base">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi Kegiatan</h2>
                    <p id="modalDescription" class="whitespace-pre-line">
                        {{-- Deskripsi event akan masuk di sini --}}
                    </p>
                </div>
                
            </div>
            
            {{-- KOLOM KANAN: Gambar Poster, Tombol Close, dan Tombol Daftar --}}
            <div class="w-5/12 flex-shrink-0 bg-gray-100 relative rounded-r-2xl overflow-hidden shadow-inner flex items-center justify-center">
                
                {{-- Container Gambar Poster dengan object-cover --}}
                <div id="modalPoster" class="w-full h-full">
                    {{-- Poster akan masuk di sini --}}
                </div>
                
                {{-- Tombol Close di atas gambar, posisi elegan --}}
                <button id="closeEventModal"
                    class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/70 backdrop-blur-sm hover:bg-white flex items-center justify-center transition-colors shadow-lg border border-gray-100">
                    <i class="fas fa-times text-gray-600 text-lg"></i>
                </button>
                
                {{-- Tombol Daftar/Tutup di Bawah (Fixed) --}}
                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/50 to-transparent flex justify-center">
                    <button id="closeEventModalBtn"
                            class="px-8 py-2.5 bg-white/90 text-gray-800 rounded-full hover:bg-white font-medium transition-colors shadow-xl">
                        <i class="fas fa-chevron-circle-up rotate-180 mr-2 text-sm"></i> Tutup Detail
                    </button>
                </div>
            </div>
        </div>
    </dialog>
    {{-- END NEW ASYMMETRIC EVENT DETAIL MODAL --}}

    {{-- START NEW ASYMMETRIC FEED DETAIL MODAL --}}
    <dialog id="feedModal" 
        class="bg-white rounded-2xl shadow-2xl p-0 w-full max-w-5xl max-h-[90vh] 
            backdrop:bg-black/40 backdrop:backdrop-blur-sm 
            transform transition-all duration-300 opacity-0 scale-95 flex flex-col overflow-hidden">
        
        <div class="relative flex h-full">
            
            {{-- KOLOM KIRI: Judul, Meta, dan Konten Teks --}}
            <div class="w-7/12 flex-shrink-0 p-8 overflow-y-auto max-h-[90vh] pb-20">

                {{-- Header/Judul --}}
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4 leading-tight" id="feedModalTitle">Judul Feed</h1>
                
                {{-- Meta Info Block --}}
                <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-3">
                    
                    <div class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-users mr-3 text-blue-600 w-4"></i>
                        <span id="feedModalUkm" class="font-bold text-blue-600">Nama UKM</span>
                    </div>
                    
                    <div class="space-y-3 pt-2 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-3 text-blue-500 w-4"></i>
                            <span id="feedModalDate">Tanggal Posting</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="far fa-eye mr-3 text-red-500 w-4"></i>
                            <span id="feedModalViews">Jumlah Dilihat</span>
                        </div>
                    </div>
                </div>
                
                {{-- Konten Utama --}}
                <div class="prose max-w-none text-gray-700 leading-relaxed text-base">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Isi Konten</h2>
                    <p id="feedModalContent" class="whitespace-pre-line">
                        {{-- Isi Feed akan masuk di sini --}}
                    </p>
                </div>
                
            </div>
            
            {{-- KOLOM KANAN: Gambar Feed, Tombol Close, dan Tombol Tutup --}}
            <div class="w-5/12 flex-shrink-0 bg-gray-100 relative rounded-r-2xl overflow-hidden shadow-inner flex items-center justify-center">
                
                {{-- Container Gambar Feed dengan object-cover --}}
                <div id="feedModalImage" class="w-full h-full">
                    {{-- Gambar akan masuk di sini --}}
                </div>
                
                {{-- Tombol Close di atas gambar, posisi elegan --}}
                <button id="closeFeedModal"
                    class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/70 backdrop-blur-sm hover:bg-white flex items-center justify-center transition-colors shadow-lg border border-gray-100">
                    <i class="fas fa-times text-gray-600 text-lg"></i>
                </button>
                
                {{-- Tombol Tutup di Bawah (Fixed) --}}
                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/50 to-transparent flex justify-center">
                    <button id="closeFeedModalBtn"
                            class="px-8 py-2.5 bg-white/90 text-gray-800 rounded-full hover:bg-white font-medium transition-colors shadow-xl">
                        <i class="fas fa-chevron-circle-up rotate-180 mr-2 text-sm"></i> Tutup Detail
                    </button>
                </div>
            </div>
        </div>
    </dialog>
    {{-- END NEW ASYMMETRIC FEED DETAIL MODAL --}}
    

</div>
<style>
    .carousel-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #d1d5db;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .carousel-dot.active {
        background-color: #3b82f6;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection

@section('scripts')
<script>
    // FUNGSI UTAMA UNTUK HANDLE KLIK PADA DASHBOARD
    document.addEventListener('DOMContentLoaded', function() {
        // Handle klik event card untuk guest
        document.querySelectorAll('.event-card-guest').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                // PANGGIL FUNGSI DARI LAYOUT
                if (typeof window.showLoginModal === 'function') {
                    window.showLoginModal();
                }
            });
        });

        // Handle klik feed card untuk guest  
        document.querySelectorAll('.feed-card-guest').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                // PANGGIL FUNGSI DARI LAYOUT
                if (typeof window.showLoginModal === 'function') {
                    window.showLoginModal();
                }
            });
        });

        // Handle klik UKM card untuk guest
        document.querySelectorAll('.ukm-card[onclick]').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                // PANGGIL FUNGSI DARI LAYOUT
                if (typeof window.showLoginModal === 'function') {
                    window.showLoginModal();
                }
            });
        });

        // Handle tombol "Lihat Semua" untuk guest
        document.querySelectorAll('button[onclick="showLoginModal()"]').forEach(button => {
            if (!button.closest('.flex.justify-between')) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    // PANGGIL FUNGSI DARI LAYOUT
                    if (typeof window.showLoginModal === 'function') {
                        window.showLoginModal();
                    }
                });
            }
        });

        // Slider functionality
        const slider = document.getElementById('events-slider');
        const dots = document.querySelectorAll('.carousel-dot');
        
        if (slider && dots.length > 0) {
            const scrollAmount = 536; 
            let currentSlide = 0;
            
            function updateDots() {
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            }
            
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    slider.scrollTo({ left: index * scrollAmount, behavior: 'smooth' });
                    updateDots();
                });
            });
            
            // Auto slide
            const slideInterval = setInterval(() => {
                currentSlide = (currentSlide + 1) % dots.length;
                slider.scrollTo({ left: currentSlide * scrollAmount, behavior: 'smooth' });
                updateDots();
            }, 5000);

            slider.addEventListener('scroll', () => {
                clearInterval(slideInterval);
            });
        }

        // =================================================================
        // EVENT DAN FEED MODAL HANDLING (UNTUK USER YANG SUDAH LOGIN)
        // =================================================================
        
        // Get modal elements
        const eventModal = document.getElementById('eventModal');
        const feedModal = document.getElementById('feedModal');

        // FUNCTION UTILITY MODAL
        function openDialog(dialogElement) {
            dialogElement.classList.remove('hidden'); 
            dialogElement.showModal();
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                dialogElement.classList.add('opacity-100', 'scale-100');
                dialogElement.classList.remove('opacity-0', 'scale-95');
            }, 10);
        }

        function closeDialog(dialogElement) {
            if (!dialogElement.hasAttribute('open')) return;
            
            dialogElement.classList.remove('opacity-100', 'scale-100');
            dialogElement.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                dialogElement.close();
                document.body.style.overflow = '';
            }, 300);
        }

        // --- KLIK EVENT CARD (UNTUK USER YANG SUDAH LOGIN) ---
        document.querySelectorAll('.event-card').forEach(card => {
            card.addEventListener('click', function() {
                const eventTitle = this.getAttribute('data-event-title');
                const eventDate = this.getAttribute('data-event-date');
                const eventTime = this.getAttribute('data-event-time');
                const eventLocation = this.getAttribute('data-event-location');
                const eventDescription = this.getAttribute('data-event-description');
                const eventUkm = this.getAttribute('data-event-ukm');
                const eventPoster = this.getAttribute('data-event-poster');
                
                document.getElementById('modalTitle').textContent = eventTitle;
                document.getElementById('modalDate').textContent = eventDate;
                document.getElementById('modalTime').textContent = eventTime;
                document.getElementById('modalLocation').textContent = eventLocation;
                document.getElementById('modalDescription').textContent = eventDescription;
                document.getElementById('modalUkm').textContent = eventUkm;
                
                const posterContainer = document.getElementById('modalPoster');
                posterContainer.innerHTML = '';
                if (eventPoster) {
                    posterContainer.innerHTML = `<img src="${eventPoster}" alt="${eventTitle}">`;
                } else {
                    posterContainer.innerHTML = `
                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-5xl"></i>
                        </div>
                    `;
                }
                
                openDialog(eventModal);
            });
        });

        // --- KLIK FEED CARD (UNTUK USER YANG SUDAH LOGIN) ---
        document.querySelectorAll('.feed-card').forEach(card => {
            card.addEventListener('click', function() {
                const feedTitle = this.getAttribute('data-feed-title');
                const feedContent = this.getAttribute('data-feed-content');
                const feedImage = this.getAttribute('data-feed-image');
                const feedUkm = this.getAttribute('data-feed-ukm');
                const feedDate = this.getAttribute('data-feed-created');
                const feedViews = this.getAttribute('data-feed-views');
                
                document.getElementById('feedModalTitle').textContent = feedTitle;
                document.getElementById('feedModalContent').textContent = feedContent; 
                document.getElementById('feedModalUkm').textContent = feedUkm;
                document.getElementById('feedModalDate').textContent = feedDate;
                document.getElementById('feedModalViews').textContent = feedViews + ' dilihat';
                
                const imageContainer = document.getElementById('feedModalImage');
                imageContainer.innerHTML = '';
                if (feedImage) {
                    imageContainer.innerHTML = `<img src="${feedImage}" alt="${feedTitle}">`;
                } else {
                    imageContainer.innerHTML = `
                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 5h8a2 2 0 002-2V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    `;
                }
                
                openDialog(feedModal);
            });
        });

        // --- TUTUP MODAL EVENT DAN FEED ---
        if (eventModal) {
            document.getElementById('closeEventModal').addEventListener('click', () => closeDialog(eventModal));
            document.getElementById('closeEventModalBtn').addEventListener('click', () => closeDialog(eventModal));
            eventModal.addEventListener('click', function(e) {
                if (e.target === eventModal) closeDialog(eventModal);
            });
        }

        if (feedModal) {
            document.getElementById('closeFeedModal').addEventListener('click', () => closeDialog(feedModal));
            document.getElementById('closeFeedModalBtn').addEventListener('click', () => closeDialog(feedModal));
            feedModal.addEventListener('click', function(e) {
                if (e.target === feedModal) closeDialog(feedModal);
            });
        }

        // Close modal dengan Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (eventModal && eventModal.hasAttribute('open')) closeDialog(eventModal);
                if (feedModal && feedModal.hasAttribute('open')) closeDialog(feedModal);
            }
        });
    });


</script>
@endsection