{{-- Di setiap view (dashboard, ukm.list, events.index, feeds.index) --}}
@extends('layouts.user')

@section('content')
    <div class="container mx-auto px-4 py-8">

        <section class="navbar-bg py-16 text-white text-center shadow-lg -mx-8 mb-14">
            <h1 class="text-3xl font-semibold tracking-wider">Events & Kegiatan</h1>
            <p class="mt-2 text-sm text-gray-400">Temukan event menarik dari berbagai UKM</p>
        </section>

        <main class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-24">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <div class="flex flex-wrap gap-2 mb-8 border-b pb-4 overflow-x-auto whitespace-nowrap">
                    <button
                        class="px-4 py-2 text-sm font-medium rounded-full bg-gray-900 text-white shadow-md flex-shrink-0 category-filter active"
                        data-category="all">
                        SEMUA UKM
                    </button>
                    @foreach ($ukms as $ukmItem)
                        <button
                            class="px-4 py-2 text-sm font-medium rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 flex-shrink-0 category-filter"
                            data-category="{{ $ukmItem->id }}">
                            {{ strtoupper($ukmItem->name) }}
                        </button>
                    @endforeach
                </div>

                {{-- TELAH DIKOREKSI: Mengubah grid-cols-1 menjadi grid-cols-2 dan mengembalikan lg:grid-cols-3 --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6" id="events-grid">
                    @foreach ($events as $event)
                        <div class="bg-white rounded-md shadow-md overflow-hidden transform hover:scale-[1.02] transition duration-300 event-card h-full flex flex-col"
                            data-ukm="{{ $event->ukm->id }}">

                            <!-- Bagian Gambar - Fixed Height -->
                            <div
                                class="h-44 md:h-60 bg-gray-200 flex items-center justify-center text-gray-400 overflow-hidden relative">
                                @if ($event->poster_image)
                                    <img src="{{ $event->poster_image }}" alt="{{ $event->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-orange-500 to-purple-600 flex items-center justify-center">
                                        <i class="fas fa-calendar text-white text-4xl"></i>
                                    </div>
                                @endif

                                <div class="absolute top-3 right-3">
                                    <span
                                        class="px-2 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-xs rounded-full font-semibold shadow-sm">
                                        {{ $event->ukm->name }}
                                    </span>
                                </div>

                                <div class="absolute top-3 left-3">
                                    <div class="bg-black/70 text-white text-xs rounded-lg px-2 py-1 text-center">
                                        <div class="font-bold">{{ $event->event_date->format('d') }}</div>
                                        <div class="text-xs">{{ $event->event_date->format('M') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian Konten - Flex Column dengan Grow -->
                            <div class="p-4 flex flex-col flex-grow">

                                <!-- Judul dengan Str::limit dan height tetap -->
                                <div class="mb-2 min-h-[3rem]">
                                    <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">
                                        {{ Str::limit($event->title, 50) }} <!-- Limit 50 karakter -->
                                    </h3>
                                </div>

                                <!-- Deskripsi dengan Str::limit dan flexible height -->
                                <div class="mb-3 flex-grow min-h-[3rem]">
                                    <p class="text-sm text-gray-500 line-clamp-2">
                                        {{ Str::limit($event->description, 80) }} <!-- Limit 80 karakter -->
                                    </p>
                                </div>

                                <!-- Info waktu & lokasi - Height tetap -->
                                <div class="mb-4 space-y-2 text-xs text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 w-4 text-gray-500"></i>
                                        <span>{{ $event->event_time }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 w-4 text-gray-500"></i>
                                        <span class="line-clamp-1">
                                            {{ Str::limit($event->location, 30) }} <!-- Limit 30 karakter untuk lokasi -->
                                        </span>
                                    </div>
                                </div>

                                <!-- Tombol - Selalu di bagian bawah dengan margin-top auto -->
                                <div class="mt-auto pt-2">
                                    <div class="flex space-x-2">
                                        <button
                                            class="open-event-modal flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-orange-700 transition text-sm font-medium flex items-center justify-center"
                                            data-event-title="{{ $event->title }}"
                                            data-event-description="{{ $event->description }}"
                                            data-event-date="{{ $event->event_date }}"
                                            data-event-time="{{ $event->event_time }}"
                                            data-event-location="{{ $event->location }}"
                                            data-event-poster="{{ $event->poster_image ?? '' }}"
                                            data-event-registration="{{ $event->registration_link ?? '' }}"
                                            data-ukm-name="{{ $event->ukm->name }}"
                                            data-ukm-logo="{{ $event->ukm->logo ?? '' }}">
                                            <i class="fas fa-eye mr-1 text-xs"></i>
                                            Detail
                                        </button>
                                        @if ($event->registration_link)
                                            <a href="{{ $event->registration_link }}" target="_blank"
                                                class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium flex items-center justify-center">
                                                <i class="fas fa-external-link-alt text-xs"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($events->count() == 0)
                    <div class="text-center py-12" id="no-events-empty">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada Events</h3>
                        <p class="text-gray-500">Belum ada event yang tersedia saat ini.</p>
                    </div>
                @endif

                <div class="text-center py-12 hidden" id="no-filtered-events">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada event dari UKM ini</h3>
                    <p class="text-gray-500">Tidak ditemukan event untuk UKM yang dipilih.</p>
                    <button
                        class="mt-4 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors category-filter"
                        data-category="all">
                        Tampilkan Semua Event
                    </button>
                </div>
            </div>
        </main>


        @include('layouts.partials.modals-dashboard')

        <style>
            .navbar-bg {
                background: linear-gradient(135deg, #373737 0%, #000000 100%);
            }

            .category-filter.active {
                background-color: #1f2937 !important;
                color: white;
            }

            .event-card {
                transition: all 0.3s ease;
            }

            .event-card:hover {
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .event-card.hidden {
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

            /* --- Styles MODAL ASIMETRIS BARU (Revisi Penataan) --- */
            dialog#eventModal {
                display: flex;
                align-items: center;
                /* Center Vertikal */
                justify-content: center;
                /* Center Horizontal */
                max-width: 90vw;
                max-height: 90vh;
                /* Tambahan: Pastikan modalnya tidak melebihi viewport */
                width: fit-content;
                height: fit-content;
            }

            dialog#eventModal>div.relative {
                height: 100%;
                /* Memastikan flex child mengisi tinggi penuh parent */
                display: flex;
                /* Aktifkan flex pada container kolom */
                max-height: 90vh;
                /* Batasi tinggi total modal */
            }

            dialog#eventModal[open] {
                opacity: 1;
                transform: scale(1);
            }

            /* Kolom Kiri: Konten */
            dialog#eventModal .w-7/12 {
                height: 100%;
                /* Pastikan mengisi tinggi penuh */
            }

            /* Kolom Kanan: Gambar Poster */
            dialog#eventModal .w-5/12 {
                height: 100%;
                /* Pastikan mengisi tinggi penuh */
                /* Pastikan elemen poster di tengah jika gambarnya lebih kecil */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Container Gambar Poster */
            #eventModalPoster {
                width: 100%;
                height: 100%;
                /* Ini penting agar div poster mengisi penuh kolom kanan */
                overflow: hidden;
                /* Sembunyikan jika ada bagian gambar yang keluar */
            }

            /* Gambar di dalam container poster */
            #eventModalPoster img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                /* KRUSIAL: Ini yang membuat gambar mengisi penuh dan tidak terdistorsi */
                display: block;
                /* Menghilangkan spasi bawah default pada img */
            }

            /* Konten utama deskripsi */
            .prose {
                line-height: 1.8;
                font-size: 16px;
            }

            .prose p {
                margin-bottom: 1.5rem;
            }
        </style>
    @endsection

    @section('scripts')
        {{-- INCLUDE SCRIPT MODAL UNIVERSAL --}}
        @include('layouts.partials.scripts-modal-universal')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Inisialisasi modal universal
                if (window.ModalFunctions) {
                    ModalFunctions.initAllModals();

                    // Bind event cards ke modal
                    document.querySelectorAll('.open-event-modal').forEach(button => {
                        button.addEventListener('click', function() {
                            const eventData = {
                                title: this.getAttribute('data-event-title'),
                                description: this.getAttribute('data-event-description'),
                                event_date: this.getAttribute('data-event-date'),
                                event_time: this.getAttribute('data-event-time'),
                                location: this.getAttribute('data-event-location'),
                                poster_image: this.getAttribute('data-event-poster'),
                                registration_link: this.getAttribute('data-event-registration'),
                                ukm: {
                                    name: this.getAttribute('data-ukm-name'),
                                    logo: this.getAttribute('data-ukm-logo')
                                }
                            };

                            // Isi data ke modal menggunakan fungsi universal
                            if (ModalFunctions.fillEventModalData) {
                                ModalFunctions.fillEventModalData(eventData);
                            }

                            // Buka modal
                            const eventModal = document.getElementById('eventModal');
                            if (ModalFunctions.openDialog) {
                                ModalFunctions.openDialog(eventModal);
                            }
                        });
                    });
                }

                // 2. Filter category (tetap sama)
                const categoryFilters = document.querySelectorAll('.category-filter');
                const eventCards = document.querySelectorAll('.event-card');
                const noEventsEmpty = document.getElementById('no-events-empty');
                const noFilteredEvents = document.getElementById('no-filtered-events');
                const eventsGrid = document.getElementById('events-grid');

                if (eventCards.length > 0 && noEventsEmpty) {
                    noEventsEmpty.style.display = 'none';
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

                        eventCards.forEach(card => {
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
                            if (noEventsEmpty) noEventsEmpty.style.display = 'none';
                            noFilteredEvents.classList.remove('hidden');
                            eventsGrid.classList.add('hidden');
                        } else {
                            if (noEventsEmpty) noEventsEmpty.style.display = 'none';
                            noFilteredEvents.classList.add('hidden');
                            eventsGrid.classList.remove('hidden');
                        }
                    });
                });

                // 3. Animasi loading cards
                eventCards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });

                // 4. Handle "Tampilkan Semua Event" button
                const showAllButton = noFilteredEvents.querySelector('.category-filter');
                if (showAllButton) {
                    showAllButton.addEventListener('click', function() {
                        const allFilter = document.querySelector('.category-filter[data-category="all"]');
                        if (allFilter) allFilter.click();
                    });
                }
            });
        </script>
    @endsection
