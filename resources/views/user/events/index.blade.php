@extends('layouts.user')

@section('content')
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="events-grid">
                @foreach ($events as $event)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-[1.02] transition duration-300 event-card"
                        data-ukm="{{ $event->ukm->id }}">
                        <div
                            class="h-40 bg-gray-200 flex items-center justify-center text-gray-400 overflow-hidden relative">
                            @if ($event->poster_image)
                                <img src="{{ $event->poster_image }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
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

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ Str::limit($event->description, 80) }}</p>

                            <div class="space-y-2 text-xs text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 w-4 text-blue-500"></i>
                                    <span>{{ $event->event_time }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 w-4 text-green-500"></i>
                                    <span class="line-clamp-1">{{ $event->location }}</span>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                {{-- Ubah tombol detail agar mengirim data lengkap, termasuk relasi ukm --}}
                                <button onclick="showEventDetails({{ json_encode($event->load('ukm')) }})"
                                    class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center justify-center">
                                    <i class="fas fa-eye mr-1 text-xs"></i>
                                    Detail
                                </button>
                                @if ($event->registration_link)
                                    <a href="{{ $event->registration_link }}" target="_blank"
                                        class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium flex items-center">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                @endif
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
                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors category-filter"
                    data-category="all">
                    Tampilkan Semua Event
                </button>
            </div>
        </div>
    </main>

    {{-- START MODAL ASIMETRIS ELEGANT BARU (Revisi Penataan) --}}
    <dialog id="eventModal" 
        class="bg-white rounded-2xl shadow-2xl p-0 w-full max-w-5xl max-h-[90vh] 
               backdrop:bg-black/40 backdrop:backdrop-blur-sm 
               transform transition-all duration-300 opacity-0 scale-95 flex flex-col overflow-hidden">
        
        <div class="relative flex h-full">
            
            {{-- KOLOM KIRI: Judul, Meta, dan Konten Teks --}}
            <div class="w-7/12 flex-shrink-0 p-8 overflow-y-auto max-h-[90vh] pb-20">
                
                {{-- Header/Judul --}}
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6 leading-tight" id="eventModalTitle">Judul Event</h1>
                
                {{-- UKM & Meta Info Block --}}
                <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-3">
                    
                    {{-- UKM Info / Organizer --}}
                    <div class="flex items-center text-sm font-semibold text-gray-700">
                        <div id="eventModalLogo" class="w-8 h-8 rounded-full flex items-center justify-center mr-3 border border-gray-200 flex-shrink-0 overflow-hidden">
                            {{-- Logo will be inserted here --}}
                        </div>
                        <span id="eventModalUkm" class="text-base text-blue-600 font-bold">Nama UKM (Organizer)</span>
                    </div>
                    
                    {{-- Tanggal, Waktu, Lokasi --}}
                    <div class="space-y-3 pt-2 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-3 text-blue-500 w-4"></i>
                            <span id="eventModalDate">Tanggal Event</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock mr-3 text-green-500 w-4"></i>
                            <span id="eventModalTime">Waktu Event</span>
                        </div>

                        <div class="flex items-start text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-3 text-red-500 mt-1 w-4"></i>
                            <span id="eventModalLocation" class="font-medium">Lokasi Event</span>
                        </div>
                    </div>
                </div>
                
                {{-- Konten Utama (Deskripsi) --}}
                <div class="prose max-w-none text-gray-700 leading-relaxed text-base">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi Kegiatan</h2>
                    <div id="eventModalDescription" class="whitespace-pre-line">
                        {{-- Deskripsi event akan masuk di sini --}}
                    </div>
                </div>
                
            </div>
            
            {{-- KOLOM KANAN: Gambar Poster, Tombol Close, dan Tombol Daftar --}}
            <div class="w-5/12 flex-shrink-0 bg-gray-100 relative rounded-r-2xl overflow-hidden shadow-inner flex items-center justify-center">
                
                {{-- Container Gambar Poster dengan object-cover --}}
                {{-- Pastikan ini mengisi tinggi penuh, dan gambar di dalamnya object-cover --}}
                <div id="eventModalPoster" class="w-full h-full">
                    {{-- Poster akan masuk di sini --}}
                </div>
                
                {{-- Tombol Close di atas gambar, posisi elegan --}}
                <button data-close-modal 
                    class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/70 backdrop-blur-sm hover:bg-white flex items-center justify-center transition-colors shadow-lg border border-gray-100">
                    <i class="fas fa-times text-gray-600 text-lg"></i>
                </button>
                
                {{-- Tombol Daftar/Tutup di Bawah (Fixed) --}}
                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/50 to-transparent flex justify-center">
                    <div id="eventModalRegistrationButton">
                        {{-- Tombol pendaftaran atau tombol Tutup akan masuk di sini --}}
                    </div>
                </div>
            </div>
        </div>
    </dialog>
    {{-- END MODAL ASIMETRIS ELEGANT BARU (Revisi Penataan) --}}

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
            align-items: center; /* Center Vertikal */
            justify-content: center; /* Center Horizontal */
            max-width: 90vw; 
            max-height: 90vh;
            /* Tambahan: Pastikan modalnya tidak melebihi viewport */
            width: fit-content;
            height: fit-content;
        }

        dialog#eventModal > div.relative {
            height: 100%; /* Memastikan flex child mengisi tinggi penuh parent */
            display: flex; /* Aktifkan flex pada container kolom */
            max-height: 90vh; /* Batasi tinggi total modal */
        }
        
        dialog#eventModal[open] {
            opacity: 1;
            transform: scale(1);
        }

        /* Kolom Kiri: Konten */
        dialog#eventModal .w-7/12 {
            height: 100%; /* Pastikan mengisi tinggi penuh */
        }

        /* Kolom Kanan: Gambar Poster */
        dialog#eventModal .w-5/12 {
            height: 100%; /* Pastikan mengisi tinggi penuh */
            /* Pastikan elemen poster di tengah jika gambarnya lebih kecil */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Container Gambar Poster */
        #eventModalPoster {
            width: 100%;
            height: 100%; /* Ini penting agar div poster mengisi penuh kolom kanan */
            overflow: hidden; /* Sembunyikan jika ada bagian gambar yang keluar */
        }

        /* Gambar di dalam container poster */
        #eventModalPoster img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* KRUSIAL: Ini yang membuat gambar mengisi penuh dan tidak terdistorsi */
            display: block; /* Menghilangkan spasi bawah default pada img */
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilters = document.querySelectorAll('.category-filter');
            const eventCards = document.querySelectorAll('.event-card');
            const noEventsEmpty = document.getElementById('no-events-empty');
            const noFilteredEvents = document.getElementById('no-filtered-events');
            const eventsGrid = document.getElementById('events-grid');
            const modal = document.getElementById('eventModal');
            const transitionDuration = 300;

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

            eventCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            const showAllButton = noFilteredEvents.querySelector('.category-filter');
            if (showAllButton) {
                showAllButton.addEventListener('click', function() {
                    const allFilter = document.querySelector('.category-filter[data-category="all"]');
                    if (allFilter) {
                        allFilter.click();
                    }
                });
            }

            function closeModal() {
                if (!modal.hasAttribute('open')) return;
                modal.classList.remove('opacity-100', 'scale-100');
                modal.classList.add('opacity-0', 'scale-95');

                setTimeout(() => {
                    modal.close();
                }, transitionDuration);
            }

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            const closeButtons = document.querySelectorAll('#eventModal [data-close-modal]');
            closeButtons.forEach(button => {
                button.addEventListener('click', closeModal);
            });
        });

        /**
         * Membuka modal detail event dan mengisi kontennya.
         * @param {object} event - Objek event yang dimuat dengan relasi UKM.
         */
        function showEventDetails(event) {
            const modal = document.getElementById('eventModal');

            // 1. Isi Konten Teks (Kolom Kiri)
            document.getElementById('eventModalTitle').textContent = event.title;
            document.getElementById('eventModalUkm').textContent = event.ukm.name;

            const date = new Date(event.event_date);
            document.getElementById('eventModalDate').textContent = date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('eventModalTime').textContent = event.event_time;
            document.getElementById('eventModalLocation').textContent = event.location;

            document.getElementById('eventModalDescription').innerHTML = event.description;

            const logoContainer = document.getElementById('eventModalLogo');
            logoContainer.innerHTML = '';
            logoContainer.classList.remove('bg-gray-200');

            if (event.ukm.logo) {
                logoContainer.innerHTML =
                    `<img src="${event.ukm.logo}" alt="${event.ukm.name}" class="w-full h-full object-cover rounded-full">`;
            } else {
                logoContainer.innerHTML = `<i class="fas fa-users text-gray-400 text-sm"></i>`;
                logoContainer.classList.add('bg-gray-200');
            }

            // 2. Isi Poster & Tombol (Kolom Kanan)
            const posterContainer = document.getElementById('eventModalPoster');
            posterContainer.innerHTML = '';

            if (event.poster_image) {
                // Class object-cover diterapkan via CSS dan di HTML
                posterContainer.innerHTML =
                    `<img src="${event.poster_image}" alt="${event.title}" class="w-full h-full object-cover">`;
            } else {
                posterContainer.innerHTML = `
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-5xl"></i>
                    </div>
                `;
            }

            const registrationButtonDiv = document.getElementById('eventModalRegistrationButton');

            if (event.registration_link) {
                registrationButtonDiv.innerHTML = `
                    <a href="${event.registration_link}" target="_blank" 
                        class="px-8 py-2.5 bg-green-600 text-white rounded-full hover:bg-green-700 font-medium transition-colors shadow-xl flex items-center">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                    </a>
                `;
            } else {
                registrationButtonDiv.innerHTML = `
                    <button data-close-modal
                            class="px-8 py-2.5 bg-white/90 text-gray-800 rounded-full hover:bg-white font-medium transition-colors shadow-xl">
                        <i class="fas fa-chevron-circle-up rotate-180 mr-2 text-sm"></i> Tutup Detail
                    </button>
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