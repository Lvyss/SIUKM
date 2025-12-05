@extends('layouts.admin')

@section('content')
<style>
    /* CUSTOM STYLES DARI USER MANAGEMENT - SAMA PERSIS */
    .lux-bg { background-color: #f3f4f6; }
    .lux-gold-text { color: #b45309; } 

    /* Soft Shadow Card (SEKARANG MENGGUNAKAN NAMA CLASS 'floating-card' YANG SAMA) */
    .floating-card {
        background-color: white;
        border-radius: 12px;
        /* Shadow yang lebih kuat seperti di User Management */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .floating-card:hover {
        /* Hover agar terasa 'hidup' seperti di User Management */
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }
    
    /* Tombol Utama Lux (Gold/Amber) */
    .lux-button {
        background-color: #b45309;
        color: white;
        transition: background-color 0.2s;
    }
    .lux-button:hover {
        background-color: #92400e;
    }
    
    /* Fokus Input Lux (Gold/Amber Ring) */
    .input-lux:focus {
        border-color: #b45309; /* lux-gold */
        box-shadow: 0 0 0 2px rgba(180, 83, 9, 0.4);
    }

    /* Modal Animation */
    dialog.modal-lux::backdrop {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(2px);
    }
    dialog.modal-lux {
        animation: fadeIn 0.3s ease-out;
        border: none;
        padding: 0;
        border-radius: 12px;
        width: 95vw;
        max-width: 28rem;
        margin: 1rem auto;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    /* Hover Row yang lebih jelas (DISELARASKAN dengan hover-row-table) */
    .hover-row-table:hover {
        background-color: #fffaf0; /* Amber sangat muda */
    }
</style>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Feed Matrix & Management</h1>
    <div class="text-sm font-semibold lux-gold-text px-3 py-1 rounded-full border border-gray-300 bg-amber-50 whitespace-nowrap">
        <i class="fas fa-newspaper mr-1 lux-gold-text"></i> Total: **{{ $feeds->total() }} feeds**
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 flex-shrink-0">
                <i class="fas fa-newspaper text-blue-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Feeds</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $totalFeeds }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100 flex-shrink-0">
                <i class="fas fa-image text-green-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">With Images</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $feedsWithImages }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-purple-100 flex-shrink-0">
                <i class="fas fa-calendar-day text-purple-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Today's</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $todayFeeds }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-amber-100 flex-shrink-0">
                <i class="fas fa-users text-amber-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Active UKMs</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $ukms->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="floating-card mb-6">
    <div class="p-4 sm:p-5 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-filter mr-2 lux-gold-text"></i> Search & Filters
        </h2>
    </div>
    <div class="p-4 sm:p-5">
        <form action="{{ route('admin.feeds.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:flex-row sm:items-end sm:gap-3">
            <div class="flex-1 sm:flex-[2]">
                <label for="search" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Search Feeds</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Title, content, or UKM..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus transition duration-150 text-sm sm:text-base">
            </div>
            
            <div class="sm:min-w-[140px]">
                <label for="ukm_id" class="block text-xs font-semibold text-gray-600 uppercase mb-1">UKM</label>
                <select name="ukm_id" id="ukm_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus transition duration-150 text-sm sm:text-base">
                    <option value="">All UKMs</option>
                    @foreach($ukms as $ukm)
                        <option value="{{ $ukm->id }}" {{ request('ukm_id') == $ukm->id ? 'selected' : '' }}>
                            {{ $ukm->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="sm:min-w-[140px]">
                <label for="date_filter" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Date</label>
                <select name="date_filter" id="date_filter" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus transition duration-150 text-sm sm:text-base">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                </select>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-end sm:space-x-2 space-y-2 sm:space-y-0 pt-2 sm:pt-0">
                <button type="submit" 
                        class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center justify-center text-sm sm:text-base h-[42px] sm:h-auto">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('admin.feeds.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200 flex items-center justify-center text-sm sm:text-base h-[42px] sm:h-auto">
                    <i class="fas fa-refresh mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Feeds Table -->
<div class="floating-card overflow-hidden">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border-b bg-gray-50/50 gap-4">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-database mr-2 lux-gold-text"></i>
            @if(request('ukm_id'))
                @php
                    $selectedUkm = $ukms->firstWhere('id', request('ukm_id'));
                @endphp
                {{ $selectedUkm ? $selectedUkm->name . ' Feeds' : 'Selected UKM Feeds' }}
            @elseif(request('date_filter'))
                @switch(request('date_filter'))
                    @case('today')
                        Today's Feeds
                        @break
                    @case('week')
                        This Week's Feeds
                        @break
                    @case('month')
                        This Month's Feeds
                        @break
                @endswitch
            @else
                All Feed List
            @endif
        </h2>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <button onclick="openAddModal()" 
                    class="w-full sm:w-auto lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center justify-center text-sm sm:text-base">
                <i class="fas fa-plus mr-2"></i> Add Feed
            </button>
            
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <span class="text-xs font-medium text-gray-600 uppercase">Show:</span>
                <select onchange="window.location.href = this.value" 
                        class="border border-gray-300 rounded-lg px-3 py-1 text-sm w-full sm:w-auto">
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}" 
                            {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" 
                            {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" 
                            {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto px-4 sm:px-0">
        @if($feeds->count() > 0)
            <table class="w-full min-w-full">
                <thead class="hidden sm:table-header-group">
                    <tr class="bg-gray-100/70 border-b border-gray-200">
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Image</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Feed Details</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">UKM</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Content</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($feeds as $feed)
                    <!-- Mobile View -->
                    <tr class="hover-row-table transition duration-150 block sm:table-row border-b sm:border-b-0">
                        <td class="block sm:hidden p-4 mx-2 sm:mx-0 my-2 sm:my-0 bg-white rounded-lg shadow-sm">
                            <div class="space-y-4">
                                <!-- Header with Image -->
                                <div class="flex items-start space-x-3">
                                    @if($feed->image)
                                        <img src="{{ $feed->image }}" alt="{{ $feed->title }}" 
                                             class="w-16 h-16 rounded-lg object-cover border shadow-sm flex-shrink-0">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border flex-shrink-0">
                                            <i class="fas fa-newspaper text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-900 text-lg">{{ $feed->title }}</div>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                                <i class="fas fa-users mr-1"></i>
                                                {{ $feed->ukm->name }}
                                            </span>
                                            <div class="text-xs text-gray-500 flex items-center">
                                                <i class="fas fa-user-edit mr-1"></i>
                                                {{ $feed->creator->name ?? 'System' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <p class="line-clamp-3">{{ $feed->content }}</p>
                                </div>

                                <!-- Date -->
                                <div class="text-sm text-gray-500">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $feed->created_at->format('d M Y') }}</div>
                                            <div class="text-gray-400">{{ $feed->created_at->format('H:i') }}</div>
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $feed->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col space-y-2 pt-4 border-t border-gray-200">
                                    <button onclick="editFeed({{ $feed }})" 
                                            class="w-full bg-yellow-500 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-yellow-600 transition duration-200 flex items-center justify-center gap-2 font-medium">
                                        <i class="fas fa-edit"></i> Edit Feed
                                    </button>
                                    <form action="{{ route('admin.feeds.destroy', $feed->id) }}" method="POST" 
                                          class="w-full" onsubmit="return confirmDeleteFeed('{{ $feed->title }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-red-500 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center justify-center gap-2 font-medium">
                                            <i class="fas fa-trash"></i> Delete Feed
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>

                        <!-- Desktop View -->
                        <td class="hidden sm:table-cell p-4">
                            @if($feed->image)
                                <img src="{{ $feed->image }}" alt="{{ $feed->title }}" 
                                     class="w-16 h-16 rounded-lg object-cover border shadow-sm">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border">
                                    <i class="fas fa-newspaper text-gray-400 text-xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="hidden sm:table-cell p-4">
                            <div class="font-semibold text-gray-900">{{ $feed->title }}</div>
                            <div class="text-xs text-gray-500 mt-1 flex items-center">
                                <i class="fas fa-user-edit mr-1"></i>
                                By: {{ $feed->creator->name ?? 'System' }}
                            </div>
                        </td>
                        <td class="hidden sm:table-cell p-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                <i class="fas fa-users mr-1"></i>
                                {{ $feed->ukm->name }}
                            </span>
                        </td>
                        <td class="hidden sm:table-cell p-4">
                            <div class="text-sm text-gray-600 max-w-xs">
                                {{ Str::limit($feed->content, 80) }}
                            </div>
                        </td>
                        <td class="hidden sm:table-cell p-4 text-sm text-gray-500">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{ $feed->created_at->format('d M Y') }}</span>
                                <span class="text-gray-400">{{ $feed->created_at->format('H:i') }}</span>
                                <span class="text-xs text-gray-400 mt-1">
                                    {{ $feed->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </td>
                        <td class="hidden sm:table-cell p-4">
                            <div class="flex space-x-2 items-center">
                                <button onclick="editFeed({{ $feed }})" 
                                        class="bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-yellow-600 transition duration-200 flex items-center font-medium shadow-md shadow-yellow-500/20">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <form action="{{ route('admin.feeds.destroy', $feed->id) }}" method="POST" 
                                      class="inline" onsubmit="return confirmDeleteFeed('{{ $feed->title }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center font-medium shadow-md shadow-red-500/20">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-12 px-4">
                <i class="fas fa-newspaper text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg mb-2 font-semibold">No feeds match the criteria</p>
                <p class="text-gray-500 text-sm">
                    @if(request()->hasAny(['search', 'ukm_id', 'date_filter']))
                        Try adjusting your search or filters to see more results.
                    @else
                        No feeds have been created to the system yet.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'ukm_id', 'date_filter']))
                    <a href="{{ route('admin.feeds.index') }}" class="mt-4 inline-flex items-center text-sm font-semibold lux-gold-text hover:text-amber-700 transition duration-150">
                        <i class="fas fa-refresh mr-1"></i> Reset Filters
                    </a>
                @else
                    <button onclick="openAddModal()" 
                            class="mt-4 lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 font-semibold text-sm sm:text-base">
                        <i class="fas fa-plus mr-2"></i> Create First Feed
                    </button>
                @endif
            </div>
        @endif
    </div>
    
    <!-- Pagination -->
    @if($feeds->hasPages())
    <div class="px-4 sm:px-5 py-4 border-t border-gray-100 bg-gray-50/50">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
            <div class="text-sm text-gray-700 text-center sm:text-left font-medium">
                Showing **{{ $feeds->firstItem() }}** to **{{ $feeds->lastItem() }}** of **{{ $feeds->total() }}** results
            </div>
            <div class="flex flex-wrap justify-center gap-2">
                @if($feeds->onFirstPage())
                    <span class="px-3 py-1 rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed text-sm">
                        <i class="fas fa-angle-left"></i> Previous
                    </span>
                @else
                    <a href="{{ $feeds->previousPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">
                        <i class="fas fa-angle-left"></i> Previous
                    </a>
                @endif

                @php
                    $currentPage = $feeds->currentPage();
                    $lastPage = $feeds->lastPage();
                    $start = max(1, $currentPage - 1);
                    $end = min($lastPage, $currentPage + 1);
                @endphp

                @if ($start > 1)
                    <a href="{{ $feeds->url(1) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">1</a>
                    @if ($start > 2)
                        <span class="px-3 py-1 text-gray-500">...</span>
                    @endif
                @endif
                
                @for ($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <span class="px-3 py-1 rounded-lg border bg-amber-600 text-white text-sm font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $feeds->url($page) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="px-3 py-1 text-gray-500">...</span>
                    @endif
                    <a href="{{ $feeds->url($lastPage) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">{{ $lastPage }}</a>
                @endif

                @if($feeds->hasMorePages())
                    <a href="{{ $feeds->nextPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">
                        Next <i class="fas fa-angle-right"></i>
                    </a>
                @else
                    <span class="px-3 py-1 rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed text-sm">
                        Next <i class="fas fa-angle-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add Feed Modal -->
<dialog id="addFeedModal" class="modal-lux bg-white">
    <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
        <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-2 lux-gold-text"></i> Create New Feed
        </h3>
        <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-700 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="addFeedForm" action="{{ route('admin.feeds.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="p-4 sm:p-6 space-y-5 max-h-[70vh] overflow-y-auto">
            @if($errors->any() && !session('edit_errors'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <strong class="font-medium">Please fix the following errors:</strong>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">UKM *</label>
                    <select name="ukm_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base
                                   {{ $errors->has('ukm_id') && !session('edit_errors') ? 'border-red-500' : '' }}">
                        <option value="">Select UKM</option>
                        @foreach($ukms as $ukm)
                            <option value="{{ $ukm->id }}" {{ old('ukm_id') == $ukm->id ? 'selected' : '' }}>
                                {{ $ukm->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('ukm_id') && !session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('ukm_id') }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Feed Title *</label>
                    <input type="text" name="title" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base
                                  {{ $errors->has('title') && !session('edit_errors') ? 'border-red-500' : '' }}"
                           value="{{ old('title') }}"
                           placeholder="Enter feed title">
                    @if($errors->has('title') && !session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('title') }}</p>
                    @endif
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Content *</label>
                <textarea name="content" required 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base
                                 {{ $errors->has('content') && !session('edit_errors') ? 'border-red-500' : '' }}"
                          rows="4" 
                          placeholder="Write feed content...">{{ old('content') }}</textarea>
                @if($errors->has('content') && !session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('content') }}</p>
                @endif
                <div class="flex justify-between items-center mt-2">
                    <p class="text-gray-500 text-xs">Min 10 karakter, Max 2000 karakter</p>
                    <span id="contentCounter" class="text-xs font-medium text-gray-500">0/2000</span>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Image</label>
                <input type="file" name="image" accept="image/*" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base
                              file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                              file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700
                              hover:file:bg-amber-100
                              {{ $errors->has('image') && !session('edit_errors') ? 'border-red-500' : '' }}">
                @if($errors->has('image') && !session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('image') }}</p>
                @endif
                <p class="text-gray-500 text-xs mt-2">Format: JPEG, PNG, JPG, GIF, WebP | Max: 2MB</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 p-4 sm:p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl">
            <button type="button" onclick="closeAddModal()" 
                    class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium text-sm sm:text-base">
                Cancel
            </button>
            <button type="submit" 
                    class="lux-button px-4 py-2.5 rounded-lg hover:bg-amber-700 transition duration-200 font-medium flex items-center justify-center text-sm sm:text-base">
                <i class="fas fa-save mr-2"></i> Save Feed
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Feed Modal -->
<dialog id="editFeedModal" class="modal-lux bg-white">
    <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
        <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-edit mr-2 lux-gold-text"></i> Edit Feed
        </h3>
        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-700 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="editFeedForm" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="p-4 sm:p-6 space-y-5 max-h-[70vh] overflow-y-auto">
            @if($errors->any() && session('edit_errors'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <strong class="font-medium">Please fix the following errors:</strong>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">UKM *</label>
                    <select name="ukm_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base
                                   {{ $errors->has('ukm_id') && session('edit_errors') ? 'border-red-500' : '' }}"
                            id="editFeedUkm">
                        @foreach($ukms as $ukm)
                            <option value="{{ $ukm->id }}">{{ $ukm->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('ukm_id') && session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('ukm_id') }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Feed Title *</label>
                    <input type="text" name="title" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base
                                  {{ $errors->has('title') && session('edit_errors') ? 'border-red-500' : '' }}"
                           id="editFeedTitle">
                    @if($errors->has('title') && session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('title') }}</p>
                    @endif
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Content *</label>
                <textarea name="content" required 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base
                                 {{ $errors->has('content') && session('edit_errors') ? 'border-red-500' : '' }}"
                          rows="4" 
                          id="editFeedContent"></textarea>
                @if($errors->has('content') && session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('content') }}</p>
                @endif
                <div class="flex justify-between items-center mt-2">
                    <p class="text-gray-500 text-xs">Min 10 karakter, Max 2000 karakter</p>
                    <span id="editContentCounter" class="text-xs font-medium text-gray-500">0/2000</span>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Image</label>
                <input type="file" name="image" accept="image/*" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base
                              file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                              file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700
                              hover:file:bg-amber-100
                              {{ $errors->has('image') && session('edit_errors') ? 'border-red-500' : '' }}">
                @if($errors->has('image') && session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('image') }}</p>
                @endif
                <div class="mt-3" id="currentImageContainer">
                    <p class="text-sm font-medium text-gray-600 mb-2">Current Image:</p>
                    <img id="currentFeedImage" src="" class="w-32 h-32 rounded-lg border object-cover shadow-sm">
                </div>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 p-4 sm:p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl">
            <button type="button" onclick="closeEditModal()" 
                    class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium text-sm sm:text-base">
                Cancel
            </button>
            <button type="submit" 
                    class="lux-button px-4 py-2.5 rounded-lg hover:bg-amber-700 transition duration-200 font-medium flex items-center justify-center text-sm sm:text-base">
                <i class="fas fa-save mr-2"></i> Update Feed
            </button>
        </div>
    </form>
</dialog>

<script>
// Modal Functions
function openAddModal() {
    const modal = document.getElementById('addFeedModal');
    modal.showModal();
    document.getElementById('addFeedForm').reset();
    
    // Clear errors
    const errorInputs = document.querySelectorAll('#addFeedForm .border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    const errorMessages = document.querySelectorAll('#addFeedForm .text-red-500');
    errorMessages.forEach(msg => msg.remove());
    
    // Reset counter
    updateContentCounter(document.querySelector('#addFeedForm textarea[name="content"]'), 'contentCounter');
    
    // Mobile positioning
    if (window.innerWidth < 640) {
        modal.style.margin = '1rem auto';
        modal.style.maxHeight = '90vh';
        modal.style.overflowY = 'auto';
    }
}

function closeAddModal() {
    document.getElementById('addFeedModal').close();
}

function closeEditModal() {
    document.getElementById('editFeedModal').close();
}

function editFeed(feed) {
    const form = document.getElementById('editFeedForm');
    form.action = `/admin/feeds/${feed.id}`;
    
    document.getElementById('editFeedUkm').value = feed.ukm_id;
    document.getElementById('editFeedTitle').value = feed.title;
    document.getElementById('editFeedContent').value = feed.content;
    
    // Handle current image
    const currentImage = document.getElementById('currentFeedImage');
    const currentImageContainer = document.getElementById('currentImageContainer');
    
    if (feed.image) {
        currentImage.src = feed.image;
        currentImageContainer.style.display = 'block';
    } else {
        currentImageContainer.style.display = 'none';
    }
    
    // Update counter
    updateContentCounter(document.getElementById('editFeedContent'), 'editContentCounter');
    
    const modal = document.getElementById('editFeedModal');
    modal.showModal();
    
    // Mobile positioning
    if (window.innerWidth < 640) {
        modal.style.margin = '1rem auto';
        modal.style.maxHeight = '90vh';
        modal.style.overflowY = 'auto';
    }
}

function confirmDeleteFeed(title) {
    return confirm(`Are you sure you want to delete feed "${title}"? This action cannot be undone.`);
}

// Content counter function
function updateContentCounter(textarea, counterId) {
    const counter = document.getElementById(counterId);
    const length = textarea.value.length;
    counter.textContent = `${length}/2000`;
    
    if (length > 2000) {
        counter.classList.add('text-red-500');
    } else {
        counter.classList.remove('text-red-500');
    }
}

// Auto open modal jika ada error
document.addEventListener('DOMContentLoaded', function() {
    const hasAddErrors = {{ $errors->any() && !session('edit_errors') ? 'true' : 'false' }};
    if (hasAddErrors) {
        setTimeout(() => openAddModal(), 300);
    }

    const hasEditErrors = {{ $errors->any() && session('edit_errors') ? 'true' : 'false' }};
    if (hasEditErrors) {
        setTimeout(() => {
            const modal = document.getElementById('editFeedModal');
            modal.showModal();
            if (window.innerWidth < 640) {
                modal.style.margin = '1rem auto';
                modal.style.maxHeight = '90vh';
                modal.style.overflowY = 'auto';
            }
        }, 300);
    }

    // Content counter for add form
    const addContentTextarea = document.querySelector('#addFeedForm textarea[name="content"]');
    if (addContentTextarea) {
        addContentTextarea.addEventListener('input', function() {
            updateContentCounter(this, 'contentCounter');
        });
        updateContentCounter(addContentTextarea, 'contentCounter');
    }

    // Content counter for edit form
    const editContentTextarea = document.getElementById('editFeedContent');
    if (editContentTextarea) {
        editContentTextarea.addEventListener('input', function() {
            updateContentCounter(this, 'editContentCounter');
        });
    }

    // Close modals on backdrop click
    document.getElementById('addFeedModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddModal();
    });
    
    document.getElementById('editFeedModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // Image validation
    const imageInputs = document.querySelectorAll('input[type="file"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
            }
        });
    });
});

</script>
@endsection