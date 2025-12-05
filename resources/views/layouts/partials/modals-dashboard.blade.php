<dialog id="eventModal" 
    class="bg-white rounded-lg md:rounded-2xl shadow-lg md:shadow-2xl p-0 w-full max-w-[95vw] md:max-w-5xl max-h-[90vh] 
        backdrop:bg-black/40 backdrop:backdrop-blur-sm 
        transform transition-all duration-300 opacity-0 scale-95 flex flex-col overflow-hidden">
    
    <div class="relative flex flex-col md:flex-row h-full">
        
        {{-- KOLOM ATAS (MOBILE): Gambar Poster --}}
        <div class="md:hidden w-full h-48 bg-gray-100 relative">
            <div id="eventModalPosterMobile" class="w-full h-full">
                {{-- Poster untuk mobile --}}
            </div>
            <button id="closeEventModalMobile"
                class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white flex items-center justify-center transition-colors shadow border border-gray-100">
                <i class="fas fa-times text-gray-600 text-sm"></i>
            </button>
        </div>
        
        {{-- KOLOM KIRI: Judul, Meta, dan Konten Teks --}}
        <div class="w-full md:w-7/12 flex-shrink-0 p-4 md:p-8 overflow-y-auto max-h-[90vh] pb-16 md:pb-20">
            
            {{-- Tombol Close Desktop --}}
            <button id="closeEventModal"
                class="hidden md:flex absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/70 backdrop-blur-sm hover:bg-white items-center justify-center transition-colors shadow-lg border border-gray-100">
                <i class="fas fa-times text-gray-600 text-lg"></i>
            </button>

            {{-- Header/Judul --}}
            <h1 class="text-2xl md:text-4xl font-bold md:font-extrabold text-gray-900 mb-4 md:mb-6 leading-tight" id="eventModalTitle">Judul Event</h1>
            
            {{-- UKM & Meta Info Block --}}
            <div class="mb-6 md:mb-8 p-3 md:p-4 bg-gray-50 rounded-lg md:rounded-xl border border-gray-100 space-y-2 md:space-y-3">
                
                {{-- UKM Info / Organizer --}}
                <div class="flex items-center text-sm md:text-base font-semibold text-gray-700">
                    <div id="eventModalLogo" class="w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center mr-2 md:mr-3 border border-gray-200 flex-shrink-0 overflow-hidden">
                        {{-- Logo akan dimasukkan di sini --}}
                    </div>
                    <span id="eventModalUkm" class="text-sm md:text-base font-bold text-blue-600">Nama UKM (Organizer)</span>
                </div>
                
                {{-- Tanggal, Waktu, Lokasi --}}
                <div class="space-y-2 md:space-y-3 pt-2 border-t border-gray-200">
                    <div class="flex items-center text-xs md:text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-2 md:mr-3 text-blue-500 w-3 md:w-4"></i>
                        <span id="eventModalDate">Tanggal Event</span>
                    </div>
                    
                    <div class="flex items-center text-xs md:text-sm text-gray-600">
                        <i class="fas fa-clock mr-2 md:mr-3 text-green-500 w-3 md:w-4"></i>
                        <span id="eventModalTime">Waktu Event</span>
                    </div>

                    <div class="flex items-start text-xs md:text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2 md:mr-3 text-red-500 mt-0.5 md:mt-1 w-3 md:w-4"></i>
                        <span id="eventModalLocation" class="font-medium">Lokasi Event</span>
                    </div>
                </div>
            </div>
            
            {{-- Konten Utama (Deskripsi) --}}
            <div class="prose max-w-none text-gray-700 leading-relaxed text-sm md:text-base">
                <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4">Deskripsi Kegiatan</h2>
                <div id="eventModalDescription" class="whitespace-pre-line text-sm md:text-base">
                    {{-- Deskripsi event akan masuk di sini --}}
                </div>
            </div>
            
        </div>
        
        {{-- KOLOM KANAN (DESKTOP): Gambar Poster --}}
        <div class="hidden md:flex w-5/12 flex-shrink-0 bg-gray-100 relative rounded-r-2xl overflow-hidden shadow-inner items-center justify-center">
            
            {{-- Container Gambar Poster dengan object-cover --}}
            <div id="eventModalPoster" class="w-full h-full">
                {{-- Poster akan masuk di sini --}}
            </div>
            
            {{-- Tombol Daftar/Tutup di Bawah (Fixed) --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/50 to-transparent flex justify-center">
                <div id="eventModalRegistrationButton">
                    {{-- Tombol akan dimasukkan di sini oleh JavaScript --}}
                </div>
            </div>
        </div>
        
        {{-- FOOTER MOBILE: Tombol Aksi --}}
        <div class="md:hidden fixed bottom-0 left-0 right-0 p-3 bg-white border-t border-gray-200 shadow-lg">
            <div id="eventModalRegistrationButtonMobile" class="flex justify-center">
                {{-- Tombol untuk mobile --}}
            </div>
        </div>
    </div>
</dialog>

<dialog id="feedModal" 
    class="bg-white rounded-lg md:rounded-2xl shadow-lg md:shadow-2xl p-0 w-full max-w-[95vw] md:max-w-5xl max-h-[90vh] 
        backdrop:bg-black/40 backdrop:backdrop-blur-sm 
        transform transition-all duration-300 opacity-0 scale-95 flex flex-col overflow-hidden">
    
    <div class="relative flex flex-col md:flex-row h-full">
        
        {{-- KOLOM ATAS (MOBILE): Gambar Feed --}}
        <div class="md:hidden w-full h-48 bg-gray-100 relative">
            <div id="feedModalImageMobile" class="w-full h-full">
                {{-- Gambar untuk mobile --}}
            </div>
            <button id="closeFeedModalMobile"
                class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white flex items-center justify-center transition-colors shadow border border-gray-100">
                <i class="fas fa-times text-gray-600 text-sm"></i>
            </button>
        </div>
        
        {{-- KOLOM KIRI: Judul, Meta, dan Konten Teks --}}
        <div class="w-full md:w-7/12 flex-shrink-0 p-4 md:p-8 overflow-y-auto max-h-[90vh] pb-16 md:pb-20">
            
            {{-- Tombol Close Desktop --}}
            <button id="closeFeedModal"
                class="hidden md:flex absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/70 backdrop-blur-sm hover:bg-white items-center justify-center transition-colors shadow-lg border border-gray-100">
                <i class="fas fa-times text-gray-600 text-lg"></i>
            </button>

            {{-- Header/Judul --}}
            <h1 class="text-2xl md:text-4xl font-bold md:font-extrabold text-gray-900 mb-4 md:mb-6 leading-tight" id="feedModalTitle">Judul Feed</h1>
            
            {{-- Meta Info Block --}}
            <div class="mb-6 md:mb-8 p-3 md:p-4 bg-gray-50 rounded-lg md:rounded-xl border border-gray-100 space-y-2 md:space-y-3">
                
                <div class="flex items-center text-sm md:text-base font-semibold text-gray-700">
                    <i class="fas fa-users mr-2 md:mr-3 text-blue-600 w-3 md:w-4"></i>
                    <span id="feedModalUkm" class="text-sm md:text-base font-bold text-blue-600">Nama UKM</span>
                </div>
                
                <div class="space-y-2 md:space-y-3 pt-2 border-t border-gray-200">
                    <div class="flex items-center text-xs md:text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-2 md:mr-3 text-blue-500 w-3 md:w-4"></i>
                        <span id="feedModalDate">Tanggal Posting</span>
                    </div>
                    
                    <div class="flex items-center text-xs md:text-sm text-gray-600">
                        <i class="far fa-eye mr-2 md:mr-3 text-red-500 w-3 md:w-4"></i>
                        <span id="feedModalViews">Jumlah Dilihat</span>
                    </div>
                </div>
            </div>
            
            {{-- Konten Utama --}}
            <div class="prose max-w-none text-gray-700 leading-relaxed text-sm md:text-base">
                <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4">Isi Konten</h2>
                <p id="feedModalContent" class="whitespace-pre-line text-sm md:text-base">
                    {{-- Isi Feed akan masuk di sini --}}
                </p>
            </div>
            
        </div>
        
        {{-- KOLOM KANAN (DESKTOP): Gambar Feed --}}
        <div class="hidden md:flex w-5/12 flex-shrink-0 bg-gray-100 relative rounded-r-2xl overflow-hidden shadow-inner items-center justify-center">
            
            {{-- Container Gambar Feed dengan object-cover --}}
            <div id="feedModalImage" class="w-full h-full">
                {{-- Gambar akan masuk di sini --}}
            </div>
            
 
        </div>
        
        {{-- FOOTER MOBILE: Tombol Tutup --}}
        <div class="md:hidden fixed bottom-0 left-0 right-0 p-3 bg-white border-t border-gray-200 shadow-lg">
            <button id="closeFeedModalBtnMobile"
                    class="w-full py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                <i class="fas fa-times mr-2"></i> Tutup
            </button>
        </div>
    </div>
</dialog>