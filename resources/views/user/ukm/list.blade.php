@extends('layouts.user')

@section('content')
<!-- Hero Section -->
<section class="navbar-bg py-16 text-white text-center shadow-lg -ml-8 -mr-8 mb-14">
    <h1 class="text-3xl font-semibold tracking-wider">Daftar UKM</h1>
    <p class="mt-2 text-sm text-gray-400">Mulai perjalananmu di sini</p>
</section>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-24">
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <!-- Categories Filter -->
        <div class="flex flex-wrap gap-2 mb-8 border-b pb-4 overflow-x-auto whitespace-nowrap">
            <button class="px-4 py-2 text-sm font-medium rounded-full bg-gray-900 text-white shadow-md flex-shrink-0 category-filter active" data-category="all">
                SEMUA KATEGORI
            </button>
            @foreach($categories as $category)
            <button class="px-4 py-2 text-sm font-medium rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 flex-shrink-0 category-filter" data-category="{{ $category->id }}">
                {{ strtoupper($category->name) }}
            </button>
            @endforeach
        </div>
        
        <!-- UKM Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="ukm-grid">
            @foreach($ukms as $ukm)
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-[1.02] transition duration-300 ukm-card" data-category="{{ $ukm->category_id }}">
                <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-400 overflow-hidden">
                    @if($ukm->logo)
                        <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 3h16a2 2 0 012 2v14a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2zm4.5 10.5a.5.5 0 00-.5.5v2a.5.5 0 00.5.5h2a.5.5 0 00.5-.5v-2a.5.5 0 00-.5-.5h-2zM12 9a3 3 0 100 6 3 3 0 000-6zM4 19h16v-6l-3.5-3.5-3 3-5-5-4.5 4.5v6z"/>
                        </svg>
                    @endif
                    <!-- Category Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded-full">
                            {{ $ukm->category->name }}
                        </span>
                    </div>
                </div>
                <div class="p-4 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $ukm->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1 h-10 overflow-hidden">{{ Str::limit($ukm->description, 80) }}</p>
                    
                    <!-- Member Count -->
                    <div class="flex items-center justify-center text-xs text-gray-500 mt-2">
                        <i class="fas fa-users mr-1"></i>
                        <span>{{ $ukm->registrations()->where('status', 'approved')->count() }} Anggota</span>
                    </div>
                    
                    <!-- Social Media -->
                    @if($ukm->instagram)
                    <p class="text-xs text-blue-500 mt-3 font-medium">{{ $ukm->instagram }}</p>
                    @else
                    <p class="text-xs text-gray-400 mt-3 font-medium">@username</p>
                    @endif
                    
                    <!-- Action Button -->
                    <div class="mt-4">
                        <a href="{{ route('user.ukm.detail', $ukm->id) }}" 
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Empty State for No UKM -->
        @if($ukms->count() == 0)
        <div class="text-center py-12" id="no-ukm-empty">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 3h16a2 2 0 012 2v14a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2zm4.5 10.5a.5.5 0 00-.5.5v2a.5.5 0 00.5.5h2a.5.5 0 00.5-.5v-2a.5.5 0 00-.5-.5h-2zM12 9a3 3 0 100 6 3 3 0 000-6zM4 19h16v-6l-3.5-3.5-3 3-5-5-4.5 4.5v6z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada UKM</h3>
            <p class="text-gray-500">Belum ada UKM yang terdaftar saat ini.</p>
        </div>
        @endif

        <!-- Empty State for Filtered Results -->
        <div class="text-center py-12 hidden" id="no-filtered-ukm">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada UKM di kategori ini</h3>
            <p class="text-gray-500">Tidak ditemukan UKM untuk kategori yang dipilih.</p>
            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors category-filter" data-category="all">
                Tampilkan Semua UKM
            </button>
        </div>
    </div>
</main>

<style>
.navbar-bg {
    background: linear-gradient(135deg, #373737 0%, #000000 100%);
}

.category-filter.active {
    background-color: #1f2937 !important;
    color: white;
}

.ukm-card {
    transition: all 0.3s ease;
}

.ukm-card:hover {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Smooth transition for hiding/showing cards */
.ukm-card {
    transition: all 0.3s ease;
}

.ukm-card.hidden {
    opacity: 0;
    transform: scale(0.8);
    height: 0;
    margin: 0;
    padding: 0;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilters = document.querySelectorAll('.category-filter');
    const ukmCards = document.querySelectorAll('.ukm-card');
    const noUkmEmpty = document.getElementById('no-ukm-empty');
    const noFilteredUkm = document.getElementById('no-filtered-ukm');
    const ukmGrid = document.getElementById('ukm-grid');

    // Hide empty state initially if there are UKMs
    if (ukmCards.length > 0 && noUkmEmpty) {
        noUkmEmpty.style.display = 'none';
    }

    // Category filter functionality
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            const selectedCategory = this.getAttribute('data-category');
            
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
            
            // Filter UKM cards
            ukmCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                
                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
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
                if (noUkmEmpty) noUkmEmpty.style.display = 'none';
                noFilteredUkm.classList.remove('hidden');
                ukmGrid.classList.add('hidden');
            } else {
                if (noUkmEmpty) noUkmEmpty.style.display = 'none';
                noFilteredUkm.classList.add('hidden');
                ukmGrid.classList.remove('hidden');
            }
        });
    });
    
    // Add loading animation for initial cards
    ukmCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Handle "Tampilkan Semua UKM" button in empty state
    const showAllButton = noFilteredUkm.querySelector('.category-filter');
    if (showAllButton) {
        showAllButton.addEventListener('click', function() {
            // Find and click the "All" category filter
            const allFilter = document.querySelector('.category-filter[data-category="all"]');
            if (allFilter) {
                allFilter.click();
            }
        });
    }
});
</script>
@endsection