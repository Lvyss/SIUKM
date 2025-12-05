@extends('layouts.admin')

@section('content')
<style>
    /* CUSTOM STYLES DARI CATEGORY/USER MANAGEMENT (LUX THEME) */
    .lux-bg { background-color: #f3f4f6; }
    .lux-gold-text { color: #b45309; } 
    .lux-gold-bg-light { background-color: #fffbeb; } /* Amber 50 */
    
    /* Soft Shadow Card (floating-card) */
    .floating-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .floating-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }
    
    /* Tombol Utama Lux (Gold/Amber) */
    .lux-button {
        background-color: #b45309; /* Amber-700 */
        color: white;
        transition: background-color 0.2s;
    }
    .lux-button:hover {
        background-color: #92400e; /* Amber-800 */
    }
    
    /* Fokus Input Lux (Gold/Amber Ring) */
    .input-lux:focus {
        border-color: #b45309; /* lux-gold */
        box-shadow: 0 0 0 2px rgba(180, 83, 9, 0.4);
    }
    
    /* Hover Row yang diselaraskan dengan tema lux */
    .hover-row-table:hover {
        background-color: #fffaf0; /* Amber sangat muda */
    }
    
    /* Modal Animation */
    dialog[open] {
        animation: slideIn 0.2s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">UKM Matrix & Management</h1>
    <div class="text-sm font-semibold lux-gold-text px-3 py-1 rounded-full border border-gray-300 bg-amber-50 whitespace-nowrap">
        <i class="fas fa-users-cog mr-1 lux-gold-text"></i> Total: **{{ $ukms->total() }} UKM**
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-amber-100 flex-shrink-0">
                <i class="fas fa-users lux-gold-text text-base sm:text-lg"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total UKM</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $totalUkms }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-green-100 flex-shrink-0">
                <i class="fas fa-check-circle text-green-600 text-base sm:text-lg"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Active</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $activeUkms }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-red-100 flex-shrink-0">
                <i class="fas fa-pause-circle text-red-600 text-base sm:text-lg"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Inactive</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $inactiveUkms }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-amber-100 flex-shrink-0">
                <i class="fas fa-user-tie lux-gold-text text-base sm:text-lg"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">With Staff</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $ukmsWithStaff }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="floating-card mb-6">
    <div class="p-4 sm:p-5 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-filter mr-2 lux-gold-text"></i> Search & Filters
        </h2>
    </div>
    <div class="p-4 sm:p-5">
        <form action="{{ route('admin.ukms.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:flex-row sm:items-end sm:gap-3">
            <!-- Search Input -->
            <div class="flex-1 sm:flex-[2]">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Search UKM</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search UKM..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus text-sm sm:text-base">
            </div>
            
            <!-- Category Filter -->
            <div class="sm:min-w-[140px]">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Category</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus text-sm sm:text-base">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status Filter -->
            <div class="sm:min-w-[120px]">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus text-sm sm:text-base">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:space-x-2 space-y-2 sm:space-y-0 pt-2 sm:pt-0">
                <button type="submit" 
                        class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center justify-center text-sm sm:text-base h-[42px] sm:h-auto">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('admin.ukms.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200 flex items-center justify-center text-sm sm:text-base h-[42px] sm:h-auto">
                    <i class="fas fa-refresh mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- UKM Table -->
<div class="floating-card overflow-hidden">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border-b bg-gray-50/50 gap-4">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-database mr-2 lux-gold-text"></i>
            @if(request('category_id'))
                @php
                    $selectedCategory = $categories->where('id', request('category_id'))->first();
                @endphp
                UKM - {{ $selectedCategory->name ?? 'Selected Category' }}
            @elseif(request('status'))
                {{ ucfirst(request('status')) }} UKM
            @else
                All UKM
            @endif
        </h2>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <button onclick="openAddModal()" 
                    class="w-full sm:w-auto lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center justify-center text-sm sm:text-base">
                <i class="fas fa-plus mr-2"></i>Add UKM
            </button>
            
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <span class="text-sm text-gray-600 whitespace-nowrap">Show:</span>
                <select onchange="window.location.href = this.value" 
                        class="border border-gray-300 rounded px-3 py-1 text-sm w-full sm:w-auto">
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
        @if($ukms->count() > 0)
        <table class="w-full min-w-full">
            <thead class="hidden sm:table-header-group">
                <tr class="bg-gray-100/70 border-b border-gray-200">
                    <th class="p-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Logo</th>
                    <th class="p-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">UKM Details</th>
                    <th class="p-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Category</th>
                    <th class="p-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Stats</th>
                    <th class="p-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="p-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($ukms as $ukm)
                <!-- Mobile View -->
                <tr class="hover-row-table transition duration-150 block sm:table-row border-b sm:border-b-0">
                    <td class="block sm:hidden p-4 mx-2 sm:mx-0 my-2 sm:my-0 bg-white rounded-lg shadow-sm">
                        <div class="space-y-4">
                            <!-- Header with Logo -->
                            <div class="flex items-start space-x-3">
                                @if($ukm->logo)
                                    <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" 
                                         class="w-16 h-16 rounded-lg object-cover border flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 bg-amber-50 rounded-lg flex items-center justify-center border border-amber-200 flex-shrink-0">
                                        <i class="fas fa-users lux-gold-text text-xl"></i>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-gray-900 text-lg">{{ $ukm->name }}</div>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            {{ $ukm->category->name }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $ukm->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($ukm->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($ukm->description)
                            <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <p class="line-clamp-2">{{ $ukm->description }}</p>
                            </div>
                            @endif

                            <!-- Contact Info -->
                            @if($ukm->contact_person || $ukm->instagram)
                            <div class="text-xs text-gray-500 space-y-1">
                                @if($ukm->contact_person)
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2 w-4"></i>
                                        <span>{{ $ukm->contact_person }}</span>
                                    </div>
                                @endif
                                @if($ukm->instagram)
                                    <div class="flex items-center">
                                        <i class="fab fa-instagram mr-2 w-4"></i>
                                        <span>{{ $ukm->instagram }}</span>
                                    </div>
                                @endif
                            </div>
                            @endif

                            <!-- Stats -->
                            <div class="flex flex-wrap gap-2 pt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-users mr-1"></i>{{ $ukm->staff_count }} Staff
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-calendar mr-1"></i>{{ $ukm->events_count }} Events
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-newspaper mr-1"></i>{{ $ukm->feeds_count }} Feeds
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col space-y-2 pt-4 border-t border-gray-200">
                                <button onclick="editUkm({{ $ukm }})" 
                                        class="w-full bg-amber-500 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-amber-600 transition duration-200 flex items-center justify-center gap-2 font-medium">
                                    <i class="fas fa-edit"></i> Edit UKM
                                </button>
                                <form action="{{ route('admin.ukms.destroy', $ukm->id) }}" method="POST" 
                                      class="w-full" onsubmit="return confirmDelete()">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-500 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center justify-center gap-2 font-medium">
                                        <i class="fas fa-trash"></i> Delete UKM
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop View -->
                    <td class="hidden sm:table-cell p-3">
                        @if($ukm->logo)
                            <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" 
                                 class="w-12 h-12 rounded-lg object-cover border">
                        @else
                            <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center border border-amber-200">
                                <i class="fas fa-users lux-gold-text text-lg"></i>
                            </div>
                        @endif
                    </td>
                    <td class="hidden sm:table-cell p-3">
                        <div class="font-semibold text-gray-900">{{ $ukm->name }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($ukm->description, 60) }}</div>
                        <div class="text-xs text-gray-500 mt-1 flex flex-wrap gap-x-3">
                            @if($ukm->contact_person)
                                <span class="text-gray-600"><i class="fas fa-user mr-1"></i>{{ $ukm->contact_person }}</span>
                            @endif
                            @if($ukm->instagram)
                                <span class="text-gray-600"><i class="fab fa-instagram mr-1"></i>{{ $ukm->instagram }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="hidden sm:table-cell p-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            {{ $ukm->category->name }}
                        </span>
                    </td>
                    <td class="hidden sm:table-cell p-3">
                        <div class="flex flex-wrap gap-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800"
                                  title="{{ $ukm->staff_count }} staff members">
                                <i class="fas fa-users mr-1"></i>{{ $ukm->staff_count }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800"
                                  title="{{ $ukm->events_count }} events">
                                <i class="fas fa-calendar mr-1"></i>{{ $ukm->events_count }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800"
                                  title="{{ $ukm->feeds_count }} feeds">
                                <i class="fas fa-newspaper mr-1"></i>{{ $ukm->feeds_count }}
                            </span>
                        </div>
                    </td>
                    <td class="hidden sm:table-cell p-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $ukm->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($ukm->status) }}
                        </span>
                    </td>
                    <td class="hidden sm:table-cell p-3">
                        <div class="flex space-x-2">
                            <button onclick="editUkm({{ $ukm }})" 
                                    class="bg-amber-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-amber-600 transition duration-200 flex items-center font-medium shadow-md shadow-amber-500/20">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <form action="{{ route('admin.ukms.destroy', $ukm->id) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
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
        @endif
    </div>
    
    <!-- Empty State -->
    @if($ukms->count() == 0)
    <div class="text-center py-12 px-4">
        <i class="fas fa-users text-4xl text-gray-300 mb-4 lux-gold-text"></i>
        <p class="text-gray-600 text-lg mb-2 font-semibold">No UKM found</p>
        <p class="text-gray-500 text-sm mb-4">
            @if(request()->hasAny(['search', 'category_id', 'status', 'staff_filter']))
                Try adjusting your search or filters
            @else
                No UKM have been created yet
            @endif
        </p>
        <button onclick="openAddModal()" 
                class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center mx-auto font-semibold text-sm sm:text-base">
            <i class="fas fa-plus mr-2"></i>Create First UKM
        </button>
    </div>
    @endif
    
    <!-- Pagination -->
    @if($ukms->hasPages())
    <div class="px-4 sm:px-5 py-4 border-t border-gray-100 bg-gray-50/50">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
            <div class="text-sm text-gray-700 text-center sm:text-left font-medium">
                Showing **{{ $ukms->firstItem() }}** to **{{ $ukms->lastItem() }}** of **{{ $ukms->total() }}** results
            </div>
            <div class="flex flex-wrap justify-center gap-2">
                @if($ukms->onFirstPage())
                    <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed text-sm">Previous</span>
                @else
                    <a href="{{ $ukms->previousPageUrl() }}" class="px-3 py-1 rounded border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">Previous</a>
                @endif

                @foreach($ukms->getUrlRange(max(1, $ukms->currentPage() - 2), min($ukms->lastPage(), $ukms->currentPage() + 2)) as $page => $url)
                    @if($page == $ukms->currentPage())
                        <span class="px-3 py-1 rounded border bg-amber-600 text-white text-sm font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 rounded border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">{{ $page }}</a>
                    @endif
                @endforeach

                @if($ukms->hasMorePages())
                    <a href="{{ $ukms->nextPageUrl() }}" class="px-3 py-1 rounded border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">Next</a>
                @else
                    <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed text-sm">Next</span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- MODAL ADD UKM -->
<dialog id="addUkmModal" class="modal-backdrop w-[95vw] sm:w-full max-w-2xl mx-auto rounded-lg">
    <!-- Modal content tetap sama, tambahkan class responsive di padding -->
    <div class="modal-content p-0">
        <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle mr-2 lux-gold-text"></i>
                Add New UKM
            </h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        
        <form id="addUkmForm" action="{{ route('admin.ukms.store') }}" method="POST" enctype="multipart/form-data" 
              class="p-4 sm:p-6 space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">UKM Name *</label>
                    <input type="text" name="name" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                           placeholder="Enter UKM name">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                          placeholder="Enter UKM description">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                    <input type="text" name="contact_person" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                           placeholder="Contact person name"
                           value="{{ old('contact_person') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                    <input type="text" name="instagram" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                           placeholder="@username"
                           value="{{ old('instagram') }}">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                <input type="file" name="logo" accept="image/*" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus">
                <p class="text-xs text-gray-500 mt-1">Recommended: Square image, max 2MB</p>
                @error('logo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vision</label>
                    <textarea name="vision" rows="2" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                              placeholder="UKM vision">{{ old('vision') }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mission</label>
                    <textarea name="mission" rows="2" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                              placeholder="UKM mission">{{ old('mission') }}</textarea>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeAddModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                    Cancel
                </button>
                <button type="submit" 
                        class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>Save UKM
                </button>
            </div>
        </form>
    </div>
</dialog>

<!-- EDIT MODAL Responsive -->
<dialog id="editUkmModal" class="modal-backdrop w-[95vw] sm:w-full max-w-2xl mx-auto rounded-lg">
    <!-- Modal content tetap sama, tambahkan class responsive -->
    <div class="modal-content p-0">
        <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-2 lux-gold-text"></i>
                Edit UKM
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        
        <form id="editUkmForm" action="" method="POST" enctype="multipart/form-data" 
              class="p-4 sm:p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div id="currentLogoContainer" class="text-center mb-4" style="display: none;">
                <p class="text-sm font-medium text-gray-700 mb-2">Current Logo</p>
                <img id="currentLogo" src="" alt="Current Logo" class="w-20 h-20 rounded-lg object-cover border mx-auto">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">UKM Name *</label>
                    <input type="text" name="name" id="editUkmName" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                           placeholder="Enter UKM name">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" id="editUkmCategory" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="editUkmDescription" rows="3" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                          placeholder="Enter UKM description"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                    <input type="text" name="contact_person" id="editUkmContactPerson" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                           placeholder="Contact person name">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                    <input type="text" name="instagram" id="editUkmInstagram" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                           placeholder="@username">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                <input type="file" name="logo" accept="image/*" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus">
                <p class="text-xs text-gray-500 mt-1">Leave empty to keep current logo</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vision</label>
                    <textarea name="vision" id="editUkmVision" rows="2" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                              placeholder="UKM vision"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mission</label>
                    <textarea name="mission" id="editUkmMission" rows="2" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus"
                              placeholder="UKM mission"></textarea>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="editUkmStatus" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                    Cancel
                </button>
                <button type="submit" 
                        class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>Update UKM
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
// Modal Functions untuk mobile
function openAddModal() {
    const modal = document.getElementById('addUkmModal');
    modal.showModal();
    document.getElementById('addUkmForm').reset();
    
    // Mobile positioning
    if (window.innerWidth < 640) {
        modal.style.margin = '1rem auto';
        modal.style.maxHeight = '90vh';
        modal.style.overflowY = 'auto';
    }
}

function closeAddModal() {
    document.getElementById('addUkmModal').close();
}

function closeEditModal() {
    document.getElementById('editUkmModal').close();
}

function editUkm(ukm) {
    const form = document.getElementById('editUkmForm');
    form.action = `/admin/ukms/${ukm.id}`;
    
    // Fill form dengan data UKM
    document.getElementById('editUkmName').value = ukm.name || '';
    document.getElementById('editUkmCategory').value = ukm.category_id || '';
    document.getElementById('editUkmDescription').value = ukm.description || '';
    document.getElementById('editUkmVision').value = ukm.vision || '';
    document.getElementById('editUkmMission').value = ukm.mission || '';
    document.getElementById('editUkmContactPerson').value = ukm.contact_person || '';
    document.getElementById('editUkmInstagram').value = ukm.instagram || '';
    document.getElementById('editUkmStatus').value = ukm.status || 'active';
    
    const currentLogo = document.getElementById('currentLogo');
    const currentLogoContainer = document.getElementById('currentLogoContainer');
    
    if (ukm.logo) {
        currentLogo.src = ukm.logo;
        currentLogoContainer.style.display = 'block';
    } else {
        currentLogoContainer.style.display = 'none';
    }
    
    const modal = document.getElementById('editUkmModal');
    modal.showModal();
    
    // Mobile positioning
    if (window.innerWidth < 640) {
        modal.style.margin = '1rem auto';
        modal.style.maxHeight = '90vh';
        modal.style.overflowY = 'auto';
    }
}

function confirmDelete() {
    return confirm('Are you sure you want to delete this UKM? This action cannot be undone.');
}

// Auto open modal jika ada error
document.addEventListener('DOMContentLoaded', function() {
    // Cek jika ada error dari add form
    const hasAddErrors = {{ $errors->any() && !session('edit_errors') ? 'true' : 'false' }};
    if (hasAddErrors) {
        document.getElementById('addUkmModal').showModal();
    }

    // Cek jika ada error dari edit form  
    const hasEditErrors = {{ $errors->any() && session('edit_errors') ? 'true' : 'false' }};
    if (hasEditErrors) {
        document.getElementById('editUkmModal').showModal();
    }

    // Close modals on backdrop click
    document.getElementById('addUkmModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddModal();
    });
    
    document.getElementById('editUkmModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
});

</script>
@endsection