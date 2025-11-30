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

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Feed Matrix & Management</h1>
    <div class="text-sm font-semibold lux-gold-text px-3 py-1 rounded-full border border-gray-300 bg-amber-50">
        <i class="fas fa-newspaper mr-1 lux-gold-text"></i> Total: **{{ $feeds->total() }} feeds**
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100">
                <i class="fas fa-newspaper text-blue-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Total Feeds</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalFeeds }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100">
                <i class="fas fa-image text-green-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">With Images</p>
                <p class="text-xl font-bold text-gray-900">{{ $feedsWithImages }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-purple-100">
                <i class="fas fa-calendar-day text-purple-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Today's Feeds</p>
                <p class="text-xl font-bold text-gray-900">{{ $todayFeeds }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-amber-100">
                <i class="fas fa-users text-amber-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Active UKMs</p>
                <p class="text-xl font-bold text-gray-900">{{ $ukms->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="floating-card mb-6">
    <div class="p-5 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-filter mr-2 lux-gold-text"></i> Search & Filters
        </h2>
    </div>
    <div class="p-5">
        <form action="{{ route('admin.feeds.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Search Feeds</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Title, content, or UKM..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus transition duration-150">
            </div>
            
            <div>
                <label for="ukm_id" class="block text-xs font-semibold text-gray-600 uppercase mb-1">UKM</label>
                <select name="ukm_id" id="ukm_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus transition duration-150">
                    <option value="">All UKMs</option>
                    @foreach($ukms as $ukm)
                        <option value="{{ $ukm->id }}" {{ request('ukm_id') == $ukm->id ? 'selected' : '' }}>
                            {{ $ukm->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="date_filter" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Date</label>
                <select name="date_filter" id="date_filter" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus transition duration-150">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                </select>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                            class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center font-semibold text-sm">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('admin.feeds.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200 flex items-center font-semibold text-sm">
                    <i class="fas fa-refresh mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Feeds Table -->
<div class="floating-card overflow-hidden">
    <div class="flex justify-between items-center p-5 border-b bg-gray-50/50">
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
        
        <div class="flex items-center space-x-4">
            <button onclick="openAddModal()" 
                    class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center font-semibold text-sm">
                <i class="fas fa-plus mr-2"></i> Add Feed
            </button>
            
            <span class="text-xs font-medium text-gray-600 uppercase">Show:</span>
            <select onchange="window.location.href = this.value" 
                    class="border border-gray-300 rounded-lg px-3 py-1 text-sm input-lux:focus">
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}" 
                        {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" 
                        {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" 
                        {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        @if($feeds->count() > 0)
            <table class="w-full min-w-full">
                <thead>
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
                    <tr class="hover-row-table transition duration-150">
                        <td class="p-4">
                            @if($feed->image)
                                <img src="{{ $feed->image }}" alt="{{ $feed->title }}" 
                                     class="w-16 h-16 rounded-lg object-cover border shadow-sm">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border">
                                    <i class="fas fa-newspaper text-gray-400 text-xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="font-semibold text-gray-900">{{ $feed->title }}</div>
                            <div class="text-xs text-gray-500 mt-1 flex items-center">
                                <i class="fas fa-user-edit mr-1"></i>
                                By: {{ $feed->creator->name ?? 'System' }}
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                <i class="fas fa-users mr-1"></i>
                                {{ $feed->ukm->name }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="text-sm text-gray-600 max-w-xs">
                                {{ Str::limit($feed->content, 80) }}
                            </div>
                        </td>
                        <td class="p-4 text-sm text-gray-500">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{ $feed->created_at->format('d M Y') }}</span>
                                <span class="text-gray-400">{{ $feed->created_at->format('H:i') }}</span>
                                <span class="text-xs text-gray-400 mt-1">
                                    {{ $feed->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </td>
                        <td class="p-4">
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
            <div class="text-center py-12">
                <i class="fas fa-newspaper text-5xl text-gray-300 mb-4"></i>
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
                            class="mt-4 lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 font-semibold text-sm">
                        <i class="fas fa-plus mr-2"></i> Create First Feed
                    </button>
                @endif
            </div>
        @endif
    </div>
    
    <!-- Pagination -->
    @if($feeds->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 rounded-b-lg">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div class="text-sm text-gray-700 font-medium">
                Showing **{{ $feeds->firstItem() }}** to **{{ $feeds->lastItem() }}** of **{{ $feeds->total() }}** results
            </div>
            <div class="flex space-x-1.5">
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
<dialog id="addFeedModal" class="modal-lux bg-white w-full max-w-2xl">
    <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-2 lux-gold-text"></i> Create New Feed
        </h3>
        <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-700 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="addFeedForm" action="{{ route('admin.feeds.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">UKM *</label>
                    <select name="ukm_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200
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
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200
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
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200
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
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200
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
        <div class="flex justify-end space-x-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl">
            <button type="button" onclick="closeAddModal()" 
                    class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium">
                Cancel
            </button>
            <button type="submit" 
                    class="lux-button px-4 py-2.5 rounded-lg hover:bg-amber-700 transition duration-200 font-medium flex items-center">
                <i class="fas fa-save mr-2"></i> Save Feed
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Feed Modal -->
<dialog id="editFeedModal" class="modal-lux bg-white w-full max-w-2xl">
    <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-edit mr-2 lux-gold-text"></i> Edit Feed
        </h3>
        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-700 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="editFeedForm" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">UKM *</label>
                    <select name="ukm_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200
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
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200
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
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200
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
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200
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
        <div class="flex justify-end space-x-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl">
            <button type="button" onclick="closeEditModal()" 
                    class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium">
                Cancel
            </button>
            <button type="submit" 
                    class="lux-button px-4 py-2.5 rounded-lg hover:bg-amber-700 transition duration-200 font-medium flex items-center">
                <i class="fas fa-save mr-2"></i> Update Feed
            </button>
        </div>
    </form>
</dialog>

<script>
// Modal Functions
function openAddModal() {
    document.getElementById('addFeedModal').showModal();
    document.getElementById('addFeedForm').reset();
    
    // Clear errors
    const errorInputs = document.querySelectorAll('#addFeedForm .border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    const errorMessages = document.querySelectorAll('#addFeedForm .text-red-500');
    errorMessages.forEach(msg => msg.remove());
    
    // Reset counter
    updateContentCounter(document.querySelector('#addFeedForm textarea[name="content"]'), 'contentCounter');
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
    
    document.getElementById('editFeedModal').showModal();
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
        document.getElementById('addFeedModal').showModal();
    }

    const hasEditErrors = {{ $errors->any() && session('edit_errors') ? 'true' : 'false' }};
    if (hasEditErrors) {
        document.getElementById('editFeedModal').showModal();
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

// Auto-close flash messages
setTimeout(() => {
    const flashMessages = document.querySelectorAll('.fixed');
    flashMessages.forEach(msg => msg.remove());
}, 5000);
</script>
@endsection