@extends('layouts.user')

@section('content')
<section class="relative bg-cover bg-center text-white text-center shadow-lg mb-8 h-64 overflow-hidden" 
         style="background-image: url('/img/header.png');">
    


<div class="relative z-10 h-full flex flex-col justify-center">

<h1 class="mt-16 text-3xl tracking-wider">Feeds</h1>

<p class="mt-2 text-sm text-gray-200">Berita & Informasi Terbaru</p>

</div>


</section>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 ">
    <div class="">
        {{-- Tombol Filter dan Grid Feeds --}}
<div class="flex flex-wrap justify-center gap-2 mb-8 border-b pb-4 overflow-x-auto whitespace-nowrap scrollbar-hide">
    <button class="px-4 py-2 text-sm font-medium rounded-full bg-gray-900 text-white shadow-md flex-shrink-0 category-filter active" data-category="all">
        SEMUA UKM
    </button>
    @foreach($ukms as $ukmItem)
    <button class="px-4 py-2 text-sm font-medium rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 flex-shrink-0 category-filter" data-category="{{ $ukmItem->id }}">
        {{ strtoupper($ukmItem->name) }}
    </button>
    @endforeach
</div>
        
        {{-- TELAH DIKOREKSI: Mengubah grid-cols-1 sm:grid-cols-2 menjadi grid-cols-2 --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6" id="feeds-grid">
            @foreach($feeds as $feed)
            <div class="bg-white rounded-md shadow-md overflow-hidden transform hover:scale-[1.02] transition duration-300 feed-card" data-ukm="{{ $feed->ukm->id }}">
                <div class="md:h-60 h-44 bg-gray-200 flex items-center justify-center text-gray-400 overflow-hidden relative">
                    @if($feed->image)
                        <img src="{{ $feed->image }}" alt="{{ $feed->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-4xl"></i>
                        </div>
                    @endif
                    
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-xs rounded-full font-semibold shadow-sm">
                            {{ $feed->ukm->name }}
                        </span>
                    </div>
                    
                    <div class="absolute top-3 left-3">
                        <div class="bg-black/70 text-white text-xs rounded-lg px-2 py-1">
                            {{ $feed->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="flex items-center mb-3">
                        @if($feed->ukm->logo)
                            <img src="{{ $feed->ukm->logo }}" alt="{{ $feed->ukm->name }}" class="w-6 h-6 rounded-full object-cover mr-2">
                        @else
                            <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-users text-gray-400 text-xs"></i>
                            </div>
                        @endif
                        <span class="font-semibold text-sm text-gray-700">{{ $feed->ukm->name }}</span>
                        <span class="mx-2 text-gray-300">•</span>
                        <span class="text-xs text-gray-500">{{ $feed->created_at->format('d M Y') }}</span>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 line-clamp-2">{{ $feed->title }}</h3>
                    <p class="text-sm text-gray-500 mb-4 line-clamp-3">{{ Str::limit($feed->content, 100) }}</p>
                    
                    <button class="open-feed-modal w-full bg-gray-600 text-white py-2.5 rounded-lg hover:bg-orange-700 transition text-sm font-medium flex items-center justify-center group"
                            data-feed-title="{{ $feed->title }}"
                            data-feed-content="{{ $feed->content }}"
                            data-feed-image="{{ $feed->image ?? '' }}"
                            data-feed-created="{{ $feed->created_at }}"
                            data-ukm-name="{{ $feed->ukm->name }}"
                            data-ukm-logo="{{ $feed->ukm->logo ?? '' }}">
                        <span>Baca Selengkapnya</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Empty States --}}
        @if($feeds->count() == 0)
        <div class="text-center py-12" id="no-feeds-empty">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-newspaper text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada Feeds</h3>
            <p class="text-gray-500">Belum ada feed yang tersedia saat ini.</p>
        </div>
        @endif

        <div class="text-center py-12 hidden" id="no-filtered-feeds">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada feed dari UKM ini</h3>
            <p class="text-gray-500">Tidak ditemukan feed untuk UKM yang dipilih.</p>
            <button class="mt-4 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors category-filter" data-category="all">
                Tampilkan Semua Feed
            </button>
        </div>
    </div>
</main>

{{-- INCLUDE MODAL DARI FILE TERPISAH --}}
@include('layouts.partials.modals-dashboard')

<style>
/* Styles tetap sama */
.navbar-bg { background: linear-gradient(135deg, #373737 0%, #000000 100%); }
.category-filter.active { background-color: #1f2937 !important; color: white; }
.feed-card { transition: all 0.3s ease; }
.feed-card:hover { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
.feed-card.hidden { opacity: 0; transform: scale(0.8); height: 0; margin: 0; padding: 0; overflow: hidden; }
.line-clamp-1, .line-clamp-2, .line-clamp-3 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; }
.line-clamp-1 { -webkit-line-clamp: 1; }
.line-clamp-2 { -webkit-line-clamp: 2; }
.line-clamp-3 { -webkit-line-clamp: 3; }
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@section('scripts')
    {{-- INCLUDE SCRIPT MODAL UNIVERSAL --}}
    @include('layouts.partials.scripts-modal-universal')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inisialisasi modal
            if (window.ModalFunctions) {
                ModalFunctions.initAllModals();
                
                // Bind feed cards
                document.querySelectorAll('.open-feed-modal').forEach(button => {
                    button.addEventListener('click', function() {
                        const feedData = {
                            title: this.getAttribute('data-feed-title'),
                            content: this.getAttribute('data-feed-content'),
                            image: this.getAttribute('data-feed-image'),
                            created_at: this.getAttribute('data-feed-created'),
                            views: this.getAttribute('data-feed-views'),
                            ukm: {
                                name: this.getAttribute('data-ukm-name')
                            }
                        };
                        
                        // Isi data ke modal menggunakan fungsi universal
                        if (ModalFunctions.fillFeedModalData) {
                            ModalFunctions.fillFeedModalData(feedData);
                        }
                        
                        // Buka modal
                        const feedModal = document.getElementById('feedModal');
                        if (ModalFunctions.openDialog) {
                            ModalFunctions.openDialog(feedModal);
                        }
                    });
                });
            }
            
            // 2. Fungsi untuk mengisi modal feed
            function fillFeedModal(feedData) {
                document.getElementById('feedModalTitle').textContent = feedData.title;
                document.getElementById('feedModalUkm').textContent = feedData.ukm.name;
                
                const date = new Date(feedData.created_at);
                document.getElementById('feedModalDate').textContent = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                document.getElementById('feedModalTime').textContent = date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                }) + ' WIB';
                
                document.getElementById('feedModalContent').innerHTML = feedData.content;
                
                const logoContainer = document.getElementById('feedModalLogo');
                logoContainer.innerHTML = '';
                if (feedData.ukm.logo) {
                    logoContainer.innerHTML = `<img src="${feedData.ukm.logo}" alt="${feedData.ukm.name}" class="w-full h-full object-cover rounded-full">`;
                } else {
                    logoContainer.innerHTML = `<i class="fas fa-users text-gray-400 text-sm"></i>`;
                    logoContainer.classList.add('bg-gray-200');
                }
                
                const imageContainer = document.getElementById('feedModalImage');
                imageContainer.innerHTML = '';
                if (feedData.image) {
                    imageContainer.innerHTML = `<img src="${feedData.image}" alt="${feedData.title}" class="w-full h-full object-cover">`;
                } else {
                    imageContainer.innerHTML = `
                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-5xl"></i>
                        </div>
                    `;
                }
            }
            
            // 3. Kode filter category (diperbaiki agar event listener berfungsi)
            const categoryFilters = document.querySelectorAll('.category-filter');
            const feedCards = document.querySelectorAll('.feed-card');
            const noFeedsEmpty = document.getElementById('no-feeds-empty');
            const noFilteredFeeds = document.getElementById('no-filtered-feeds');
            const feedsGrid = document.getElementById('feeds-grid');

            if (feedCards.length > 0 && noFeedsEmpty) {
                noFeedsEmpty.style.display = 'none';
            }

            categoryFilters.forEach(filter => {
                filter.addEventListener('click', function() {
                    const selectedUkm = this.getAttribute('data-category');
                    
                    categoryFilters.forEach(f => {
                        f.classList.remove('active');
                        f.classList.remove('bg-gray-900', 'text-white');
                        f.classList.add('bg-gray-200', 'text-gray-700');
                    });
                    
                    this.classList.add('active');
                    this.classList.remove('bg-gray-200', 'text-gray-700');
                    this.classList.add('bg-gray-900', 'text-white');
                    
                    let visibleCount = 0;
                    
                    feedCards.forEach(card => {
                        const cardUkm = card.getAttribute('data-ukm');
                        
                        if (selectedUkm === 'all' || cardUkm === selectedUkm) {
                            card.classList.remove('hidden');
                            visibleCount++;
                            
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'scale(1)';
                            }, 50);
                        } else {
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.8)';
                            setTimeout(() => {
                                card.classList.add('hidden');
                            }, 300);
                        }
                    });
                    
                    if (visibleCount === 0) {
                        if (noFeedsEmpty) noFeedsEmpty.style.display = 'none';
                        noFilteredFeeds.classList.remove('hidden');
                        feedsGrid.classList.add('hidden');
                    } else {
                        if (noFeedsEmpty) noFeedsEmpty.style.display = 'none';
                        noFilteredFeeds.classList.add('hidden');
                        feedsGrid.classList.remove('hidden');
                    }
                });
            });
            
            // 4. Handle "Tampilkan Semua Feed" button
            const showAllButton = noFilteredFeeds.querySelector('.category-filter');
            if (showAllButton) {
                showAllButton.addEventListener('click', function() {
                    const allFilter = document.querySelector('.category-filter[data-category="all"]');
                    if (allFilter) allFilter.click();
                });
            }
        });
    </script>
@endsection