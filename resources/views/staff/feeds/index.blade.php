@extends('layouts.staff')

@section('content')
<style>
    /* CUSTOM STYLES PURPLE/VIOLET THEME SESUAI DASHBOARD */
    .lux-purple-text { color: #7c3aed; } /* Violet-600 */
    .lux-purple-bg-light { background-color: #f5f3ff; } /* Violet-50 */
    
    /* Soft Shadow Card */
    .floating-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .floating-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    
    /* Tombol Utama Purple/Violet */
    .lux-button {
        background-color: #7c3aed; /* Violet-600 */
        color: white;
        transition: background-color 0.2s;
        font-weight: 600;
    }
    .lux-button:hover {
        background-color: #6d28d9; /* Violet-700 */
    }
    
    /* Fokus Input Purple/Violet */
    .input-lux:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.4);
        outline: none;
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
        max-width: 95vw;
        margin: 20px auto;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    /* Hover Row dengan tema purple */
    .hover-row-table:hover {
        background-color: #faf5ff; /* Violet sangat muda */
    }

    /* Badge dan status colors yang match dengan tema */
    .badge-purple {
        background-color: #f5f3ff;
        color: #7c3aed;
    }
    
    .badge-indigo {
        background-color: #e0e7ff;
        color: #4f46e5;
    }
    
    .badge-violet {
        background-color: #ede9fe;
        color: #8b5cf6;
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .mobile-stack {
            flex-direction: column;
        }
        
        .mobile-full-width {
            width: 100%;
        }
        
        .mobile-text-center {
            text-align: center;
        }
        
        .mobile-table-header {
            display: none;
        }
        
        .mobile-card-view {
            display: flex;
            flex-direction: column;
        }
        
        dialog.modal-lux {
            max-height: 90vh;
            overflow-y: auto;
        }
    }
</style>

<!-- Header Section -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Feed Management</h1>
        <p class="text-gray-600 text-sm sm:text-base mt-1">Manage feeds for your assigned UKMs</p>
    </div>
    
    <div class="text-sm font-semibold lux-purple-text px-3 py-1.5 rounded-full border border-gray-300 bg-violet-50 shadow-sm inline-flex items-center self-start sm:self-auto">
        <i class="fas fa-newspaper mr-1.5 lux-purple-text"></i> 
        <span>Total: {{ $feeds->total() }} feeds</span>
    </div>
</div>

<!-- Statistics Cards - Mobile: 2 per row, Desktop: 3 per row -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="floating-card p-4 sm:p-5">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-blue-100">
                <i class="fas fa-newspaper text-blue-600 text-sm sm:text-base"></i>
            </div>
            <div class="ml-3">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Total Feeds</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $totalFeeds }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4 sm:p-5">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-green-100">
                <i class="fas fa-image text-green-600 text-sm sm:text-base"></i>
            </div>
            <div class="ml-3">
                <p class="text-xs sm:text-sm font-medium text-gray-600">With Images</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $feedsWithImages }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4 sm:p-5">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-purple-100">
                <i class="fas fa-calendar-day text-purple-600 text-sm sm:text-base"></i>
            </div>
            <div class="ml-3">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Today's Feeds</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $todayFeeds }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="floating-card mb-6">
    <div class="p-4 sm:p-5 border-b border-gray-100">
        <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-filter mr-2 lux-purple-text"></i> Search & Filters
        </h2>
    </div>
    <div class="p-4 sm:p-5">
        <form action="{{ route('staff.feeds.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Search Feeds</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Title, content, or UKM..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-150 text-sm">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">UKM</label>
                <select name="ukm_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-150 text-sm">
                    <option value="">All UKMs</option>
                    @foreach($managedUkms as $ukm)
                        <option value="{{ $ukm->id }}" {{ request('ukm_id') == $ukm->id ? 'selected' : '' }}>
                            {{ Str::limit($ukm->name, 15) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" 
                        class="w-full sm:w-auto lux-button px-4 py-2.5 rounded-lg hover:bg-violet-700 transition duration-200 flex items-center justify-center font-semibold text-sm">
                    <i class="fas fa-search mr-2"></i> 
                    <span class="hidden sm:inline">Search</span>
                    <span class="sm:hidden">Go</span>
                </button>
                <a href="{{ route('staff.feeds.index') }}" 
                   class="w-full sm:w-auto bg-gray-500 text-white px-4 py-2.5 rounded-lg hover:bg-gray-600 transition duration-200 flex items-center justify-center font-semibold text-sm">
                    <i class="fas fa-refresh mr-2"></i>
                    <span class="hidden sm:inline">Reset</span>
                    <span class="sm:hidden">Clear</span>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Feeds Table/Card View -->
<div class="floating-card overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 sm:p-5 border-b bg-gray-50/50 gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-database mr-2 lux-purple-text"></i>
                @if(request('ukm_id'))
                    @php
                        $selectedUkm = $managedUkms->where('id', request('ukm_id'))->first();
                    @endphp
                    {{ $selectedUkm ? Str::limit($selectedUkm->name, 20) . ' Feeds' : 'Selected UKM Feeds' }}
                @else
                    My Managed Feeds
                @endif
            </h2>
            <p class="text-xs text-gray-600 mt-1 sm:hidden">{{ $feeds->count() }} of {{ $feeds->total() }} feeds</p>
        </div>
        
        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
            <button onclick="openAddModal()" 
                    class="w-full sm:w-auto lux-button px-4 py-2.5 rounded-lg hover:bg-violet-700 transition duration-200 flex items-center justify-center text-sm">
                <i class="fas fa-plus mr-2"></i>Add Feed
            </button>
            
            <div class="flex items-center space-x-2">
                <span class="text-xs sm:text-sm text-gray-600 hidden sm:block">Show:</span>
                <select onchange="window.location.href = this.value" 
                        class="w-full sm:w-auto border border-gray-300 rounded px-3 py-1.5 text-sm input-lux focus:input-lux:focus">
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
    
    <div class="overflow-x-auto">
        @if($feeds->count() > 0)
            <!-- Mobile Card View -->
            <div class="sm:hidden p-4 space-y-4">
                @foreach($feeds as $feed)
                <div class="floating-card p-4 border border-gray-200">
                    <div class="flex items-start space-x-3 mb-3">
                        @if($feed->image)
                            <img src="{{ $feed->image }}" alt="{{ $feed->title }}" 
                                 class="w-16 h-16 rounded-lg object-cover border shadow-sm flex-shrink-0">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border flex-shrink-0">
                                <i class="fas fa-newspaper text-gray-400 text-xl"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $feed->title }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 mt-1">
                                <i class="fas fa-users mr-1 text-xs"></i>
                                {{ Str::limit($feed->ukm->name, 15) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-xs text-gray-600 line-clamp-2">{{ Str::limit($feed->content, 80) }}</p>
                    </div>
                    
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-3">
                        <div>
                            <span class="font-medium">{{ $feed->created_at->format('d M') }}</span>
                            <span class="text-gray-400 ml-2">{{ $feed->created_at->format('H:i') }}</span>
                        </div>
                        <span class="text-gray-400">{{ $feed->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="flex space-x-2 pt-3 border-t border-gray-100">
                        <button onclick="editFeed({{ $feed }})" 
                                class="flex-1 lux-button px-3 py-1.5 rounded-lg text-xs hover:bg-violet-700 transition duration-200 flex items-center justify-center font-medium">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <form action="{{ route('staff.feeds.destroy', $feed->id) }}" method="POST" 
                              class="flex-1 inline" onsubmit="return confirmDeleteFeed('{{ $feed->title }}')">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-600 transition duration-200 flex items-center justify-center font-medium">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Desktop Table View -->
            <div class="hidden sm:block">
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
                                            class="lux-button px-3 py-1.5 rounded-lg text-sm hover:bg-violet-700 transition duration-200 flex items-center font-medium">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                    <form action="{{ route('staff.feeds.destroy', $feed->id) }}" method="POST" 
                                          class="inline" onsubmit="return confirmDeleteFeed('{{ $feed->title }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center font-medium">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 px-4">
                <i class="fas fa-newspaper text-4xl sm:text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-base sm:text-lg mb-2 font-semibold">No feeds found</p>
                <p class="text-gray-500 text-sm sm:text-sm">
                    @if(request()->hasAny(['search', 'ukm_id']))
                        Try adjusting your search or filters to see more results.
                    @else
                        No feeds have been created for your managed UKMs yet.
                    @endif
                </p>
                <button onclick="openAddModal()" 
                        class="mt-4 lux-button px-4 py-2.5 rounded-lg hover:bg-violet-700 transition duration-200 text-sm sm:text-base">
                    <i class="fas fa-plus mr-2"></i>Create First Feed
                </button>
            </div>
        @endif
    </div>
    
    @if($feeds->hasPages())
    <div class="px-4 sm:px-5 py-4 border-t border-gray-100 bg-gray-50/50 rounded-b-lg">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
            <div class="text-xs sm:text-sm text-gray-700 font-medium">
                Showing {{ $feeds->firstItem() }} to {{ $feeds->lastItem() }} of {{ $feeds->total() }} results
            </div>
            <div class="flex space-x-1.5 overflow-x-auto pb-1">
                @if($feeds->onFirstPage())
                    <span class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed text-xs sm:text-sm whitespace-nowrap">
                        <i class="fas fa-angle-left mr-1"></i> Prev
                    </span>
                @else
                    <a href="{{ $feeds->previousPageUrl() }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm whitespace-nowrap">
                        <i class="fas fa-angle-left mr-1"></i> Prev
                    </a>
                @endif

                @php
                    $currentPage = $feeds->currentPage();
                    $lastPage = $feeds->lastPage();
                    $start = max(1, $currentPage - 1);
                    $end = min($lastPage, $currentPage + 1);
                @endphp

                @if ($start > 1)
                    <a href="{{ $feeds->url(1) }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm">1</a>
                    @if ($start > 2)
                        <span class="px-2 sm:px-3 py-1 text-gray-500">...</span>
                    @endif
                @endif
                
                @for ($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <span class="px-2 sm:px-3 py-1 rounded-lg border bg-violet-600 text-white text-xs sm:text-sm font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $feeds->url($page) }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="px-2 sm:px-3 py-1 text-gray-500">...</span>
                    @endif
                    <a href="{{ $feeds->url($lastPage) }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm">{{ $lastPage }}</a>
                @endif

                @if($feeds->hasMorePages())
                    <a href="{{ $feeds->nextPageUrl() }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm whitespace-nowrap">
                        Next <i class="fas fa-angle-right ml-1"></i>
                    </a>
                @else
                    <span class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed text-xs sm:text-sm whitespace-nowrap">
                        Next <i class="fas fa-angle-right ml-1"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add Feed Modal -->
<dialog id="addFeedModal" class="modal-lux bg-white w-full max-w-2xl">
    <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
        <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-2 lux-purple-text"></i> Create New Feed
        </h3>
        <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-700 transition duration-200 p-1">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="addFeedForm" action="{{ route('staff.feeds.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-5 max-h-[70vh] overflow-y-auto">
            @if($errors->any() && !session('edit_errors'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-3 sm:px-4 py-3 rounded-lg">
                    <strong class="font-medium text-sm">Please fix the following errors:</strong>
                    <ul class="list-disc list-inside text-xs sm:text-sm mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">UKM *</label>
                    <select name="ukm_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200 text-sm
                                   {{ $errors->has('ukm_id') && !session('edit_errors') ? 'border-red-500' : '' }}">
                        <option value="">Select UKM</option>
                        @foreach($managedUkms as $ukm)
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
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200 text-sm
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
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200 text-sm
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
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200 text-sm
                              file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                              file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700
                              hover:file:bg-violet-100
                              {{ $errors->has('image') && !session('edit_errors') ? 'border-red-500' : '' }}">
                @if($errors->has('image') && !session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('image') }}</p>
                @endif
                <p class="text-gray-500 text-xs mt-2">Format: JPEG, PNG, JPG, GIF, WebP | Max: 2MB</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3 p-4 sm:p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl">
            <button type="button" onclick="closeAddModal()" 
                    class="w-full sm:w-auto px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium text-sm">
                Cancel
            </button>
            <button type="submit" 
                    class="w-full sm:w-auto lux-button px-4 py-2.5 rounded-lg hover:bg-violet-700 transition duration-200 font-medium flex items-center justify-center text-sm">
                <i class="fas fa-save mr-2"></i> Save Feed
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Feed Modal -->
<dialog id="editFeedModal" class="modal-lux bg-white w-full max-w-2xl">
    <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
        <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-edit mr-2 lux-purple-text"></i> Edit Feed
        </h3>
        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-700 transition duration-200 p-1">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="editFeedForm" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-5 max-h-[70vh] overflow-y-auto">
            @if($errors->any() && session('edit_errors'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-3 sm:px-4 py-3 rounded-lg">
                    <strong class="font-medium text-sm">Please fix the following errors:</strong>
                    <ul class="list-disc list-inside text-xs sm:text-sm mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">UKM *</label>
                    <select name="ukm_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200 text-sm
                                   {{ $errors->has('ukm_id') && session('edit_errors') ? 'border-red-500' : '' }}"
                            id="editFeedUkm">
                        @foreach($managedUkms as $ukm)
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
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200 text-sm
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
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200 text-sm
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
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200 text-sm
                              file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                              file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700
                              hover:file:bg-violet-100
                              {{ $errors->has('image') && session('edit_errors') ? 'border-red-500' : '' }}">
                @if($errors->has('image') && session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('image') }}</p>
                @endif
                <div class="mt-3" id="currentImageContainer">
                    <p class="text-sm font-medium text-gray-600 mb-2">Current Image:</p>
                    <img id="currentFeedImage" src="" class="w-24 h-24 sm:w-32 sm:h-32 rounded-lg border object-cover shadow-sm">
                </div>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3 p-4 sm:p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl">
            <button type="button" onclick="closeEditModal()" 
                    class="w-full sm:w-auto px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium text-sm">
                Cancel
            </button>
            <button type="submit" 
                    class="w-full sm:w-auto lux-button px-4 py-2.5 rounded-lg hover:bg-violet-700 transition duration-200 font-medium flex items-center justify-center text-sm">
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
    form.action = `/staff/feeds/${feed.id}`;
    
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

    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('addFeedModal').open) closeAddModal();
            if (document.getElementById('editFeedModal').open) closeEditModal();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        // Hanya hapus flash messages dengan class slide-in-from-top
        const flashMessages = document.querySelectorAll('.slide-in-from-top');
        flashMessages.forEach(msg => {
            msg.style.opacity = '0';
            msg.style.transition = 'opacity 0.5s ease-out';
            setTimeout(() => {
                if (msg.parentNode) {
                    msg.remove();
                }
            }, 500);
        });
    }, 4500);
});
</script>
@endsection