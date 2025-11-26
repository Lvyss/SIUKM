@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- UKM Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <!-- Cover Image -->
        <div class="h-72 bg-gradient-to-r from-blue-50 to-indigo-100 relative overflow-hidden">
            @if($ukm->logo)
                <div class="absolute inset-0 flex items-center justify-center">
                    <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" class="w-32 h-32 rounded-2xl shadow-lg object-cover border-4 border-white">
                </div>
            @else
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-32 h-32 bg-white rounded-2xl shadow-lg flex items-center justify-center border-4 border-white">
                        <i class="fas fa-users text-blue-400 text-4xl"></i>
                    </div>
                </div>
            @endif
            
            <!-- Category Badge -->
            <div class="absolute top-6 left-6">
<span class="px-4 py-2 bg-white/90 backdrop-blur-sm text-blue-600 text-sm font-medium rounded-full shadow-sm border border-blue-100">
        {{ $ukm->category->name ?? 'No Category' }}
</span>
            </div>
        </div>
        
        <!-- UKM Info -->
        <div class="px-8 pb-8 pt-16">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-8">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $ukm->name }}</h1>
                    <p class="text-lg text-gray-600 leading-relaxed">{{ $ukm->description }}</p>
                </div>
                
                <!-- Action Button -->
                <div class="flex-shrink-0">
                    @if(!$isRegistered)
                    <button onclick="document.getElementById('registerModal').showModal()"
                            class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-3 rounded-xl hover:shadow-lg transition-all duration-300 font-semibold flex items-center group">
                        <i class="fas fa-user-plus mr-3 group-hover:scale-110 transition-transform"></i>
                        Join UKM
                    </button>
                    @else
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-3 rounded-xl font-semibold flex items-center">
                        <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
                        Sudah Terdaftar
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                @if($ukm->contact_person)
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Contact Person</p>
                            <p class="text-gray-900 font-semibold">{{ $ukm->contact_person }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($ukm->instagram)
                <div class="bg-pink-50 rounded-xl p-4 border border-pink-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fab fa-instagram text-pink-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-pink-600 font-medium">Instagram</p>
                            <p class="text-gray-900 font-semibold">{{ $ukm->instagram }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($ukm->email_ukm)
                <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-envelope text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-red-600 font-medium">Email</p>
                            <p class="text-gray-900 font-semibold">{{ $ukm->email_ukm }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Vision & Mission -->
            @if($ukm->vision || $ukm->mission)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @if($ukm->vision)
                <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-6 border border-purple-100">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-eye text-purple-600 text-sm"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Visi</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $ukm->vision }}</p>
                </div>
                @endif
                
                @if($ukm->mission)
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bullseye text-green-600 text-sm"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Misi</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $ukm->mission }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Events & Feeds -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Upcoming Events -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Event Mendatang</h2>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600"></i>
                    </div>
                </div>
                
                @if($ukm->events->where('event_date', '>=', now())->count() > 0)
                    <div class="space-y-4">
                        @foreach($ukm->events->where('event_date', '>=', now())->take(3) as $event)
                        <div class="group p-4 border border-gray-200 rounded-xl hover:border-blue-200 hover:bg-blue-50/50 transition-all duration-300 cursor-pointer">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">{{ $event->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $event->description }}</p>
                                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                            {{ $event->event_date->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-2 text-green-500"></i>
                                            {{ $event->event_time }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                            {{ $event->location }}
                                        </span>
                                    </div>
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

            <!-- Recent Feeds -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Feed Terbaru</h2>
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-newspaper text-purple-600"></i>
                    </div>
                </div>
                
                @if($ukm->feeds->count() > 0)
                    <div class="space-y-4">
                        @foreach($ukm->feeds->take(3) as $feed)
                        <div class="p-4 border border-gray-200 rounded-xl hover:shadow-md transition-all duration-300">
                            <div class="flex items-start gap-4">
                                @if($feed->image)
                                    <img src="{{ $feed->image }}" alt="{{ $feed->title }}" 
                                         class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                                @else
                                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-newspaper text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-2">{{ $feed->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($feed->content, 120) }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">{{ $feed->created_at->diffForHumans() }}</span>
                                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                                            Baca Selengkapnya
                                            <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                        </button>
                                    </div>
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

        <!-- Right Column - Similar UKM -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">UKM Serupa</h2>
                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-orange-600"></i>
                </div>
            </div>
            
            @if($similarUkms->count() > 0)
                <div class="space-y-4">
                    @foreach($similarUkms as $similar)
                    <a href="{{ route('user.ukm.detail', $similar->id) }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-orange-200 hover:bg-orange-50/50 transition-all duration-300 group">
                        @if($similar->logo)
                            <img src="{{ $similar->logo }}" alt="{{ $similar->name }}" 
                                 class="w-12 h-12 rounded-xl object-cover mr-4 border-2 border-white group-hover:border-orange-200 transition-colors">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mr-4 border-2 border-white group-hover:border-orange-200 transition-colors">
                                <i class="fas fa-users text-gray-400 text-sm"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">{{ $similar->name }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-1">{{ Str::limit($similar->description, 40) }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-orange-500 transition-colors ml-2"></i>
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

<!-- Register Modal -->
@if(!$isRegistered)
<dialog id="registerModal" class="bg-white rounded-2xl shadow-2xl p-0 w-full max-w-lg backdrop:bg-black/30">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-plus text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Bergabung dengan {{ $ukm->name }}</h3>
                    <p class="text-sm text-gray-500">Isi form pendaftaran di bawah</p>
                </div>
            </div>
            <button onclick="document.getElementById('registerModal').close()" 
                    class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
    </div>
    
<form action="/user/ukm/{{ $ukm->id }}/register" method="POST" class="p-6">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Motivasi *</label>
                <textarea name="motivation" required 
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                          rows="4" 
                          placeholder="Ceritakan mengapa Anda ingin bergabung dengan UKM ini..."></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pengalaman (Opsional)</label>
                <textarea name="experience" 
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                          rows="2" 
                          placeholder="Pengalaman relevan yang Anda miliki..."></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keahlian (Opsional)</label>
                <textarea name="skills" 
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                          rows="2" 
                          placeholder="Keahlian khusus yang dapat Anda kontribusikan..."></textarea>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
            <button type="button" onclick="document.getElementById('registerModal').close()" 
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors">
                Batal
            </button>
            <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-lg font-medium transition-all duration-300">
                Kirim Pendaftaran
            </button>
        </div>
    </form>
</dialog>
@endif

<style>
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
// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('registerModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.close();
            }
        });
    }
});
</script>
@endsection