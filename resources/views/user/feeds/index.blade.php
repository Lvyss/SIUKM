@extends('layouts.user')

@section('content')
<!-- Hero Section -->
<section class="navbar-bg py-16 text-white text-center shadow-lg -mx-8 mb-14">
    <h1 class="text-3xl font-semibold tracking-wider">Feeds & Berita</h1>
    <p class="mt-2 text-sm text-gray-400">Update terbaru dari berbagai UKM</p>
</section>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-24">
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <!-- Categories Filter -->
        <div class="flex flex-wrap gap-2 mb-8 border-b pb-4 overflow-x-auto whitespace-nowrap">
            <button class="px-4 py-2 text-sm font-medium rounded-full bg-gray-900 text-white shadow-md flex-shrink-0 category-filter active" data-category="all">
                SEMUA UKM
            </button>
            @foreach($ukms as $ukmItem)
            <button class="px-4 py-2 text-sm font-medium rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 flex-shrink-0 category-filter" data-category="{{ $ukmItem->id }}">
                {{ strtoupper($ukmItem->name) }}
            </button>
            @endforeach
        </div>
        
        <!-- Feeds Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="feeds-grid">
            @foreach($feeds as $feed)
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-[1.02] transition duration-300 feed-card" data-ukm="{{ $feed->ukm->id }}">
                <!-- Feed Image -->
                <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-400 overflow-hidden relative">
                    @if($feed->image)
                        <img src="{{ $feed->image }}" alt="{{ $feed->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-4xl"></i>
                        </div>
                    @endif
                    
                    <!-- UKM Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-xs rounded-full font-semibold shadow-sm">
                            {{ $feed->ukm->name }}
                        </span>
                    </div>
                    
                    <!-- Time Badge -->
                    <div class="absolute top-3 left-3">
                        <div class="bg-black/70 text-white text-xs rounded-lg px-2 py-1">
                            {{ $feed->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                
                <!-- Feed Content -->
                <div class="p-4">
                    <!-- UKM Info -->
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
                    
                    <!-- Action Button -->
                    <button onclick="showFeedDetails({{ $feed }})" 
                            class="w-full bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center justify-center group">
                        <span>Baca Selengkapnya</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Empty State for No Feeds -->
        @if($feeds->count() == 0)
        <div class="text-center py-12" id="no-feeds-empty">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-newspaper text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada Feeds</h3>
            <p class="text-gray-500">Belum ada feed yang tersedia saat ini.</p>
        </div>
        @endif

        <!-- Empty State for Filtered Results -->
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

<!-- Feed Details Modal -->
<dialog id="feedModal" class="bg-white rounded-2xl shadow-2xl p-0 w-full max-w-4xl max-h-[90vh] overflow-y-auto backdrop:bg-black/30">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div id="feedModalLogo" class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                    <!-- Logo will be inserted here -->
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900" id="feedModalTitle"></h3>
                    <p class="text-sm text-gray-500" id="feedModalUkm"></p>
                </div>
            </div>
            <button onclick="document.getElementById('feedModal').close()" 
                    class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
    </div>
    
    <div class="p-6">
        <!-- Feed Meta -->
        <div class="flex items-center text-sm text-gray-500 mb-6">
            <div class="flex items-center mr-4">
                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                <span id="feedModalDate"></span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-clock mr-2 text-green-500"></i>
                <span id="feedModalTime"></span>
            </div>
        </div>
        
        <!-- Feed Image -->
        <div id="feedModalImage" class="w-full h-80 bg-gray-200 rounded-xl mb-6 flex items-center justify-center overflow-hidden">
            <!-- Image will be inserted here -->
        </div>
        
        <!-- Feed Content -->
        <div class="prose max-w-none">
            <div id="feedModalContent" class="text-gray-700 leading-relaxed text-lg whitespace-pre-line"></div>
        </div>
    </div>
    
    <div class="flex justify-end p-6 border-t border-gray-200">
        <button onclick="document.getElementById('feedModal').close()" 
                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors">
            Tutup
        </button>
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

.prose {
    line-height: 1.8;
    font-size: 16px;
}

.prose p {
    margin-bottom: 1.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilters = document.querySelectorAll('.category-filter');
    const feedCards = document.querySelectorAll('.feed-card');
    const noFeedsEmpty = document.getElementById('no-feeds-empty');
    const noFilteredFeeds = document.getElementById('no-filtered-feeds');
    const feedsGrid = document.getElementById('feeds-grid');

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
            const allFilter = document.querySelector('.category-filter[data-category="all"]');
            if (allFilter) {
                allFilter.click();
            }
        });
    }
});

function showFeedDetails(feed) {
    document.getElementById('feedModalTitle').textContent = feed.title;
    document.getElementById('feedModalUkm').textContent = feed.ukm.name;
    document.getElementById('feedModalDate').textContent = new Date(feed.created_at).toLocaleDateString('id-ID', { 
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
    });
    document.getElementById('feedModalTime').textContent = feed.created_at.substring(11, 16) + ' WIB';
    document.getElementById('feedModalContent').textContent = feed.content;
    
    // Set UKM logo
    const logoContainer = document.getElementById('feedModalLogo');
    if (feed.ukm.logo) {
        logoContainer.innerHTML = `<img src="${feed.ukm.logo}" alt="${feed.ukm.name}" class="w-full h-full object-cover rounded-xl">`;
    } else {
        logoContainer.innerHTML = `<i class="fas fa-users text-white"></i>`;
    }
    
    // Set feed image
    const imageContainer = document.getElementById('feedModalImage');
    if (feed.image) {
        imageContainer.innerHTML = `<img src="${feed.image}" alt="${feed.title}" class="w-full h-full object-cover rounded-xl">`;
    } else {
        imageContainer.innerHTML = `
            <div class="w-full h-full bg-gradient-to-br from-purple-100 to-pink-200 flex items-center justify-center rounded-xl">
                <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
            </div>
        `;
    }
    
    document.getElementById('feedModal').showModal();
}

// Close modal when clicking outside
document.getElementById('feedModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.close();
    }
});
</script>
@endsection