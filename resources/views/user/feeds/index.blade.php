@extends('layouts.user')

@section('content')
<section class="navbar-bg py-16 text-white text-center shadow-lg -mx-8 mb-14">
    <h1 class="text-3xl font-semibold tracking-wider pt-16">Feeds</h1>
    <p class="mt-2 text-sm text-gray-400">Update terbaru dari berbagai UKM</p>
</section>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-24">
    <div class="bg-white p-6 rounded-xl shadow-lg">
        {{-- Tombol Filter dan Grid Feeds --}}
        <div class="flex flex-wrap gap-2 mb-8 border-b pb-4 overflow-x-auto whitespace-nowrap scrollbar-hide">
            <button class="px-4 py-2 text-sm font-medium rounded-full bg-gray-900 text-white shadow-md flex-shrink-0 category-filter active" data-category="all">
                SEMUA UKM
            </button>
            @foreach($ukms as $ukmItem)
            <button class="px-4 py-2 text-sm font-medium rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 flex-shrink-0 category-filter" data-category="{{ $ukmItem->id }}">
                {{ strtoupper($ukmItem->name) }}
            </button>
            @endforeach
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="feeds-grid">
            @foreach($feeds as $feed)
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-[1.02] transition duration-300 feed-card" data-ukm="{{ $feed->ukm->id }}">
                <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-400 overflow-hidden relative">
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
                    
                    <button onclick="showFeedDetails({{ json_encode($feed->load('ukm')) }})" 
                            class="w-full bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center justify-center group">
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
            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors category-filter" data-category="all">
                Tampilkan Semua Feed
            </button>
        </div>
    </div>
</main>

{{-- MODAL ASIMETRIS ELEGANT SAMA SEPERTI EVENT --}}
<dialog id="feedModal" 
    class="bg-white rounded-2xl shadow-2xl p-0 w-full max-w-5xl max-h-[90vh] 
           backdrop:bg-black/40 backdrop:backdrop-blur-sm 
           transform transition-all duration-300 opacity-0 scale-95 flex flex-col overflow-hidden">
    
    <div class="relative flex h-full">
        
        {{-- KOLOM KIRI: Judul, Meta, dan Konten Teks --}}
        <div class="w-7/12 flex-shrink-0 p-8 overflow-y-auto max-h-[90vh] pb-20">
            
            {{-- Header/Judul --}}
            <h1 class="text-4xl font-extrabold text-gray-900 mb-6 leading-tight" id="feedModalTitle">Judul Feed</h1>
            
            {{-- UKM & Meta Info Block --}}
            <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-3">
                
                {{-- UKM Info --}}
                <div class="flex items-center text-sm font-semibold text-gray-700">
                    <div id="feedModalLogo" class="w-8 h-8 rounded-full flex items-center justify-center mr-3 border border-gray-200 flex-shrink-0 overflow-hidden">
                        {{-- Logo will be inserted here --}}
                    </div>
                    <span id="feedModalUkm" class="text-base text-blue-600 font-bold">{{ $feed->ukm->name ?? 'Nama UKM' }}</span>
                </div>
                
                {{-- Tanggal & Waktu --}}
                <div class="space-y-3 pt-2 border-t border-gray-200">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-3 text-blue-500 w-4"></i>
                        <span id="feedModalDate">Tanggal Posting</span>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-3 text-green-500 w-4"></i>
                        <span id="feedModalTime">Waktu Posting</span>
                    </div>
                </div>
            </div>
            
            {{-- Konten Utama --}}
            <div class="prose max-w-none text-gray-700 leading-relaxed text-base">
                <div id="feedModalContent" class="whitespace-pre-line">
                    {{-- Konten feed akan masuk di sini --}}
                </div>
            </div>
            
        </div>
        
        {{-- KOLOM KANAN: Gambar dan Tombol Close --}}
        <div class="w-5/12 flex-shrink-0 bg-gray-100 relative rounded-r-2xl overflow-hidden shadow-inner flex items-center justify-center">
            
            {{-- Container Gambar dengan object-cover --}}
            <div id="feedModalImage" class="w-full h-full">
                {{-- Gambar akan masuk di sini --}}
            </div>
            
            {{-- Tombol Close di atas gambar --}}
            <button data-close-modal 
                class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/70 backdrop-blur-sm hover:bg-white flex items-center justify-center transition-colors shadow-lg border border-gray-100">
                <i class="fas fa-times text-gray-600 text-lg"></i>
            </button>
            
            {{-- Tombol Tutup di Bawah --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/50 to-transparent flex justify-center">
                <button data-close-modal
                    class="px-8 py-2.5 bg-white/90 text-gray-800 rounded-full hover:bg-white font-medium transition-colors shadow-xl">
                    <i class="fas fa-chevron-circle-up rotate-180 mr-2 text-sm"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</dialog>

<style>
.navbar-bg {
    background: linear-gradient(135deg, #373737 0%, #000000 100%);
}

.category-filter.active {
    background-color: #1f2937 !important;
    color: white;
}

.feed-card {
    transition: all 0.3s ease;
}

.feed-card:hover {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.feed-card.hidden {
    opacity: 0;
    transform: scale(0.8);
    height: 0;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

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

/* --- Styles MODAL ASIMETRIS --- */
dialog#feedModal {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 90vw; 
    max-height: 90vh;
    width: fit-content;
    height: fit-content;
}

dialog#feedModal > div.relative {
    height: 100%;
    display: flex;
    max-height: 90vh;
}

dialog#feedModal[open] {
    opacity: 1;
    transform: scale(1);
}

/* Kolom Kiri: Konten */
dialog#feedModal .w-7/12 {
    height: 100%;
}

/* Kolom Kanan: Gambar */
dialog#feedModal .w-5/12 {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Container Gambar */
#feedModalImage {
    width: 100%;
    height: 100%;
    overflow: hidden;
}

/* Gambar di dalam container */
#feedModalImage img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Konten utama */
.prose {
    line-height: 1.8;
    font-size: 16px;
}

.prose p {
    margin-bottom: 1.5rem;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Filter Functionality
    const categoryFilters = document.querySelectorAll('.category-filter');
    const feedCards = document.querySelectorAll('.feed-card');
    const noFeedsEmpty = document.getElementById('no-feeds-empty');
    const noFilteredFeeds = document.getElementById('no-filtered-feeds');
    const feedsGrid = document.getElementById('feeds-grid');
    const modal = document.getElementById('feedModal');
    const transitionDuration = 300;

    // Hide empty state initially if there are feeds
    if (feedCards.length > 0 && noFeedsEmpty) {
        noFeedsEmpty.style.display = 'none';
    }

    // Category filter functionality
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            const selectedUkm = this.getAttribute('data-category');
            
            // Update active state
            categoryFilters.forEach(f => {
                f.classList.remove('active');
                f.classList.remove('bg-gray-900', 'text-white');
                f.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            this.classList.add('active');
            this.classList.remove('bg-gray-200', 'text-gray-700');
            this.classList.add('bg-gray-900', 'text-white');
            
            let visibleCount = 0;
            
            // Filter feed cards
            feedCards.forEach(card => {
                const cardUkm = card.getAttribute('data-ukm');
                
                if (selectedUkm === 'all' || cardUkm === selectedUkm) {
                    card.classList.remove('hidden');
                    visibleCount++;
                    
                    // Add animation delay for staggered appearance
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
            
            // Show/hide empty states
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
    
    // Add loading animation for initial cards
    feedCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Handle "Tampilkan Semua Feed" button in empty state
    const showAllButton = noFilteredFeeds.querySelector('.category-filter');
    if (showAllButton) {
        showAllButton.addEventListener('click', function() {
            // Find and click the "All" category filter
            const allFilter = document.querySelector('.category-filter[data-category="all"]');
            if (allFilter) {
                allFilter.click();
            }
        });
    }

    // Modal Functionality
    function closeModal() {
        if (!modal.hasAttribute('open')) return;
        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            modal.close();
        }, transitionDuration);
    }
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal using data-close-modal buttons
    const closeButtons = document.querySelectorAll('#feedModal [data-close-modal]');
    closeButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });
});

/**
 * Membuka modal detail feed dan mengisi kontennya.
 * @param {object} feed - Objek feed yang dimuat dengan relasi UKM.
 */
function showFeedDetails(feed) {
    const modal = document.getElementById('feedModal');

    // 1. Isi Konten Teks (Kolom Kiri)
    document.getElementById('feedModalTitle').textContent = feed.title;
    document.getElementById('feedModalUkm').textContent = feed.ukm.name;

    const date = new Date(feed.created_at);
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

    document.getElementById('feedModalContent').innerHTML = feed.content;

    const logoContainer = document.getElementById('feedModalLogo');
    logoContainer.innerHTML = '';
    logoContainer.classList.remove('bg-gray-200');

    if (feed.ukm.logo) {
        logoContainer.innerHTML = `<img src="${feed.ukm.logo}" alt="${feed.ukm.name}" class="w-full h-full object-cover rounded-full">`;
    } else {
        logoContainer.innerHTML = `<i class="fas fa-users text-gray-400 text-sm"></i>`;
        logoContainer.classList.add('bg-gray-200');
    }

    // 2. Isi Gambar (Kolom Kanan)
    const imageContainer = document.getElementById('feedModalImage');
    imageContainer.innerHTML = '';

    if (feed.image) {
        // Class object-cover diterapkan via CSS
        imageContainer.innerHTML = `<img src="${feed.image}" alt="${feed.title}" class="w-full h-full object-cover">`;
    } else {
        imageContainer.innerHTML = `
            <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                <i class="fas fa-newspaper text-white text-5xl"></i>
            </div>
        `;
    }

    // 3. Buka Modal dengan Animasi Masuk
    modal.showModal();
    setTimeout(() => {
        modal.classList.add('opacity-100', 'scale-100');
        modal.classList.remove('opacity-0', 'scale-95');
    }, 10);
}
</script>
@endsection