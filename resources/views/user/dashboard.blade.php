@extends('layouts.user')

@section('content')

{{-- 1. HERO SECTION (Banner Utama) - TIDAK ADA PERUBAHAN DARI RESPON SEBELUMNYA --}}
<section class="md:ml-[-34px] md:mr-[-34px] py-10 md:py-16 px-4 md:px-0 flex flex-col md:flex-row justify-between items-center bg-white shadow-md">
    <div class="max-w-7xl mx-auto w-full flex flex-col md:flex-row justify-between items-center md:px-24 lg:px-24">
        <div class="w-full md:w-2/3 mb-8 md:mb-0 text-center md:text-left">
            <h1 class="text-3xl sm:text-4xl md:text-4xl lg:text-4xl font-bold leading-tight text-gray-900 px-4">
                Temukan 
                <span class="text-orange-600">Tempatmu</span>, 
                <span class="text-orange-600">Wujudkan</span> 
                <br class="hidden sm:inline">
                <span class="text-orange-600">Potensimu</span>, 
                Raih Mimpimu!
            </h1>
        </div>
        <div class="w-full md:w-2/3 text-center md:text-left max-w-md px-4">
            <p class="text-base text-gray-600 mb-6 mx-auto md:ml-auto"> 
                Bergabunglah dengan UKM untuk mengasah minat, bakat, 
                dan kepemimpinanmu. Kampus bukan hanya soal kuliah. 
                Tapi juga tentang bertumbuh, bersosial, dan berkarya!
            </p>
            
            @auth
                <a href="{{ route('user.ukm.list') }}" class="inline-block px-12 py-2 bg-gray-800 text-white font-semibold rounded-full hover:bg-gray-700 transition duration-300">
                    Disini!
                </a>
            @else
                <button onclick="showLoginModal()" class="inline-block px-10 py-3 bg-gray-800 text-white font-semibold rounded-full hover:bg-gray-700 transition duration-300">
                    Gabung Sekarang!
                </button>
            @endauth
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-24 py-10">


    {{-- 2. TRENDING EVENT (Mempertahankan 2-per-slide dan layout gambar-teks) --}}
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6 px-4 sm:px-0">
            <h2 class="text-2xl sm:text-3xl font-bold">Trending Event</h2>
      </div>

        <div class="relative">
            <div class="overflow-hidden">
                
                {{-- PERUBAHAN: Mengubah lebar kartu di mobile menjadi 50% dikurangi space --}}
                <div class="flex space-x-4 md:space-x-8 overflow-x-auto pb-4 scrollbar-hide snap-x snap-mandatory" id="events-slider">
                    @foreach($trendingEvents as $event)
                        {{-- Set lebar agar 2 kartu terlihat per view/slide (w-[calc(50%-16px)] di mobile) --}}
                        <div class="flex-shrink-0 w-[calc(50%-8px)] sm:w-[calc(50%-16px)] lg:w-[calc(50%-16px)] snap-start">
                            @auth
                                {{-- PERUBAHAN: Layout card diubah agar selalu row (gambar-teks) --}}
<div class="flex border rounded-lg shadow-lg overflow-hidden bg-white hover:shadow-xl transition-all duration-300 cursor-pointer event-card h-40 sm:h-80 lg:h-96"
    data-event-title="{{ $event->title }}"
    data-event-description="{{ $event->description }}"
    data-event-date="{{ $event->event_date }}"
    data-event-time="{{ $event->event_time }}"
    data-event-location="{{ $event->location }}"
    data-event-poster="{{ $event->poster_image ?? '' }}"
    data-event-ukm="{{ $event->ukm->name }}">
                            @else
                                <div class="flex border rounded-lg shadow-lg overflow-hidden bg-white hover:shadow-xl transition-all duration-300 cursor-pointer event-card-guest h-40 sm:h-80 lg:h-96"
                                    onclick="showLoginModal()">
                            @endauth
                                
                                    {{-- Image/Poster: Di mobile, kasih proporsi 40% agar teks muat --}}
                                    <div class="w-2/4 sm:w-2/4 bg-gray-100 relative flex justify-center items-center">
                                        @if($event->poster_image)
                                            <img src="{{ $event->poster_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                                                <i class="fas fa-calendar-alt text-gray-400 text-2xl sm:text-3xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Detail Event: Di mobile, kasih proporsi 60% --}}
                                    <div class="w-2/4 sm:w-2/4 p-3 sm:p-6 space-y-1 sm:space-y-2 flex flex-col justify-between">
                                        <div>
                                            {{-- Penyesuaian ukuran teks untuk mobile --}}
                                            <h3 class="text-sm sm:text-lg font-semibold line-clamp-2 leading-tight">{{ $event->title }}</h3>
                                            <p class="text-xs text-gray-500 mt-1 hidden sm:block"><i class="far fa-calendar-alt mr-1"></i> {{ $event->event_date->format('d M Y') }} • {{ $event->event_time }}</p>
                                        </div>
                                        
                                        {{-- Batasi deskripsi agar tidak pecah di mobile --}}
                                        <p class="text-sm text-gray-600 line-clamp-3 sm:line-clamp-none">{{ Str::limit($event->description, 290) }}</p>
                                        
                                        <div class="mt-auto pt-1">
                                                                     <div class="flex items-center text-gray-600/90 text-[10px] sm:text-xs">
                                        <i class="fab fa-instagram mr-1 sm:mr-1 text-[15px]"></i>
                                       <p class="text-xs text-gray-600 font-medium">{{ $event->ukm->instagram }}</p>
                                    </div>
                                            
                                            <div class="flex justify-between items-end mt-1">
                                                <span class="text-xs text-gray-400 hidden sm:block">{{ $event->created_at->diffForHumans() }}</span>
    
                                            </div>
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
                <div class="w-2 h-2 rounded-full bg-gray-300 transition-colors duration-300 cursor-pointer carousel-dot {{ $key === 0 ? 'active !bg-gray-600' : '' }}"></div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. TRENDING FEED (Menggunakan grid-cols-2 di mobile) --}}
{{-- MENJADI INI: --}}
<section class="mb-12">
    {{-- Container dengan margin negatif lebih besar --}}
        <div class="lg:mx-[-130px] mx-[-15px] bg-white pt-10 pb-16 shadow-inner md:shadow-none">
            
            {{-- Konten dengan padding normal --}}
            <div class="px-4 sm:px-6 lg:px-24">
                <div class="flex justify-center items-center mb-10">
                    <h2 class="text-2xl sm:text-3xl font-bold">Feed</h2>
                </div>
            </div>
        
        {{-- PERUBAHAN: grid-cols-2 di semua ukuran kecuali desktop (lg:grid-cols-4) --}}
        <div class="lg:mx-[125px]  grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 px-4 sm:px-0">
            @foreach($trendingFeeds as $feed)
            
            @auth
                <div class="bg-white rounded-lg border border-gray-200 hover:shadow-md transition-all duration-300 cursor-pointer feed-card"
                    data-feed-id="{{ $feed->id }}"
                    data-feed-title="{{ $feed->title }}"
                    data-feed-content="{{ $feed->content }}"
                    data-feed-image="{{ $feed->image ?? '' }}"
                    data-feed-ukm="{{ $feed->ukm->name }}"
                    data-feed-created="{{ $feed->created_at->format('d M Y, H:i') }}"
                    data-feed-views="1.2k">
            @else
                <div class="bg-white rounded-lg border border-gray-200 hover:shadow-md transition-all duration-300 cursor-pointer feed-card-guest"
                    onclick="showLoginModal()">
            @endauth

                    <div class="w-full h-40 sm:h-40 lg:h-[220px] bg-gray-200 flex justify-center items-center text-gray-500 rounded-t-lg overflow-hidden">
                        @if($feed->image)
                            <img src="{{ $feed->image }}" alt="{{ $feed->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex justify-center items-center">
                                <span class="text-4xl text-gray-400">×</span>
                                <span class="text-4xl text-gray-400">×</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-3"> 
                        
                        <h3 class="text-sm sm:text-base font-semibold mb-1 line-clamp-1">
                            {{ $feed->title }}
                        </h3>
                        
                        {{-- Batasi content di mobile agar card tidak terlalu panjang --}}
                        <p class="text-xs sm:text-sm text-gray-600 line-clamp-2">
                            {{ Str::limit($feed->content, 65) }}
                        </p>
                        <span class="text-xs text-gray-400 block mt-2">{{ $feed->ukm->name }}</span>
                    </div>
                    
                @auth
                    </div>
                @else
                    </div>
                @endauth
            @endforeach
        </div>

        @if($trendingFeeds->count() == 0)
        <div class="px-4 sm:px-0">
            <div class="text-center py-12 border rounded-lg mt-6 bg-gray-50 col-span-2 lg:col-span-4">
                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 5h8a2 2 0 002-2V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-lg">Belum ada feed terbaru yang diposting.</p>
                @auth
                    <a href="{{ route('user.feeds.index') }}" class="text-orange-600 hover:text-orange-700 font-medium mt-2 inline-block">
                        Jelajahi Feed Lainnya →
                    </a>
                @else
                    <button onclick="showLoginModal()" class="text-orange-600 hover:text-orange-700 font-medium mt-2">
                        Jelajahi Feed Lainnya →
                    </button>
                @endauth
            </div>
        </div>
        @endif
    </div>
</section>


    {{-- 4. UKM POPULER (Menggunakan grid-cols-2 di mobile) --}}
    <section class="mb-12">
        <div class="flex justify-center items-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold">UKM Populer</h2>
        </div>

        {{-- PERUBAHAN: grid-cols-2 di semua ukuran kecuali desktop (lg:grid-cols-3) --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @foreach($ukms as $ukm)
            @auth
                <a href="{{ route('user.ukm.detail', $ukm->id) }}" class="ukm-card block bg-white rounded-xl shadow-lg overflow-hidden transform hover:shadow-2xl hover:scale-[1.02] transition duration-300">
            @else
                <div onclick="showLoginModal()" class="ukm-card bg-white rounded-xl shadow-lg overflow-hidden transform hover:shadow-2xl hover:scale-[1.02] transition duration-300 cursor-pointer">
            @endauth
                
                    <div class="h-28 sm:h-32 lg:h-36 relative bg-gray-100 flex items-center justify-center">
                        {{-- 1. COVER IMAGE/BACKGROUND --}}
                        @if($ukm->logo)
                            <img src="{{ $ukm->logo }}" alt="Cover {{ $ukm->name }}" class="w-full h-full object-cover brightness-75 blur-sm opacity-80">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center">
                                <i class="fas fa-camera text-3xl sm:text-4xl text-gray-500"></i>
                            </div>
                        @endif

                        {{-- Category Badge --}}
                        <div class="absolute top-2 right-2 z-10 ukm-badge transition duration-300">
                            <span class="px-2 py-1 bg-orange-600/90 text-white text-xs font-semibold rounded-full shadow-md">
                                {{ $ukm->category->name ?? 'Umum' }}
                            </span>
                        </div>

                        {{-- 2. CIRCULAR LOGO --}}
                        {{-- Ukuran logo diperkecil agar proporsional di mobile --}}
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 border-3 sm:border-4 border-white rounded-full overflow-hidden bg-white shadow-xl z-20 ukm-logo-container transition-all duration-300">
                            @if($ukm->logo)
                                <img src="{{ $ukm->logo }}" alt="Logo {{ $ukm->name }}" class="w-full h-full object-cover p-1">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <i class="fas fa-users text-lg sm:text-xl text-gray-500"></i>
                                </div>
                            @endif
                        </div>
                    </div>

<div class="pt-10 sm:pt-14 lg:pt-16 pb-4 sm:pb-6 px-3 sm:px-4 text-center">
    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-1 line-clamp-1">{{ $ukm->name }}</h3>
    
    {{-- Deskripsi dengan 2 baris untuk mobile, lebih untuk desktop --}}
    <p class="text-xs sm:text-sm text-gray-500 mb-2 sm:mb-4 line-clamp-2 sm:line-clamp-3">
        {{ Str::limit($ukm->description, 100) }}
    </p>
    
    <div class="flex flex-col items-center justify-center space-y-1 text-sm text-gray-500 mb-3 sm:mb-4 border-t pt-2 sm:pt-3 mt-2 sm:mt-3">
        @if($ukm->instagram)
            <div class="flex items-center text-gray-600/90 text-[10px] sm:text-xs">
                <i class="fab fa-instagram mr-1 sm:mr-1 text-[15px]"></i>
                <span>{{ $ukm->instagram }}</span>
            </div>
        @endif
        
    </div>
</div>

            @auth
                </a>
            @else
                </div>
            @endauth
            @endforeach
        

            @if($ukms->count() == 0)
            <div class="col-span-2 lg:col-span-3 text-center py-12 border rounded-lg bg-gray-50">
                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-gray-500 text-2xl"></i>
                </div>
                <p class="text-gray-600 text-lg">Belum ada UKM terdaftar.</p>
                @auth
                    <a href="{{ route('user.ukm.list') }}" class="text-orange-600 hover:text-orange-700 font-medium mt-2 inline-block">
                        Jelajahi UKM Lainnya →
                    </a>
                @else
                    <button onclick="showLoginModal()" class="text-orange-600 hover:text-orange-700 font-medium mt-2">
                        Jelajahi UKM Lainnya →
                    </button>
                @endauth
            </div>
            @endif
    </section> 
</div>


    

   {{-- INCLUDE MODAL TEMPLATE --}}
    @include('layouts.partials.modals-dashboard')

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
    {{-- INCLUDE FUNGSI MODAL UNIVERSAL --}}
    @include('layouts.partials.scripts-modal-universal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inisialisasi modal
            if (window.ModalFunctions) {
                ModalFunctions.initAllModals();
                ModalFunctions.bindEventCardsToModal();
                ModalFunctions.bindFeedCardsToModal();
            }

            // 2. Handle guest clicks
            document.querySelectorAll('.event-card-guest, .feed-card-guest, .ukm-card[onclick]').forEach(card => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (typeof window.showLoginModal === 'function') {
                        window.showLoginModal();
                    }
                });
            });

            // 3. Carousel functionality (khusus dashboard)
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
        });
    </script>
@endsection