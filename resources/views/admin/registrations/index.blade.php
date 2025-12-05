@extends('layouts.admin')

@section('content')
<style>
    /* CUSTOM STYLES LUXURY SAMA KAYA YANG LAIN */
    .lux-bg { background-color: #f3f4f6; }
    .lux-gold-text { color: #b45309; } 

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
    
    .lux-button {
        background-color: #b45309;
        color: white;
        transition: background-color 0.2s;
        font-weight: 600;
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
        max-width: 95vw;
        margin: 20px auto;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    .hover-row-table:hover {
        background-color: #fffaf0;
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
    }
</style>

<!-- Header Section -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Registrations Management</h1>
        <p class="text-gray-600 text-sm sm:text-base mt-1">Manage all UKM registration requests</p>
    </div>
    
    <div class="text-sm font-semibold lux-gold-text px-3 py-1.5 rounded-full border border-gray-300 bg-amber-50 shadow-sm inline-flex items-center self-start sm:self-auto">
        <i class="fas fa-clipboard-check mr-1.5 lux-gold-text"></i> 
        <span>Total: {{ $registrations->total() }} registrations</span>
    </div>
</div>

<!-- Statistics Cards - Mobile: 2 per row, Desktop: 4 per row -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="floating-card p-4 sm:p-5">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-blue-100">
                <i class="fas fa-list text-blue-600 text-sm sm:text-base"></i>
            </div>
            <div class="ml-3">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Total</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $totalCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4 sm:p-5">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-yellow-100">
                <i class="fas fa-clock text-yellow-600 text-sm sm:text-base"></i>
            </div>
            <div class="ml-3">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Pending</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4 sm:p-5">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-green-100">
                <i class="fas fa-check text-green-600 text-sm sm:text-base"></i>
            </div>
            <div class="ml-3">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Approved</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $approvedCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4 sm:p-5">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-red-100">
                <i class="fas fa-times text-red-600 text-sm sm:text-base"></i>
            </div>
            <div class="ml-3">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Rejected</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $rejectedCount }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="floating-card mb-6">
    <div class="p-4 sm:p-5 border-b border-gray-100">
        <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-filter mr-2 lux-gold-text"></i> Search & Filters
        </h2>
    </div>
    <div class="p-4 sm:p-5">
        <div class="flex flex-col gap-4">
            <!-- Status Filter -->
            <div class="flex flex-wrap gap-2">
                <button onclick="filterRegistrations('all')" 
                        class="px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 
                               {{ request('status') == 'all' || !request('status') ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All ({{ $totalCount }})
                </button>
                <button onclick="filterRegistrations('pending')" 
                        class="px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 
                               {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}">
                    Pending ({{ $pendingCount }})
                </button>
                <button onclick="filterRegistrations('approved')" 
                        class="px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 
                               {{ request('status') == 'approved' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                    Approved ({{ $approvedCount }})
                </button>
                <button onclick="filterRegistrations('rejected')" 
                        class="px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 
                               {{ request('status') == 'rejected' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                    Rejected ({{ $rejectedCount }})
                </button>
            </div>

            <!-- Search -->
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search by user or UKM..." 
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg input-lux focus:input-lux:focus transition duration-200 text-sm">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>
</div>

<!-- Registrations Table/Card View -->
<div class="floating-card overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 sm:p-5 border-b bg-gray-50/50 gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-database mr-2 lux-gold-text"></i>
                @if(request('status') && request('status') != 'all')
                    {{ ucfirst(request('status')) }} Registration Requests
                @else
                    All Registration Requests
                @endif
            </h2>
            <p class="text-xs text-gray-600 mt-1 sm:hidden">{{ $registrations->count() }} of {{ $registrations->total() }} registrations</p>
        </div>
        
        <div class="flex items-center space-x-2">
            <span class="text-xs sm:text-sm font-medium text-gray-600 uppercase hidden sm:block">Show:</span>
            <select onchange="window.location.href = this.value" 
                    class="w-full sm:w-auto border border-gray-300 rounded-lg px-3 py-1.5 text-sm input-lux focus:input-lux:focus">
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
        @if($registrations->count() > 0)
            <!-- Mobile Card View -->
            <div class="sm:hidden p-4 space-y-4">
                @foreach($registrations as $registration)
                <div class="floating-card p-4 border border-gray-200 registration-card" 
                     data-user="{{ strtolower($registration->user->name) }}"
                     data-ukm="{{ strtolower($registration->ukm->name) }}"
                     data-status="{{ $registration->status }}">
                    <div class="flex items-start space-x-3 mb-3">
                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-amber-500 to-yellow-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">
                                {{ substr($registration->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $registration->user->name }}</h3>
                            <p class="text-xs text-gray-500 truncate">{{ $registration->user->email }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 mt-1">
                                <i class="fas fa-users mr-1 text-xs"></i>
                                {{ Str::limit($registration->ukm->name, 15) }}
                            </span>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                            {{ $registration->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $registration->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            <i class="fas 
                                {{ $registration->status == 'pending' ? 'fa-clock' : '' }}
                                {{ $registration->status == 'approved' ? 'fa-check' : '' }}
                                {{ $registration->status == 'rejected' ? 'fa-times' : '' }} 
                                mr-1 text-xs"></i>
                            {{ ucfirst($registration->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-xs text-gray-600 line-clamp-2">
                            <strong class="text-gray-700">Motivation:</strong> {{ Str::limit($registration->motivation, 60) }}
                        </p>
                        
                        @if($registration->experience)
                        <p class="text-xs text-gray-600 mt-1 line-clamp-1">
                            <strong class="text-gray-700">Experience:</strong> {{ Str::limit($registration->experience, 40) }}
                        </p>
                        @endif
                    </div>
                    
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-3">
                        <div>
                            <span class="font-medium">{{ $registration->created_at->format('d M') }}</span>
                            <span class="text-gray-400 ml-2">{{ $registration->created_at->format('H:i') }}</span>
                        </div>
                        <span class="text-gray-400">{{ $registration->created_at->diffForHumans() }}</span>
                    </div>
                    
                    @if($registration->status == 'pending')
                    <div class="flex space-x-2 pt-3 border-t border-gray-100">
                        <form action="{{ route('admin.registrations.updateStatus', $registration->id) }}" method="POST" class="flex-1">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" 
                                    class="w-full bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-700 transition duration-200 flex items-center justify-center font-medium"
                                    onclick="return confirm('Approve this registration?')">
                                <i class="fas fa-check mr-1 text-xs"></i> Approve
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.registrations.updateStatus', $registration->id) }}" method="POST" class="flex-1">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" 
                                    class="w-full bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-700 transition duration-200 flex items-center justify-center font-medium"
                                    onclick="return confirm('Reject this registration?')">
                                <i class="fas fa-times mr-1 text-xs"></i> Reject
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="text-center text-gray-500 text-xs py-2">
                        <i class="fas fa-check-circle text-green-500 text-base mb-1"></i>
                        <div>Processed</div>
                    </div>
                    @endif
                    
                    <button onclick="showDetails({{ $registration->id }})" 
                            class="w-full lux-button px-3 py-1.5 rounded-lg text-xs hover:bg-amber-700 transition duration-200 flex items-center justify-center font-medium mt-2">
                        <i class="fas fa-eye mr-1 text-xs"></i> View Details
                    </button>
                </div>
                @endforeach
            </div>
            
            <!-- Desktop Table View -->
            <div class="hidden sm:block">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-100/70 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Applicant & UKM
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($registrations as $registration)
                        <tr class="hover-row-table transition duration-150 registration-row" 
                            data-user="{{ strtolower($registration->user->name) }}"
                            data-ukm="{{ strtolower($registration->ukm->name) }}"
                            data-status="{{ $registration->status }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-amber-500 to-yellow-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ substr($registration->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $registration->user->email }}</div>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                                <i class="fas fa-users mr-1 text-xs"></i>
                                                {{ $registration->ukm->name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <div class="text-sm text-gray-900 mb-2">
                                        <strong class="text-gray-700">Motivation:</strong> 
                                        <span class="text-gray-600">{{ Str::limit($registration->motivation, 80) }}</span>
                                    </div>
                                    
                                    @if($registration->experience)
                                    <div class="text-sm text-gray-600 mb-1">
                                        <strong class="text-gray-700">Experience:</strong> 
                                        <span>{{ Str::limit($registration->experience, 50) }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($registration->skills)
                                    <div class="text-sm text-gray-600">
                                        <strong class="text-gray-700">Skills:</strong> 
                                        <span>{{ Str::limit($registration->skills, 50) }}</span>
                                    </div>
                                    @endif
                                    
                                    <button onclick="showDetails({{ $registration->id }})" 
                                            class="mt-2 text-amber-600 hover:text-amber-800 text-xs font-medium transition duration-200 flex items-center">
                                        <i class="fas fa-eye mr-1"></i>View Details
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $registration->status == 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                                        {{ $registration->status == 'approved' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                                        {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}">
                                        <i class="fas 
                                            {{ $registration->status == 'pending' ? 'fa-clock' : '' }}
                                            {{ $registration->status == 'approved' ? 'fa-check' : '' }}
                                            {{ $registration->status == 'rejected' ? 'fa-times' : '' }} 
                                            mr-1 text-xs"></i>
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                    
                                    @if($registration->status != 'pending')
                                        <div class="text-xs text-gray-500">
                                            <div>By: {{ $registration->approver->name ?? 'System' }}</div>
                                            <div>{{ $registration->approved_at?->format('M d, Y H:i') }}</div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900">{{ $registration->created_at->format('M d, Y') }}</span>
                                    <span class="text-gray-400">{{ $registration->created_at->format('H:i') }}</span>
                                    <span class="text-xs text-gray-400 mt-1">
                                        {{ $registration->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($registration->status == 'pending')
                                    <div class="flex flex-col space-y-2">
                                        <form action="{{ route('admin.registrations.updateStatus', $registration->id) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" 
                                                    class="w-full bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-green-700 transition duration-200 flex items-center justify-center font-medium"
                                                    onclick="return confirm('Approve this registration?')">
                                                <i class="fas fa-check mr-1 text-xs"></i> Approve
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.registrations.updateStatus', $registration->id) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" 
                                                    class="w-full bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-700 transition duration-200 flex items-center justify-center font-medium"
                                                    onclick="return confirm('Reject this registration?')">
                                                <i class="fas fa-times mr-1 text-xs"></i> Reject
                                            </button>
                                        </form>
                                        
                                        <button onclick="showDetails({{ $registration->id }})" 
                                                class="w-full lux-button px-3 py-1.5 rounded-lg text-sm hover:bg-amber-700 transition duration-200 flex items-center justify-center font-medium">
                                            <i class="fas fa-eye mr-1 text-xs"></i> Review
                                        </button>
                                    </div>
                                @else
                                    <div class="text-center text-gray-500 text-sm py-2">
                                        <i class="fas fa-check-circle text-green-500 text-lg mb-1"></i>
                                        <div>Processed</div>
                                    </div>
                                    
                                    <button onclick="showDetails({{ $registration->id }})" 
                                            class="w-full bg-gray-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-gray-700 transition duration-200 flex items-center justify-center font-medium mt-2">
                                        <i class="fas fa-eye mr-1 text-xs"></i> View
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 px-4">
                <div class="mx-auto w-16 h-16 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-clipboard-list text-gray-400 text-2xl sm:text-3xl"></i>
                </div>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No registrations found</h3>
                <p class="text-gray-500 max-w-md mx-auto text-sm">
                    @if(request('status') && request('status') != 'all')
                        There are no {{ request('status') }} registrations at the moment.
                    @else
                        No registration requests have been submitted yet.
                    @endif
                </p>
            </div>
        @endif
    </div>

    @if($registrations->hasPages())
    <div class="px-4 sm:px-5 py-4 border-t border-gray-100 bg-gray-50/50 rounded-b-lg">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
            <div class="text-xs sm:text-sm text-gray-700 font-medium">
                Showing {{ $registrations->firstItem() }} to {{ $registrations->lastItem() }} of {{ $registrations->total() }} results
            </div>
            <div class="flex space-x-1.5 overflow-x-auto pb-1">
                @if($registrations->onFirstPage())
                    <span class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed text-xs sm:text-sm whitespace-nowrap">
                        <i class="fas fa-angle-left mr-1"></i> Prev
                    </span>
                @else
                    <a href="{{ $registrations->previousPageUrl() }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm whitespace-nowrap">
                        <i class="fas fa-angle-left mr-1"></i> Prev
                    </a>
                @endif

                @php
                    $currentPage = $registrations->currentPage();
                    $lastPage = $registrations->lastPage();
                    $start = max(1, $currentPage - 1);
                    $end = min($lastPage, $currentPage + 1);
                @endphp

                @if ($start > 1)
                    <a href="{{ $registrations->url(1) }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm">1</a>
                    @if ($start > 2)
                        <span class="px-2 sm:px-3 py-1 text-gray-500">...</span>
                    @endif
                @endif
                
                @for ($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <span class="px-2 sm:px-3 py-1 rounded-lg border bg-amber-600 text-white text-xs sm:text-sm font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $registrations->url($page) }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="px-2 sm:px-3 py-1 text-gray-500">...</span>
                    @endif
                    <a href="{{ $registrations->url($lastPage) }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm">{{ $lastPage }}</a>
                @endif

                @if($registrations->hasMorePages())
                    <a href="{{ $registrations->nextPageUrl() }}" class="px-2 sm:px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-xs sm:text-sm whitespace-nowrap">
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

<!-- Registration Details Modal -->
<dialog id="detailsModal" class="modal-lux bg-white w-full max-w-2xl max-h-[90vh] overflow-hidden">
    <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Registration Details</h3>
        <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 transition duration-200 p-1">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <div class="p-4 sm:p-6 overflow-y-auto max-h-[60vh]" id="modalContent">
        <!-- Content will be loaded via AJAX -->
    </div>
    
    <div class="flex justify-end space-x-3 p-4 sm:p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
        <button type="button" onclick="closeDetailsModal()" 
                class="w-full sm:w-auto px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium text-sm">
            Close
        </button>
    </div>
</dialog>

<script>
// Filter registrations by status
function filterRegistrations(status) {
    const url = new URL(window.location.href);
    if (status === 'all') {
        url.searchParams.delete('status');
    } else {
        url.searchParams.set('status', status);
    }
    window.location.href = url.toString();
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            // For desktop table rows
            const rows = document.querySelectorAll('.registration-row');
            rows.forEach(row => {
                const userName = row.getAttribute('data-user');
                const ukmName = row.getAttribute('data-ukm');
                
                if (userName.includes(searchTerm) || ukmName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // For mobile cards
            const cards = document.querySelectorAll('.registration-card');
            cards.forEach(card => {
                const userName = card.getAttribute('data-user');
                const ukmName = card.getAttribute('data-ukm');
                
                if (userName.includes(searchTerm) || ukmName.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});

// Show registration details
function showDetails(registrationId) {
    fetch(`/admin/registrations/${registrationId}/details`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('detailsModal').showModal();
        })
        .catch(error => {
            console.error('Error loading details:', error);
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl sm:text-4xl mb-4"></i>
                    <p class="text-gray-600 text-sm">Failed to load registration details.</p>
                </div>
            `;
            document.getElementById('detailsModal').showModal();
        });
}

function closeDetailsModal() {
    document.getElementById('detailsModal').close();
}



// Close modal on backdrop click and escape key
document.getElementById('detailsModal').addEventListener('click', function(e) {
    if (e.target === this) closeDetailsModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (document.getElementById('detailsModal').open) {
            closeDetailsModal();
        }
    }
});
</script>
@endsection