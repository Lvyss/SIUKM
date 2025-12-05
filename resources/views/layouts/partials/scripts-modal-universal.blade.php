<script>
// ==================== UNIVERSAL MODAL FUNCTIONS ====================
// Bisa dipakai di dashboard, feeds, events, dll

// Fungsi buka modal dialog
function openDialog(dialogElement) {
    if (!dialogElement) return;
    
    dialogElement.classList.remove('hidden'); 
    dialogElement.showModal();
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        dialogElement.classList.add('opacity-100', 'scale-100');
        dialogElement.classList.remove('opacity-0', 'scale-95');
    }, 10);
}

// Fungsi tutup modal dialog  
function closeDialog(dialogElement) {
    if (!dialogElement || !dialogElement.hasAttribute('open')) return;
    
    dialogElement.classList.remove('opacity-100', 'scale-100');
    dialogElement.classList.add('opacity-0', 'scale-95');

    setTimeout(() => {
        dialogElement.close();
        document.body.style.overflow = '';
    }, 300);
}

// Fungsi setup modal event (reusable)
// Fungsi setup modal event (reusable)
function setupEventModal() {
    const eventModal = document.getElementById('eventModal');
    if (!eventModal) return;
    
    // Bind close buttons (desktop & mobile)
    const closeBtn1 = document.getElementById('closeEventModal');
    const closeBtn2 = document.getElementById('closeEventModalBtn');
    const closeBtnMobile = document.getElementById('closeEventModalMobile');
    
    if (closeBtn1) closeBtn1.addEventListener('click', () => closeDialog(eventModal));
    if (closeBtn2) closeBtn2.addEventListener('click', () => closeDialog(eventModal));
    if (closeBtnMobile) closeBtnMobile.addEventListener('click', () => closeDialog(eventModal));
    
    // Backdrop click
    eventModal.addEventListener('click', function(e) {
        if (e.target === eventModal) closeDialog(eventModal);
    });
}

// Fungsi setup modal feed (reusable)
function setupFeedModal() {
    const feedModal = document.getElementById('feedModal');
    if (!feedModal) return;
    
    // Bind close buttons (desktop & mobile)
    const closeBtn1 = document.getElementById('closeFeedModal');
    const closeBtn2 = document.getElementById('closeFeedModalBtn');
    const closeBtnMobile = document.getElementById('closeFeedModalMobile');
    const closeBtnMobileBottom = document.getElementById('closeFeedModalBtnMobile');
    
    if (closeBtn1) closeBtn1.addEventListener('click', () => closeDialog(feedModal));
    if (closeBtn2) closeBtn2.addEventListener('click', () => closeDialog(feedModal));
    if (closeBtnMobile) closeBtnMobile.addEventListener('click', () => closeDialog(feedModal));
    if (closeBtnMobileBottom) closeBtnMobileBottom.addEventListener('click', () => closeDialog(feedModal));
    
    // Backdrop click
    feedModal.addEventListener('click', function(e) {
        if (e.target === feedModal) closeDialog(feedModal);
    });
}
// ==================== FUNGSI ISI DATA KE MODAL ====================

// Fungsi isi data event ke modal
function fillEventModalData(eventData) {
    if (!eventData) return;
    
    // Isi konten teks
    document.getElementById('eventModalTitle').textContent = eventData.title || '';
    document.getElementById('eventModalUkm').textContent = eventData.ukm?.name || eventData.ukm || '';
    
    // Format tanggal
    if (eventData.event_date || eventData.date) {
        const date = new Date(eventData.event_date || eventData.date);
        const formattedDate = date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('eventModalDate').textContent = formattedDate;
    }
    
    document.getElementById('eventModalTime').textContent = eventData.event_time || eventData.time || '';
    document.getElementById('eventModalLocation').textContent = eventData.location || '';
    document.getElementById('eventModalDescription').innerHTML = eventData.description || '';
    
    // Poster image untuk desktop dan mobile
    const posterImage = eventData.poster_image || eventData.poster;
    
    // Desktop poster
    const posterContainer = document.getElementById('eventModalPoster');
    if (posterContainer) {
        posterContainer.innerHTML = '';
        if (posterImage) {
            posterContainer.innerHTML = `<img src="${posterImage}" alt="${eventData.title}" class="w-full h-full object-cover">`;
        } else {
            posterContainer.innerHTML = `
                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-3xl md:text-5xl"></i>
                </div>
            `;
        }
    }
    
    // Mobile poster
    const posterMobileContainer = document.getElementById('eventModalPosterMobile');
    if (posterMobileContainer) {
        posterMobileContainer.innerHTML = '';
        if (posterImage) {
            posterMobileContainer.innerHTML = `<img src="${posterImage}" alt="${eventData.title}" class="w-full h-full object-cover">`;
        } else {
            posterMobileContainer.innerHTML = `
                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-3xl"></i>
                </div>
            `;
        }
    }
    
    // Registration button untuk desktop dan mobile
    const registrationButtonDiv = document.getElementById('eventModalRegistrationButton');
    const registrationButtonMobileDiv = document.getElementById('eventModalRegistrationButtonMobile');
    
    if (eventData.registration_link) {
        const buttonHTML = `
            <a href="${eventData.registration_link}" target="_blank" 
                class="w-full md:w-auto px-6 md:px-8 py-2.5 bg-green-600 text-white rounded-full hover:bg-green-700 font-medium transition-colors shadow text-center">
                <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
            </a>
        `;
        
        if (registrationButtonDiv) registrationButtonDiv.innerHTML = buttonHTML;
        if (registrationButtonMobileDiv) registrationButtonMobileDiv.innerHTML = buttonHTML;
    } else {
        const buttonHTML = `
            <button onclick="ModalFunctions.closeDialog(document.getElementById('eventModal'))"
                    class="w-full md:w-auto px-6 md:px-8 py-2.5 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300 font-medium transition-colors shadow text-center">
                <i class="fas fa-times mr-2"></i> Tutup
            </button>
        `;
        
        if (registrationButtonDiv) registrationButtonDiv.innerHTML = buttonHTML;
        if (registrationButtonMobileDiv) registrationButtonMobileDiv.innerHTML = buttonHTML;
    }
}

// Fungsi isi data feed ke modal (VERSI BARU - format tanggal)
// Fungsi isi data feed ke modal (VERSI BARU - format tanggal)
function fillFeedModalData(feedData) {
    if (!feedData) return;
    
    // Isi konten teks
    document.getElementById('feedModalTitle').textContent = feedData.title || '';
    document.getElementById('feedModalUkm').textContent = feedData.ukm?.name || feedData.ukm || '';
    
    // Format tanggal dari string/ISO
    if (feedData.created_at) {
        const date = new Date(feedData.created_at);
        const formattedDate = date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('feedModalDate').textContent = formattedDate;
    } else {
        document.getElementById('feedModalDate').textContent = feedData.date || '';
    }
    
    // Views dengan pengecekan
    const views = feedData.views || feedData.views_count || '1.2k';
    document.getElementById('feedModalViews').textContent = (typeof views === 'number' ? views.toLocaleString() : views) + ' dilihat';
    
    // Konten
    document.getElementById('feedModalContent').innerHTML = feedData.content || '';
    
    // Gambar untuk desktop
    const imageContainer = document.getElementById('feedModalImage');
    if (imageContainer) {
        imageContainer.innerHTML = '';
        if (feedData.image) {
            imageContainer.innerHTML = `<img src="${feedData.image}" alt="${feedData.title}" class="w-full h-full object-cover">`;
        } else {
            imageContainer.innerHTML = `
                <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                    <i class="fas fa-image text-white text-3xl md:text-5xl"></i>
                </div>
            `;
        }
    }
    
    // Gambar untuk mobile
    const imageMobileContainer = document.getElementById('feedModalImageMobile');
    if (imageMobileContainer) {
        imageMobileContainer.innerHTML = '';
        if (feedData.image) {
            imageMobileContainer.innerHTML = `<img src="${feedData.image}" alt="${feedData.title}" class="w-full h-full object-cover">`;
        } else {
            imageMobileContainer.innerHTML = `
                <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                    <i class="fas fa-image text-white text-3xl"></i>
                </div>
            `;
        }
    }
}
// ==================== FUNGSI BIND CARD (UNTUK DASHBOARD) ====================

// Fungsi bind event card ke modal (untuk dashboard)
// ==================== FUNGSI BIND CARD (UNTUK DASHBOARD) ====================

// Fungsi bind event card ke modal (untuk dashboard)
function bindEventCardsToModal() {
    document.querySelectorAll('.event-card').forEach(card => {
        card.addEventListener('click', function() {
            const eventData = {
                title: this.getAttribute('data-event-title'),
                description: this.getAttribute('data-event-description'),
                event_date: this.getAttribute('data-event-date'),  // GANTI 'date' -> 'event_date'
                event_time: this.getAttribute('data-event-time'),   // GANTI 'time' -> 'event_time'
                location: this.getAttribute('data-event-location'),
                poster_image: this.getAttribute('data-event-poster'), // GANTI 'poster' -> 'poster_image'
                ukm: {
                    name: this.getAttribute('data-event-ukm')
                }
                // HAPUS: date, time, poster (karena sudah diganti)
            };
            
            // Debug: cek data
            console.log('Event data from card:', eventData);
            
            fillEventModalData(eventData);
            openDialog(document.getElementById('eventModal'));
        });
    });
}

// Fungsi bind feed card ke modal (untuk dashboard)
function bindFeedCardsToModal() {
    document.querySelectorAll('.feed-card').forEach(card => {
        card.addEventListener('click', function() {
            const feedData = {
                title: this.getAttribute('data-feed-title'),
                content: this.getAttribute('data-feed-content'),
                image: this.getAttribute('data-feed-image'),
                created_at: this.getAttribute('data-feed-created'),
                views: this.getAttribute('data-feed-views'),
                ukm: {
                    name: this.getAttribute('data-feed-ukm')
                }
            };
            
            fillFeedModalData(feedData);
            openDialog(document.getElementById('feedModal'));
        });
    });
}

// Fungsi inisialisasi semua modal
function initAllModals() {
    setupEventModal();
    setupFeedModal();
    
    // Escape key untuk semua modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const eventModal = document.getElementById('eventModal');
            const feedModal = document.getElementById('feedModal');
            
            if (eventModal && eventModal.hasAttribute('open')) closeDialog(eventModal);
            if (feedModal && feedModal.hasAttribute('open')) closeDialog(feedModal);
        }
    });
}

// Export fungsi agar bisa dipanggil dari file lain
window.ModalFunctions = {
    openDialog,
    closeDialog,
    setupEventModal,
    setupFeedModal,
    fillEventModalData,      // INI YANG BARU
    fillFeedModalData,
    bindEventCardsToModal,
    bindFeedCardsToModal,
    initAllModals
};
</script>