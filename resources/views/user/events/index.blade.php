@extends('layouts.user')

@section('content')
    <!-- Hero Section -->
    <section class="navbar-bg py-16 text-white text-center shadow-lg -mx-8 mb-14">
        <h1 class="text-3xl font-semibold tracking-wider">Events & Kegiatan</h1>
        <p class="mt-2 text-sm text-gray-400">Temukan event menarik dari berbagai UKM</p>
    </section>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-24">
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <!-- Categories Filter -->
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

            <!-- Events Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="events-grid">
                @foreach ($events as $event)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-[1.02] transition duration-300 event-card"
                        data-ukm="{{ $event->ukm->id }}">
                        <!-- Event Image -->
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

                            <!-- UKM Badge -->
                            <div class="absolute top-3 right-3">
                                <span
                                    class="px-2 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-xs rounded-full font-semibold shadow-sm">
                                    {{ $event->ukm->name }}
                                </span>
                            </div>

                            <!-- Date Badge -->
                            <div class="absolute top-3 left-3">
                                <div class="bg-black/70 text-white text-xs rounded-lg px-2 py-1 text-center">
                                    <div class="font-bold">{{ $event->event_date->format('d') }}</div>
                                    <div class="text-xs">{{ $event->event_date->format('M') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Event Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ Str::limit($event->description, 80) }}</p>

                            <!-- Event Details -->
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

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <button onclick="showEventDetails({{ $event }})"
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

            <!-- Empty State for No Events -->
            @if ($events->count() == 0)
                <div class="text-center py-12" id="no-events-empty">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada Events</h3>
                    <p class="text-gray-500">Belum ada event yang tersedia saat ini.</p>
                </div>
            @endif

            <!-- Empty State for Filtered Results -->
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

    <!-- Event Details Modal -->
    <dialog id="eventModal" class="bg-white rounded-2xl shadow-2xl p-0 w-full max-w-2xl backdrop:bg-black/30">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" id="eventTitle"></h3>
                        <p class="text-sm text-gray-500" id="eventUkm"></p>
                    </div>
                </div>
                <button onclick="document.getElementById('eventModal').close()"
                    class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-gray-500"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            <!-- Event Poster -->
            <div id="eventPoster"
                class="w-full h-64 bg-gray-200 rounded-xl mb-6 flex items-center justify-center overflow-hidden">
                <!-- Poster will be inserted here -->
            </div>

            <!-- Event Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-blue-600 font-medium">Tanggal</p>
                        <p class="text-gray-900 font-semibold" id="eventDate"></p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-100">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-green-600 font-medium">Waktu</p>
                        <p class="text-gray-900 font-semibold" id="eventTime"></p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-red-50 rounded-lg border border-red-100">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-map-marker-alt text-red-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-red-600 font-medium">Lokasi</p>
                        <p class="text-gray-900 font-semibold" id="eventLocation"></p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-purple-50 rounded-lg border border-purple-100">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-users text-purple-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-purple-600 font-medium">Organizer</p>
                        <p class="text-gray-900 font-semibold" id="eventOrganizer"></p>
                    </div>
                </div>
            </div>

            <!-- Event Description -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Deskripsi Event</h4>
                <p id="eventDescription" class="text-gray-700 leading-relaxed whitespace-pre-line"></p>
            </div>

            <!-- Registration Section -->
            <div id="eventRegistration"
                class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                <!-- Registration content will be inserted here -->
            </div>
        </div>

        <div class="flex justify-end p-6 border-t border-gray-200">
            <button onclick="document.getElementById('eventModal').close()"
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilters = document.querySelectorAll('.category-filter');
            const eventCards = document.querySelectorAll('.event-card');
            const noEventsEmpty = document.getElementById('no-events-empty');
            const noFilteredEvents = document.getElementById('no-filtered-events');
            const eventsGrid = document.getElementById('events-grid');

            // Hide empty state initially if there are events
            if (eventCards.length > 0 && noEventsEmpty) {
                noEventsEmpty.style.display = 'none';
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

                    // Filter event cards
                    eventCards.forEach(card => {
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

            // Add loading animation for initial cards
            eventCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Handle "Tampilkan Semua Event" button in empty state
            const showAllButton = noFilteredEvents.querySelector('.category-filter');
            if (showAllButton) {
                showAllButton.addEventListener('click', function() {
                    const allFilter = document.querySelector('.category-filter[data-category="all"]');
                    if (allFilter) {
                        allFilter.click();
                    }
                });
            }
        });

        function showEventDetails(event) {
            document.getElementById('eventTitle').textContent = event.title;
            document.getElementById('eventUkm').textContent = event.ukm.name;
            document.getElementById('eventDate').textContent = new Date(event.event_date).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('eventTime').textContent = event.event_time;
            document.getElementById('eventLocation').textContent = event.location;
            document.getElementById('eventOrganizer').textContent = event.ukm.name;
            document.getElementById('eventDescription').textContent = event.description;

            // Set poster image
            const posterContainer = document.getElementById('eventPoster');
            if (event.poster_image) {
                posterContainer.innerHTML =
                    `<img src="${event.poster_image}" alt="${event.title}" class="w-full h-full object-cover rounded-xl">`;
            } else {
                posterContainer.innerHTML = `
            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center rounded-xl">
                <i class="fas fa-calendar text-white text-4xl"></i>
            </div>
        `;
            }

            // Set registration section
            const registrationDiv = document.getElementById('eventRegistration');
            if (event.registration_link) {
                registrationDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-external-link-alt text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Pendaftaran Terbuka</p>
                        <p class="text-sm text-gray-600">Klik link di bawah untuk mendaftar</p>
                    </div>
                </div>
                <a href="${event.registration_link}" target="_blank" 
                   class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 font-medium transition-colors flex items-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </a>
            </div>
        `;
            } else {
                registrationDiv.innerHTML = `
            <div class="text-center py-2">
                <p class="text-gray-600">Untuk informasi pendaftaran, hubungi UKM penyelenggara</p>
            </div>
        `;
            }

            document.getElementById('eventModal').showModal();
        }

        // Close modal when clicking outside
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.close();
            }
        });
    </script>
@endsection
