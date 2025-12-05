@extends('layouts.admin')

@section('content')
<style>
    .lux-bg { background-color: #f3f4f6; }
    .lux-gold-text { color: #b45309; }
    
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

    .lux-button {
        background-color: #b45309;
        color: white;
        transition: background-color 0.2s;
    }
    .lux-button:hover {
        background-color: #92400e;
    }
    .input-lux:focus {
        border-color: #b45309;
        box-shadow: 0 0 0 2px rgba(180, 83, 9, 0.4);
    }

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

    /* Responsive table styles */
    @media (max-width: 768px) {
        .table-container {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .mobile-hidden {
            display: none;
        }
    }
</style>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Category Management</h1>
    <div class="text-sm font-semibold lux-gold-text px-3 py-1 rounded-full border border-gray-300 bg-amber-50 whitespace-nowrap">
        <i class="fas fa-tags mr-1 lux-gold-text"></i> Total: **{{ $categories->total() }} category**
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 flex-shrink-0">
                <i class="fas fa-tags text-blue-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Categories</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $totalCategories }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100 flex-shrink-0">
                <i class="fas fa-users text-green-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">With UKMs</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $categoriesWithUkms }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-gray-100 flex-shrink-0">
                <i class="fas fa-box-open text-gray-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Empty</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $emptyCategories }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="floating-card mb-6">
    <div class="p-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Filters & Search</h2>
    </div>
    <div class="p-4">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:flex-row sm:items-end sm:gap-4">
            <!-- Search Input - Lebar penuh di mobile, 1/3 di desktop -->
            <div class="flex-1 sm:flex-[2]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Categories</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by name..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
            </div>
            
            <!-- UKM Count - 2 kolom di mobile, sebaris di desktop -->
            <div class="sm:min-w-[180px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">UKM Count</label>
                <select name="ukm_count" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                    <option value="">All Categories</option>
                    <option value="empty" {{ request('ukm_count') == 'empty' ? 'selected' : '' }}>No UKMs</option>
                    <option value="has_ukms" {{ request('ukm_count') == 'has_ukms' ? 'selected' : '' }}>Has UKMs</option>
                    <option value="popular" {{ request('ukm_count') == 'popular' ? 'selected' : '' }}>Popular (5+)</option>
                </select>
            </div>
            
            <!-- Sort By - 2 kolom di mobile, sebaris di desktop -->
            <div class="sm:min-w-[180px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select name="sort" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                    <option value="name_asc" {{ request('sort', 'name_asc') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                    <option value="ukms_desc" {{ request('sort') == 'ukms_desc' ? 'selected' : '' }}>Most UKMs</option>
                    <option value="ukms_asc" {{ request('sort') == 'ukms_asc' ? 'selected' : '' }}>Fewest UKMs</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                </select>
            </div>
            
            <!-- Buttons - Stack di mobile, sebaris di desktop -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:space-x-2 space-y-2 sm:space-y-0">
                <button type="submit" 
                        class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center justify-center text-sm sm:text-base h-[42px] sm:h-auto">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('admin.categories.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200 flex items-center justify-center text-sm sm:text-base h-[42px] sm:h-auto">
                    <i class="fas fa-refresh mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Categories Table -->
<div class="floating-card overflow-hidden">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border-b border-gray-200 bg-gray-50 gap-4">
        <h2 class="text-lg font-semibold text-gray-800">
            @if(request('ukm_count'))
                @switch(request('ukm_count'))
                    @case('empty')
                        Empty Categories
                        @break
                    @case('has_ukms')
                        Categories with UKMs
                        @break
                    @case('popular')
                        Popular Categories
                        @break
                @endswitch
            @else
                All Categories
            @endif
        </h2>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <button onclick="openAddModal()" 
                    class="w-full sm:w-auto lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center justify-center text-sm sm:text-base">
                <i class="fas fa-plus mr-2"></i> Add Category
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
        @if($categories->count() > 0)
            <table class="w-full min-w-full">
                <thead class="hidden sm:table-header-group">
                    <tr class="bg-gray-100">
                        <th class="p-3 text-left text-sm font-semibold text-gray-700">Category Name</th>
                        <th class="p-3 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="p-3 text-left text-sm font-semibold text-gray-700">UKM Count</th>
                        <th class="p-3 text-left text-sm font-semibold text-gray-700">Created</th>
                        <th class="p-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($categories as $category)
                    <tr class="hover:bg-amber-50/30 transition duration-150 block sm:table-row border-b sm:border-b-0">
                        <!-- Mobile View -->
                        <td class="block sm:hidden p-4 mx-2 sm:mx-0 my-2 sm:my-0 bg-white rounded-lg shadow-sm">
                            <div class="space-y-4">
                                <!-- Header Row -->
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-900 text-lg truncate">{{ $category->name }}</div>
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $category->ukms_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $category->ukms_count }} UKM{{ $category->ukms_count != 1 ? 's' : '' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $category->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($category->description)
                                <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <p class="line-clamp-3">{{ $category->description }}</p>
                                </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex flex-col space-y-2 pt-4 border-t border-gray-200">
                                    <button onclick="editCategory({{ $category }})" 
                                            class="w-full bg-amber-500 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-amber-600 transition duration-200 flex items-center justify-center gap-2 font-medium">
                                        <i class="fas fa-edit"></i> Edit Category
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                                          class="w-full" onsubmit="return confirmDeleteCategory('{{ $category->name }}', {{ $category->ukms_count }})">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-red-500 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center justify-center gap-2 font-medium
                                                       {{ $category->ukms_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $category->ukms_count > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i> Delete Category
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>

                        <!-- Desktop View -->
                        <td class="hidden sm:table-cell p-3">
                            <div class="font-semibold text-gray-900">{{ $category->name }}</div>
                        </td>
                        <td class="hidden sm:table-cell p-3">
                            <div class="text-sm text-gray-600 max-w-xs">
                                {{ $category->description ? Str::limit($category->description, 80) : '-' }}
                            </div>
                        </td>
                        <td class="hidden sm:table-cell p-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $category->ukms_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $category->ukms_count }} UKM{{ $category->ukms_count != 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="hidden sm:table-cell p-3 text-sm text-gray-500">
                            {{ $category->created_at->format('d M Y') }}
                            <div class="text-xs text-gray-400">{{ $category->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="hidden sm:table-cell p-3">
                            <div class="flex space-x-2">
                                <button onclick="editCategory({{ $category }})" 
                                        class="bg-amber-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-amber-600 transition duration-200 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                                      class="inline" onsubmit="return confirmDeleteCategory('{{ $category->name }}', {{ $category->ukms_count }})">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center
                                                   {{ $category->ukms_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $category->ukms_count > 0 ? 'disabled' : '' }}>
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
                <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg mb-2">No categories found</p>
                <p class="text-gray-500 text-sm">
                    @if(request()->hasAny(['search', 'ukm_count']))
                        Try adjusting your search or filters
                    @else
                        No categories have been created yet
                    @endif
                </p>
                @if(request()->hasAny(['search', 'ukm_count']))
                    <a href="{{ route('admin.categories.index') }}" 
                       class="inline-block mt-4 lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 text-sm sm:text-base">
                        View All Categories
                    </a>
                @else
                    <button onclick="openAddModal()" 
                            class="mt-4 lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 text-sm sm:text-base">
                        <i class="fas fa-plus mr-2"></i> Create First Category
                    </button>
                @endif
            </div>
        @endif
    </div>
    
    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="px-4 sm:px-6 py-4 border-t bg-gray-50">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
            <div class="text-sm text-gray-700 text-center sm:text-left">
                Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} results
            </div>
            <div class="flex flex-wrap justify-center gap-2">
                <!-- Previous Page Link -->
                @if($categories->onFirstPage())
                    <span class="px-3 py-1 rounded border text-gray-400 cursor-not-allowed text-sm">Previous</span>
                @else
                    <a href="{{ $categories->previousPageUrl() }}" class="px-3 py-1 rounded border text-gray-700 hover:bg-gray-100 transition duration-200 text-sm">Previous</a>
                @endif

                <!-- Pagination Elements -->
                @foreach($categories->getUrlRange(max(1, $categories->currentPage() - 2), min($categories->lastPage(), $categories->currentPage() + 2)) as $page => $url)
                    @if($page == $categories->currentPage())
                        <span class="px-3 py-1 rounded border bg-amber-600 text-white text-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 rounded border text-gray-700 hover:bg-gray-100 transition duration-200 text-sm">{{ $page }}</a>
                    @endif
                @endforeach

                <!-- Next Page Link -->
                @if($categories->hasMorePages())
                    <a href="{{ $categories->nextPageUrl() }}" class="px-3 py-1 rounded border text-gray-700 hover:bg-gray-100 transition duration-200 text-sm">Next</a>
                @else
                    <span class="px-3 py-1 rounded border text-gray-400 cursor-not-allowed text-sm">Next</span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add Category Modal -->
<dialog id="addCategoryModal" class="modal-lux">
    <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Add New Category</h3>
        <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="addCategoryForm" action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="p-4 sm:p-6 space-y-4">
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" name="name" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm sm:text-base
                              {{ $errors->has('name') && !session('edit_errors') ? 'border-red-500' : '' }}"
                       value="{{ old('name') }}"
                       placeholder="Enter category name">
                @if($errors->has('name') && !session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('name') }}</p>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm sm:text-base
                                 {{ $errors->has('description') && !session('edit_errors') ? 'border-red-500' : '' }}"
                          rows="3" 
                          placeholder="Optional description">{{ old('description') }}</textarea>
                @if($errors->has('description') && !session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('description') }}</p>
                @endif
                <p class="text-gray-500 text-xs mt-1">Max 500 characters</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 p-4 sm:p-6 border-t bg-gray-50 rounded-b-lg">
            <button type="button" onclick="closeAddModal()" 
                    class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium text-sm sm:text-base">
                Cancel
            </button>
            <button type="submit" 
                    class="lux-button px-4 py-2.5 rounded-lg hover:bg-amber-700 transition duration-200 font-medium flex items-center justify-center text-sm sm:text-base">
                <i class="fas fa-save mr-2"></i> Save Category
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Category Modal -->
<dialog id="editCategoryModal" class="modal-lux">
    <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Edit Category</h3>
        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="editCategoryForm" method="POST">
        @csrf @method('PUT')
        <div class="p-4 sm:p-6 space-y-4">
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" name="name" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm sm:text-base
                              {{ $errors->has('name') && session('edit_errors') ? 'border-red-500' : '' }}"
                       id="editCategoryName"
                       value="{{ old('name') }}">
                @if($errors->has('name') && session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('name') }}</p>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm sm:text-base
                                 {{ $errors->has('description') && session('edit_errors') ? 'border-red-500' : '' }}"
                          rows="3" 
                          id="editCategoryDescription">{{ old('description') }}</textarea>
                @if($errors->has('description') && session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('description') }}</p>
                @endif
                <p class="text-gray-500 text-xs mt-1">Max 500 characters</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 p-4 sm:p-6 border-t bg-gray-50 rounded-b-lg">
            <button type="button" onclick="closeEditModal()" 
                    class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium text-sm sm:text-base">
                Cancel
            </button>
            <button type="submit" 
                    class="lux-button px-4 py-2.5 rounded-lg hover:bg-amber-700 transition duration-200 font-medium flex items-center justify-center text-sm sm:text-base">
                <i class="fas fa-save mr-2"></i> Update Category
            </button>
        </div>
    </form>
</dialog>

<script>
// Modal Functions
function openAddModal() {
    const modal = document.getElementById('addCategoryModal');
    modal.showModal();
    document.getElementById('addCategoryForm').reset();
    
    // Clear errors
    const errorInputs = document.querySelectorAll('#addCategoryForm .border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    const errorMessages = document.querySelectorAll('#addCategoryForm .text-red-500');
    errorMessages.forEach(msg => msg.remove());
    
    // Mobile positioning
    if (window.innerWidth < 640) {
        modal.style.margin = '1rem auto';
        modal.style.maxHeight = '90vh';
        modal.style.overflowY = 'auto';
    }
}

function closeAddModal() {
    document.getElementById('addCategoryModal').close();
}

function closeEditModal() {
    document.getElementById('editCategoryModal').close();
}

function editCategory(category) {
    const form = document.getElementById('editCategoryForm');
    form.action = `/admin/categories/${category.id}`;
    document.getElementById('editCategoryName').value = category.name;
    document.getElementById('editCategoryDescription').value = category.description || '';
    
    const modal = document.getElementById('editCategoryModal');
    modal.showModal();
    
    // Mobile positioning
    if (window.innerWidth < 640) {
        modal.style.margin = '1rem auto';
        modal.style.maxHeight = '90vh';
        modal.style.overflowY = 'auto';
    }
}

function confirmDeleteCategory(name, ukmsCount) {
    if (ukmsCount > 0) {
        alert(`Cannot delete category "${name}" because it has ${ukmsCount} UKM(s) associated with it.`);
        return false;
    }
    return confirm(`Are you sure you want to delete category "${name}"? This action cannot be undone.`);
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
            const form = document.getElementById('editCategoryForm');
            const categoryId = {{ session('edit_category_id', 0) }};
            if (categoryId) {
                form.action = `/admin/categories/${categoryId}`;
            }
            const modal = document.getElementById('editCategoryModal');
            modal.showModal();
            if (window.innerWidth < 640) {
                modal.style.margin = '1rem auto';
                modal.style.maxHeight = '90vh';
                modal.style.overflowY = 'auto';
            }
        }, 300);
    }

    // Close modals on backdrop click
    document.getElementById('addCategoryModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddModal();
    });
    
    document.getElementById('editCategoryModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
});


</script>
@endsection