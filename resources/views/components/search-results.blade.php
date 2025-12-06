{{-- resources/views/components/search-results.blade.php --}}
@props(['searchQuery'])

@if($searchQuery)
<div class="mb-6 p-4 bg-orange-50 border border-orange-100 rounded-lg">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex-1">
            <div class="flex items-center mb-1">
                <i class="fas fa-search mr-2 text-orange-600"></i>
                <h3 class="font-semibold text-gray-800">
                    Hasil pencarian: "<span class="text-orange-600">{{ $searchQuery }}</span>"
                </h3>
            </div>
            @php
                // Deteksi halaman saat ini untuk menampilkan info yang sesuai
                $currentPage = request()->route()->getName();
                $pageNames = [
                    'user.dashboard' => 'Dashboard',
                    'user.ukm.list' => 'UKM',
                    'user.events.index' => 'Events',
                    'user.feeds.index' => 'Berita'
                ];
                $pageName = $pageNames[$currentPage] ?? 'Halaman ini';
            @endphp
            <p class="text-sm text-gray-600">
                Menampilkan hasil di {{ $pageName }}
            </p>
        </div>
        <div>
            <a href="{{ url()->current() }}" 
               class="inline-flex items-center px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-800 transition">
                <i class="fas fa-times mr-1.5"></i> Hapus pencarian
            </a>
        </div>
    </div>
</div>
@endif