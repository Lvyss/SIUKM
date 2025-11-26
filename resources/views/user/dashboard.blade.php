@extends('layouts.user')

@section('content')
<div class="px-4 sm:px-6 lg:px-24 py-10">
<!-- Hero Section -->
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

<!-- Trending Events -->
<section class="mb-12">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold">Trending Event</h2>
        @auth
            <a href="{{ route('user.events.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">Lihat Semua →</a>
        @else
            <button onclick="showLoginModal()" class="text-blue-600 hover:text-blue-700 font-medium">Lihat Semua →</button>
        @endauth
    </div>

    <!-- Slider Container -->
    <div class="relative">
        <div class="overflow-hidden">
            <div class="flex space-x-8 overflow-x-auto pb-4 scrollbar-hide" id="events-slider">
                @foreach($trendingEvents as $event)
                <div class="flex-shrink-0 w-1/2">
                    <!-- Clickable Event Card -->
                    @auth
                        <!-- Untuk user yang sudah login - buka modal detail -->
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
                        <!-- Untuk guest - buka modal login -->
                        <div class="flex border rounded-lg shadow-md overflow-hidden bg-white hover:shadow-lg transition-all duration-300 cursor-pointer event-card-guest"
                             onclick="showLoginModal()">
                    @endauth
                        
                        <!-- Event Image/Placeholder -->
                        <div class="w-2/5 bg-gray-100 relative flex justify-center items-center">
                            @if($event->poster_image)
                                <img src="{{ $event->poster_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Event Content -->
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
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Carousel Dots -->
        <div class="flex justify-center mt-6 space-x-2">
            @foreach($trendingEvents as $key => $event)
            <div class="carousel-dot {{ $key === 0 ? 'active' : '' }}"></div>
            @endforeach
        </div>
    </div>
</section>
<hr class="my-10">

<!-- Trending Feeds -->
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
            <!-- Untuk user yang sudah login - buka modal detail -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-all duration-300 cursor-pointer feed-card"
                 data-feed-id="{{ $feed->id }}"
                 data-feed-title="{{ $feed->title }}"
                 data-feed-content="{{ $feed->content }}"
                 data-feed-image="{{ $feed->image ?? '' }}"
                 data-feed-ukm="{{ $feed->ukm->name }}"
                 data-feed-created="{{ $feed->created_at->format('d M Y, H:i') }}"
                 data-feed-views="1.2k">
        @else
            <!-- Untuk guest - buka modal login -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-all duration-300 cursor-pointer feed-card-guest"
                 onclick="showLoginModal()">
        @endauth
            
            <!-- Image placeholder -->
            <div class="w-full h-48 bg-gray-200 flex justify-center items-center text-gray-500 mb-4 rounded overflow-hidden">
                @if($feed->image)
                    <img src="{{ $feed->image }}" alt="{{ $feed->title }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 5h8a2 2 0 002-2V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                @endif
            </div>
            
            <!-- Info UKM dan tanggal -->
            <div class="flex items-center mb-2">
                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium mr-2">
                    {{ $feed->ukm->name }}
                </span>
                <span class="text-xs text-gray-500">{{ $feed->created_at->diffForHumans() }}</span>
            </div>
            
            <!-- Title dan content -->
            <h3 class="text-lg font-semibold mb-2 line-clamp-1">{{ $feed->title }}</h3>
            <p class="text-sm text-gray-600 line-clamp-3">
                {{ Str::limit($feed->content, 100) }}
            </p>
            
            <!-- Footer dengan view count dan link -->
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
        </div>
        @endforeach
    </div>

    <!-- Empty state -->
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

<!-- Event Detail Modal -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <h3 id="modalTitle" class="text-2xl font-bold text-gray-800"></h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6">
            <!-- Event Poster -->
            <div id="modalPoster" class="w-full h-64 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                <!-- Poster image will be inserted here -->
            </div>
            
            <!-- Event Details -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-calendar mr-3 text-blue-500"></i>
                    <span id="modalDate"></span>
                </div>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-clock mr-3 text-blue-500"></i>
                    <span id="modalTime"></span>
                </div>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-map-marker-alt mr-3 text-blue-500"></i>
                    <span id="modalLocation"></span>
                </div>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-users mr-3 text-blue-500"></i>
                    <span id="modalUkm"></span>
                </div>
            </div>
            
            <!-- Event Description -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-2">Deskripsi Event</h4>
                <p id="modalDescription" class="text-gray-600 leading-relaxed"></p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button id="closeModalBtn" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Feed Detail Modal -->
<div id="feedModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div>
                <h3 id="feedModalTitle" class="text-2xl font-bold text-gray-800 mb-2"></h3>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span id="feedModalUkm" class="bg-blue-100 text-blue-700 px-2 py-1 rounded font-medium"></span>
                    <span id="feedModalDate"></span>
                    <span class="flex items-center">
                        <i class="far fa-eye mr-1"></i>
                        <span id="feedModalViews"></span>
                    </span>
                </div>
            </div>
            <button id="closeFeedModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6">
            <!-- Feed Image -->
            <div id="feedModalImage" class="w-full h-80 bg-gray-200 rounded-lg mb-6 flex items-center justify-center">
                <!-- Image will be inserted here -->
            </div>
            
            <!-- Feed Content -->
            <div class="prose max-w-none">
                <p id="feedModalContent" class="text-gray-700 leading-relaxed whitespace-pre-line"></p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex justify-end p-6 border-t bg-gray-50 rounded-b-xl">
            <button id="closeFeedModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

</div>
@endsection
@section('scripts')
<script>
// FUNCTION MODAL
function showLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
}

function showRegisterModal() {
    closeLoginModal();
    document.getElementById('registerModal').classList.remove('hidden');
}

function closeRegisterModal() {
    document.getElementById('registerModal').classList.add('hidden');
}

// KLIK EVENT CARD
document.querySelectorAll('.event-card').forEach(card => {
    card.addEventListener('click', function() {
        @if(!auth()->check())
            showLoginModal();
        @else
            // Kalo udah login, buka modal detail event (kode lama)
            const eventId = this.getAttribute('data-event-id');
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
            if (eventPoster) {
                posterContainer.innerHTML = `<img src="${eventPoster}" alt="${eventTitle}" class="w-full h-full object-cover rounded-lg">`;
            } else {
                posterContainer.innerHTML = `
                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center rounded-lg">
                        <i class="fas fa-calendar-alt text-gray-400 text-4xl"></i>
                    </div>
                `;
            }
            
            document.getElementById('eventModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        @endif
    });
});

// KLIK FEED CARD  
document.querySelectorAll('.feed-card').forEach(card => {
    card.addEventListener('click', function() {
        @if(!auth()->check())
            showLoginModal();
        @else
            // Kalo udah login, buka modal detail feed (kode lama)
            const feedId = this.getAttribute('data-feed-id');
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
            if (feedImage) {
                imageContainer.innerHTML = `<img src="${feedImage}" alt="${feedTitle}" class="w-full h-full object-cover rounded-lg">`;
            } else {
                imageContainer.innerHTML = `
                    <div class="w-full h-full bg-gradient-to-br from-purple-100 to-pink-200 flex items-center justify-center rounded-lg">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 5h8a2 2 0 002-2V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                `;
            }
            
            document.getElementById('feedModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        @endif
    });
});

// Close modal ketika klik outside
document.getElementById('loginModal').addEventListener('click', function(e) {
    if (e.target === this) closeLoginModal();
});

document.getElementById('registerModal').addEventListener('click', function(e) {
    if (e.target === this) closeRegisterModal();
});

// Close modal dengan Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLoginModal();
        closeRegisterModal();
    }
});

// Slider functionality (tetap sama)
document.addEventListener('DOMContentLoaded', function() {
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
        
        setInterval(() => {
            currentSlide = (currentSlide + 1) % dots.length;
            slider.scrollTo({ left: currentSlide * scrollAmount, behavior: 'smooth' });
            updateDots();
        }, 5000);
    }
});
</script>
@endsection