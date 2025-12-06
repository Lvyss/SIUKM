{{-- Di setiap view (dashboard, ukm.list, events.index, feeds.index) --}}
@extends('layouts.user')

@section('content')
    <div class="container mx-auto px-4 py-8">

        <section class="navbar-bg py-16  text-white text-center shadow-lg -mx-8 mb-14">
            <h1 class="text-3xl font-semibold tracking-wider pt-16">Daftar UKM</h1>
            <p class="mt-2 text-sm text-gray-400">Temukan minat, kembangkan bakat, dan mulai perjalananmu di sini</p>
        </section>

        <main class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-24">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <div class="flex flex-wrap gap-2 mb-8 border-b pb-4 overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <button
                        class="px-4 py-2 text-sm font-medium rounded-full bg-gray-900 text-white shadow-md flex-shrink-0 category-filter active"
                        data-category="all">
                        SEMUA KATEGORI
                    </button>
                    @foreach ($categories as $category)
                        <button
                            class="px-4 py-2 text-sm font-medium rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 flex-shrink-0 category-filter"
                            data-category="{{ $category->id }}">
                            {{ strtoupper($category->name) }}
                        </button>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="ukm-grid">
                    @foreach ($ukms as $ukm)
                        {{-- START CARD UKM BARU --}}
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:shadow-xl hover:scale-[1.02] transition duration-300 ukm-card"
                            data-category="{{ $ukm->category_id }}">

                            <div class="h-36 relative bg-gray-100 flex items-center justify-center">
                                {{-- 1. COVER IMAGE --}}
                                @if ($ukm->logo)
                                    <img src="{{ $ukm->logo }}" alt="Cover {{ $ukm->name }}"
                                        class="w-full h-full object-cover brightness-75 blur-[5px]">
                                @else
                                    {{-- Placeholder jika tidak ada cover image --}}
                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-camera text-4xl text-gray-500"></i>
                                    </div>
                                @endif

                                {{-- Category Badge (DITAMBAH CLASS: ukm-badge) --}}
                                <div class="absolute top-3 right-3 z-10 ukm-badge transition duration-300">
                                    <span
                                        class="px-3 py-1 bg-blue-600/90 text-white text-xs font-semibold rounded-full shadow-md">
                                        {{ $ukm->category->name }}
                                    </span>
                                </div>

                                {{-- 2. CIRCULAR LOGO (DITAMBAH CLASS: ukm-logo-container) --}}
                                <div
                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-24 h-24 border-4 border-white rounded-full overflow-hidden bg-white shadow-lg z-20 ukm-logo-container transition duration-300">
                                    @if ($ukm->logo)
                                        <img src="{{ $ukm->logo }}" alt="Logo {{ $ukm->name }}"
                                            class="w-full h-full object-cover p-1">
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
                                <p class="text-sm text-gray-500 mb-4 h-12 overflow-hidden line-clamp-2">
                                    {{ Str::limit($ukm->description, 80) }}</p>

                                <div class="flex flex-col items-center justify-center space-y-1 text-sm text-gray-500 mb-4">
                                    @if ($ukm->contact_person)
                                        <div class="flex items-center text-blue-600 font-medium">
                                            <i class="fas fa-phone-alt mr-2 text-xs"></i>
                                            <span>{{ $ukm->contact_person }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-gray-400">
                                            <i class="fas fa-phone-alt mr-2 text-xs"></i>
                                            <span>Kontak belum tersedia</span>
                                        </div>
                                    @endif

                                    {{-- Member Count --}}
                                    <div class="flex items-center text-gray-500">
                                        <i class="fas fa-user-friends mr-2 text-xs"></i>
                                        <span
                                            class="text-xs">{{ $ukm->registrations()->where('status', 'approved')->count() }}
                                            Anggota</span>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('user.ukm.detail', $ukm->id) }}"
                                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-md">
                                        <i class="fas fa-info-circle mr-2"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- END CARD UKM BARU --}}
                    @endforeach
                </div>

                @if ($ukms->count() == 0)
                    <div class="text-center py-12" id="no-ukm-empty">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4 3h16a2 2 0 012 2v14a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2zm4.5 10.5a.5.5 0 00-.5.5v2a.5.5 0 00.5.5h2a.5.5 0 00.5-.5v-2a.5.5 0 00-.5-.5h-2zM12 9a3 3 0 100 6 3 3 0 000-6zM4 19h16v-6l-3.5-3.5-3 3-5-5-4.5 4.5v6z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada UKM</h3>
                        <p class="text-gray-500">Belum ada UKM yang terdaftar saat ini.</p>
                    </div>
                @endif

                <div class="text-center py-12 hidden" id="no-filtered-ukm">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada UKM di kategori ini</h3>
                    <p class="text-gray-500">Tidak ditemukan UKM untuk kategori yang dipilih.</p>
                    <button
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors category-filter"
                        data-category="all">
                        Tampilkan Semua UKM
                    </button>
                </div>
            </div>
        </main>

    @section('scripts')
        <style>
            /* ... (Style Anda sebelumnya) ... */
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

            /* Smooth transition for hiding/showing cards (Filtering) */
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

            /* Penyesuaian Line Clamps (Jika belum ada di layouts.user) */
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

            /* === PERBAIKAN BUG HOVER LOGO DAN PENAMBAHAN EFEK HOVER KEREN === */

            /* Mengisolasi Transformasi Logo saat Card di-hover */
            .ukm-card:hover .ukm-logo-container {
                /* Mencegah logo ikut terskala 1.02, hanya menerapkan rotasi/shadow */
                transform: translate(-50%, -50%) rotate(-3deg);
                /* Tambah shadow ring biru saat hover */
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.4), 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            /* Netralkan efek transformasi pada badge saat card di-hover */
            .ukm-card:hover .ukm-badge {
                /* Mencegah badge ikut terskala */
                transform: scale(1.0);
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

                            if (selectedCategory === 'all' || cardCategory ===
                                selectedCategory) {
                                // Check if it's currently hidden before animating
                                if (card.classList.contains('hidden')) {
                                    card.classList.remove('hidden');
                                    card.style.opacity = '0';
                                    card.style.transform = 'scale(0.8)';
                                }

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
