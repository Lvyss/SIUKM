@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-10">
        <div class="h-48 md:h-64 relative bg-gray-200">
            @if($ukm->logo)
                <img src="{{ $ukm->logo }}" alt="Cover {{ $ukm->name }}" class="w-full h-full object-cover brightness-75 blur-[5px]">
            @else
                <div class="w-full h-full bg-gradient-to-r from-blue-50 to-indigo-100 flex items-center justify-center">
                    <i class="fas fa-image text-5xl text-gray-400"></i>
                </div>
            @endif
            
            <div class="absolute top-6 right-6">
                <span class="px-4 py-2 bg-white/90 backdrop-blur-sm text-blue-600 text-sm font-medium rounded-full shadow-lg border border-blue-100">
                    {{ $ukm->category->name ?? 'No Category' }}
                </span>
            </div>
            
            <div class="absolute bottom-6 right-6">
                @if(!$isRegistered)
                    <button id="openRegisterModalButton"
                            class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-5 py-2.5 rounded-xl hover:shadow-lg hover:shadow-green-300 transition-all duration-300 font-semibold text-sm flex items-center group">
                        <i class="fas fa-user-plus mr-2 group-hover:scale-105 transition-transform"></i>
                        Join UKM
                    </button>
                    @else
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center">
                        <i class="fas fa-check-circle mr-2 text-emerald-500"></i>
                        Sudah Terdaftar
                    </div>
                @endif
            </div>
        </div>
        
        <div class="px-8 pb-8 pt-4 flex flex-col md:flex-row gap-6">
            
            <div class="relative z-10 -mt-24 md:-mt-16 flex-shrink-0">
                <div class="w-32 h-32 md:w-40 md:h-40 border-4 border-white rounded-full overflow-hidden bg-white shadow-xl">
                    @if($ukm->logo)
                        <img src="{{ $ukm->logo }}" alt="Logo {{ $ukm->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <i class="fas fa-users text-4xl text-gray-500"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex-1 pt-2 md:pt-4">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $ukm->name }}</h1>
                        <p class="text-gray-600 text-sm italic">
                            @if($ukm->instagram)
                                <a href="https://instagram.com/{{ ltrim($ukm->instagram, '@') }}" target="_blank" class="hover:text-pink-600 transition-colors">
                                    <i class="fab fa-instagram mr-1"></i> {{ $ukm->instagram }}
                                </a>
                            @else
                                <i class="fas fa-hashtag mr-1"></i> #{{ Str::slug($ukm->name, '') }}
                            @endif
                        </p>
                    </div>
                    
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-800">{{ $ukm->registrations()->where('status', 'approved')->count() }}</p>
                            <p class="text-sm text-gray-500">ACTIVE MEMBERS</p>
                        </div>
                    </div>
                </div>

                <p class="text-lg text-gray-700 leading-relaxed mt-4">{{ $ukm->description }}</p>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            
            @if($ukm->vision || $ukm->mission)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                @if($ukm->vision)
                <div class="p-4 border border-purple-100 rounded-xl bg-purple-50">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-200 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-eye text-purple-700 text-sm"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Visi</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed text-sm">{{ $ukm->vision }}</p>
                </div>
                @endif
                
                @if($ukm->mission)
                <div class="p-4 border border-green-100 rounded-xl bg-green-50">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-200 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bullseye text-green-700 text-sm"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Misi</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed text-sm">{{ $ukm->mission }}</p>
                </div>
                @endif
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Event Mendatang</h2>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600 text-lg"></i>
                    </div>
                </div>
                
                @if($ukm->events->where('event_date', '>=', now())->count() > 0)
                    <div class="space-y-4">
                        @foreach($ukm->events->where('event_date', '>=', now())->take(3) as $event)
                        <div class="group p-4 border border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all duration-300 cursor-pointer flex items-start space-x-4">
                            <div class="flex-shrink-0 w-16 h-16 bg-blue-600 text-white rounded-lg flex flex-col items-center justify-center">
                                <span class="text-lg font-bold leading-none">{{ $event->event_date->format('d') }}</span>
                                <span class="text-xs uppercase leading-none">{{ $event->event_date->format('M') }}</span>
                            </div>

                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-blue-700 transition-colors">{{ $event->title }}</h3>
                                <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $event->description }}</p>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1.5 text-green-500"></i>
                                        {{ $event->event_time }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1.5 text-red-500"></i>
                                        {{ $event->location }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Belum ada event mendatang</p>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Feed Terbaru</h2>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-newspaper text-purple-600 text-lg"></i>
                    </div>
                </div>
                
                @if($ukm->feeds->count() > 0)
                    <div class="space-y-4">
                        @foreach($ukm->feeds->take(3) as $feed)
                        <div class="p-4 border border-gray-200 rounded-xl hover:shadow-lg transition-all duration-300 flex items-start gap-4">
                            @if($feed->image)
                                <img src="{{ $feed->image }}" alt="{{ $feed->title }}" 
                                        class="w-24 h-24 rounded-xl object-cover flex-shrink-0">
                            @else
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-images text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $feed->title }}</h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($feed->content, 120) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500 flex items-center">
                                        <i class="fas fa-clock mr-1"></i> {{ $feed->created_at->diffForHumans() }}
                                    </span>
                                    <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                                        Baca Selengkapnya
                                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-newspaper text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Belum ada feed tersedia</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="text-xl font-bold text-gray-900 border-b pb-3 mb-3 flex items-center">
                    <i class="fas fa-address-card mr-2 text-blue-500"></i> Informasi Kontak
                </h2>
                
                @if($ukm->contact_person)
                <div class="flex items-center space-x-3 p-2 bg-blue-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-phone-alt text-blue-600 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Contact Person (WA)</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $ukm->contact_person }}</p>
                    </div>
                </div>
                @endif
                
                @if($ukm->instagram)
                <div class="flex items-center space-x-3 p-2 bg-pink-50 rounded-lg">
                    <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fab fa-instagram text-pink-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Instagram</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $ukm->instagram }}</p>
                    </div>
                </div>
                @endif
                
                @if($ukm->email_ukm)
                <div class="flex items-center space-x-3 p-2 bg-red-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-envelope text-red-600 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Email</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $ukm->email_ukm }}</p>
                    </div>
                </div>
                @endif
                
                @if(!$ukm->contact_person && !$ukm->instagram && !$ukm->email_ukm)
                    <p class="text-center text-gray-500 text-sm py-4">Informasi kontak belum tersedia.</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">UKM Serupa</h2>
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-orange-600 text-lg"></i>
                    </div>
                </div>
                
                @if($similarUkms->count() > 0)
                    <div class="space-y-4">
                        @foreach($similarUkms as $similar)
                        <a href="{{ route('user.ukm.detail', $similar->id) }}" 
                           class="flex items-center p-3 border border-gray-200 rounded-xl hover:border-orange-400 hover:bg-orange-50 transition-all duration-300 group">
                            @if($similar->logo)
                                <img src="{{ $similar->logo }}" alt="{{ $similar->name }}" 
                                        class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-white group-hover:border-orange-400 transition-colors shadow-sm">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mr-4 border-2 border-white group-hover:border-orange-400 transition-colors">
                                    <i class="fas fa-users text-gray-400 text-sm"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors line-clamp-1">{{ $similar->name }}</h3>
                                <p class="text-xs text-gray-500 line-clamp-1">{{ Str::limit($similar->description, 40) }}</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-orange-500 transition-colors ml-2 text-sm"></i>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Tidak ada UKM serupa</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(!$isRegistered)
<dialog id="registerModal" 
    class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-auto backdrop:bg-black/40 backdrop:backdrop-blur-sm 
           transform transition-all duration-300 max-h-[90vh] flex flex-col overflow-hidden">
    
    <div class="relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-t-3xl p-6 text-white flex-shrink-0">
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-user-plus text-xl text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold leading-tight">Daftar Anggota UKM</h3>
                    <p class="text-sm opacity-90">{{ $ukm->name }}</p>
                </div>
            </div>
            <button id="closeModalHeader" 
                    class="w-8 h-8 rounded-full hover:bg-white/20 flex items-center justify-center transition-colors flex-shrink-0 -mt-1">
                <i class="fas fa-times text-white"></i>
            </button>
        </div>
    </div>
    
    <form id="registerForm" action="/user/ukm/{{ $ukm->id }}/register" method="POST" 
          class="p-8 space-y-6 overflow-y-auto flex-1">
        @csrf
        
        <p class="text-gray-600 text-sm italic border-l-4 border-emerald-400 pl-3 py-1 bg-emerald-50 rounded-md">
            Lengkapi formulir di bawah untuk mengirimkan permohonan pendaftaran.
        </p>

        <div>
            <label for="motivation" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                <i class="fas fa-lightbulb text-orange-500 mr-2"></i> Motivasi *
            </label>
            <textarea name="motivation" id="motivation" required 
                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none placeholder:text-gray-400"
                      rows="4" 
                      placeholder="Ceritakan mengapa Anda ingin bergabung dengan UKM ini dan harapan Anda..."></textarea>
        </div>
        
        <div>
            <label for="experience" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                <i class="fas fa-history text-blue-500 mr-2"></i> Pengalaman (Opsional)
            </label>
            <textarea name="experience" id="experience"
                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none placeholder:text-gray-400"
                      rows="2" 
                      placeholder="Pengalaman relevan di bidang ini (misalnya, organisasi, lomba, proyek)..."></textarea>
        </div>
        
        <div>
            <label for="skills" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                <i class="fas fa-tools text-purple-500 mr-2"></i> Keahlian (Opsional)
            </label>
            <textarea name="skills" id="skills"
                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none placeholder:text-gray-400"
                      rows="2" 
                      placeholder="Sebutkan keahlian khusus yang dapat Anda kontribusikan (dipisahkan koma)..."></textarea>
        </div>
        
    </form>
    
    <div class="flex justify-end space-x-3 p-6 pt-0 border-t border-gray-100 flex-shrink-0">
        <button type="button" data-close-modal 
                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors duration-200 shadow-sm">
            Batal
        </button>
        <button type="submit" form="registerForm" 
                class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-lg hover:shadow-green-300/50 font-semibold transition-all duration-300 transform hover:scale-[1.02]">
            <i class="fas fa-paper-plane mr-2"></i> Kirim Pendaftaran
        </button>
    </div>
    
</dialog>
@endif

<style>
/* CSS Line Clamp */
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
    const modal = document.getElementById('registerModal');
    
    if (modal) {
        const transitionDuration = 300;

        function openModal() {
            modal.showModal();
            setTimeout(() => {
                modal.classList.add('opacity-100', 'scale-100');
                modal.classList.remove('opacity-0', 'scale-95');
            }, 10);
        }

        function closeModal() {
            modal.classList.remove('opacity-100', 'scale-100');
            modal.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                modal.close();
            }, transitionDuration);
        }

        // --- 1. Tombol Pembuka Modal ---
        const openButton = document.getElementById('openRegisterModalButton');
        if (openButton) {
            openButton.addEventListener('click', openModal);
        }

        // --- 2. Menutup Modal saat Klik Backdrop (DIPERBAIKI) ---
        modal.addEventListener('click', function(e) {
            // Hanya tutup jika klik tepat di backdrop (bukan di konten modal)
            if (e.target === modal) {
                closeModal();
            }
        });

        // --- 3. Tombol Tutup di Header ---
        const closeModalHeader = document.getElementById('closeModalHeader');
        if (closeModalHeader) {
            closeModalHeader.addEventListener('click', closeModal);
        }

        // --- 4. Tombol Batal/Tutup lainnya ---
        const closeButtons = document.querySelectorAll('[data-close-modal]');
        closeButtons.forEach(button => {
            button.addEventListener('click', closeModal);
        });

        // --- PERBAIKAN: Mencegah event bubbling dari form ---
        const form = document.getElementById('registerForm');
        if (form) {
            form.addEventListener('click', function(e) {
                // Hentikan event bubbling agar tidak sampai ke modal
                e.stopPropagation();
            });
        }

        // --- PERBAIKAN TAMBAHAN: Mencegah event bubbling dari semua input di dalam modal ---
        const modalContent = modal.querySelector('.bg-white');
        if (modalContent) {
            modalContent.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    }
});
</script>
@endsection