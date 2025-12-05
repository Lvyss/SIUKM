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
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .floating-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }
    
    /* Tombol Utama Purple/Violet */
    .lux-button {
        background-color: #7c3aed; /* Violet-600 */
        color: white;
        transition: background-color 0.2s;
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
        width: 95vw;
        max-width: 28rem;
        margin: 1rem auto;
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
</style>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Registrations Matrix & Management</h1>
    <div class="text-sm font-semibold lux-purple-text px-3 py-1 rounded-full border border-gray-300 bg-violet-50 whitespace-nowrap">
        <i class="fas fa-clipboard-check mr-1 lux-purple-text"></i> Total: **{{ $registrations->total() }} registrations**
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 flex-shrink-0">
                <i class="fas fa-list text-blue-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $totalCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-yellow-100 flex-shrink-0">
                <i class="fas fa-clock text-yellow-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Pending</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100 flex-shrink-0">
                <i class="fas fa-check text-green-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Approved</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $approvedCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-3 sm:p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-red-100 flex-shrink-0">
                <i class="fas fa-times text-red-600 text-sm"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Rejected</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $rejectedCount }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="floating-card mb-6">
    <div class="p-4 sm:p-5 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-filter mr-2 lux-purple-text"></i> Search & Filters
        </h2>
    </div>
    <div class="p-4 sm:p-5">
        <div class="space-y-4">
            <!-- Status Filter -->
            <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                <button onclick="filterRegistrations('all')" 
                        class="px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                               {{ request('status') == 'all' || !request('status') ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All ({{ $totalCount }})
                </button>
                <button onclick="filterRegistrations('pending')" 
                        class="px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                               {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}">
                    Pending ({{ $pendingCount }})
                </button>
                <button onclick="filterRegistrations('approved')" 
                        class="px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                               {{ request('status') == 'approved' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                    Approved ({{ $approvedCount }})
                </button>
                <button onclick="filterRegistrations('rejected')" 
                        class="px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                               {{ request('status') == 'rejected' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                    Rejected ({{ $rejectedCount }})
                </button>
            </div>

            <!-- Search -->
            <div class="relative w-full">
                <input type="text" id="searchInput" placeholder="Search by user or UKM..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg input-lux:focus transition duration-200 text-sm sm:text-base">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>
</div>

<!-- Registrations Table -->
<div class="floating-card overflow-hidden">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border-b bg-gray-50/50 gap-4">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-database mr-2 lux-purple-text"></i>
            @if(request('status') && request('status') != 'all')
                {{ ucfirst(request('status')) }} Registration Requests
            @else
                All Registration Requests
            @endif
        </h2>
        
        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <span class="text-xs font-medium text-gray-600 uppercase whitespace-nowrap">Show:</span>
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
    
    <div class="overflow-x-auto px-4 sm:px-0">
        @if($registrations->count() > 0)
            <table class="w-full min-w-full">
                <thead class="hidden sm:table-header-group">
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
                    <!-- Mobile View -->
                    <tr class="hover-row-table transition duration-150 registration-row block sm:table-row border-b sm:border-b-0"
                        data-user="{{ strtolower($registration->user->name) }}"
                        data-ukm="{{ strtolower($registration->ukm->name) }}"
                        data-status="{{ $registration->status }}">
                        <td class="block sm:hidden p-4 mx-2 sm:mx-0 my-2 sm:my-0 bg-white rounded-lg shadow-sm">
                            <div class="space-y-4">
                                <!-- Applicant & UKM -->
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-amber-500 to-yellow-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ substr($registration->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</div>
                                        <div class="text-xs text-gray-500 truncate">{{ $registration->user->email }}</div>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                                <i class="fas fa-users mr-1 text-xs"></i>
                                                {{ $registration->ukm->name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="space-y-2">
                                    <div>
                                        <strong class="text-xs text-gray-700">Motivation:</strong> 
                                        <p class="text-xs text-gray-600 line-clamp-2">{{ $registration->motivation }}</p>
                                    </div>
                                    
                                    @if($registration->experience)
                                    <div>
                                        <strong class="text-xs text-gray-700">Experience:</strong> 
                                        <p class="text-xs text-gray-600">{{ Str::limit($registration->experience, 40) }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($registration->skills)
                                    <div>
                                        <strong class="text-xs text-gray-700">Skills:</strong> 
                                        <p class="text-xs text-gray-600">{{ Str::limit($registration->skills, 40) }}</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Status & Date -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <div class="text-xs font-medium text-gray-700">Status</div>
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
                                    </div>
                                    <div>
                                        <div class="text-xs font-medium text-gray-700">Date</div>
                                        <div class="text-xs text-gray-900">{{ $registration->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $registration->created_at->format('H:i') }}</div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="pt-4 border-t border-gray-200">
                                    @if($registration->status == 'pending')
                                        <div class="grid grid-cols-2 gap-2">
                                            <form action="{{ route('staff.registrations.updateStatus', $registration->id) }}" method="POST" class="w-full">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" 
                                                        class="w-full bg-green-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-700 transition duration-200 flex items-center justify-center font-medium"
                                                        onclick="return confirm('Approve this registration?')">
                                                    <i class="fas fa-check mr-2 text-xs"></i> Approve
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('staff.registrations.updateStatus', $registration->id) }}" method="POST" class="w-full">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" 
                                                        class="w-full bg-red-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-700 transition duration-200 flex items-center justify-center font-medium"
                                                        onclick="return confirm('Reject this registration?')">
                                                    <i class="fas fa-times mr-2 text-xs"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                        <button onclick="showDetails({{ $registration->id }})" 
                                                class="w-full lux-button px-3 py-2 rounded-lg text-sm hover:bg-violet-700 transition duration-200 flex items-center justify-center font-medium mt-2">
                                            <i class="fas fa-eye mr-2 text-xs"></i> Review Details
                                        </button>
                                    @else
                                        <div class="text-center py-2">
                                            <i class="fas fa-check-circle text-green-500 text-lg mb-2"></i>
                                            <div class="text-xs text-gray-500">Already Processed</div>
                                        </div>
                                        <button onclick="showDetails({{ $registration->id }})" 
                                                class="w-full bg-gray-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-gray-700 transition duration-200 flex items-center justify-center font-medium">
                                            <i class="fas fa-eye mr-2 text-xs"></i> View Details
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Desktop View -->
                        <td class="hidden sm:table-cell px-6 py-4">
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
                        <td class="hidden sm:table-cell px-6 py-4">
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
                                        class="mt-2 text-violet-600 hover:text-violet-800 text-xs font-medium transition duration-200 flex items-center">
                                    <i class="fas fa-eye mr-1"></i>View Details
                                </button>
                            </div>
                        </td>
                        <td class="hidden sm:table-cell px-6 py-4">
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
                        <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{ $registration->created_at->format('M d, Y') }}</span>
                                <span class="text-gray-400">{{ $registration->created_at->format('H:i') }}</span>
                                <span class="text-xs text-gray-400 mt-1">
                                    {{ $registration->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </td>
                        <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($registration->status == 'pending')
                                <div class="flex flex-col space-y-2">
                                    <form action="{{ route('staff.registrations.updateStatus', $registration->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" 
                                                class="w-full bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-green-700 transition duration-200 flex items-center justify-center font-medium shadow-md shadow-green-500/20"
                                                onclick="return confirm('Approve this registration?')">
                                            <i class="fas fa-check mr-1 text-xs"></i> Approve
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('staff.registrations.updateStatus', $registration->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" 
                                                class="w-full bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-700 transition duration-200 flex items-center justify-center font-medium shadow-md shadow-red-500/20"
                                                onclick="return confirm('Reject this registration?')">
                                            <i class="fas fa-times mr-1 text-xs"></i> Reject
                                        </button>
                                    </form>
                                    
                                    <button onclick="showDetails({{ $registration->id }})" 
                                            class="w-full lux-button px-3 py-1.5 rounded-lg text-sm hover:bg-violet-700 transition duration-200 flex items-center justify-center font-medium shadow-md shadow-violet-500/20">
                                        <i class="fas fa-eye mr-1 text-xs"></i> Review
                                    </button>
                                </div>
                            @else
                                <div class="text-center text-gray-500 text-sm py-2">
                                    <i class="fas fa-check-circle text-green-500 text-lg mb-1"></i>
                                    <div>Processed</div>
                                </div>
                                
                                <button onclick="showDetails({{ $registration->id }})" 
                                        class="w-full bg-gray-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-gray-700 transition duration-200 flex items-center justify-center font-medium shadow-md shadow-gray-500/20 mt-2">
                                    <i class="fas fa-eye mr-1 text-xs"></i> View
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-12 px-4">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-clipboard-list text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No registrations found</h3>
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
    <div class="px-4 sm:px-5 py-4 border-t border-gray-100 bg-gray-50/50">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
            <div class="text-sm text-gray-700 text-center sm:text-left font-medium">
                Showing **{{ $registrations->firstItem() }}** to **{{ $registrations->lastItem() }}** of **{{ $registrations->total() }}** results
            </div>
            <div class="flex flex-wrap justify-center gap-2">
                @if($registrations->onFirstPage())
                    <span class="px-3 py-1 rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed text-sm">
                        <i class="fas fa-angle-left"></i> Previous
                    </span>
                @else
                    <a href="{{ $registrations->previousPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">
                        <i class="fas fa-angle-left"></i> Previous
                    </a>
                @endif

                @php
                    $currentPage = $registrations->currentPage();
                    $lastPage = $registrations->lastPage();
                    $start = max(1, $currentPage - 1);
                    $end = min($lastPage, $currentPage + 1);
                @endphp

                @if ($start > 1)
                    <a href="{{ $registrations->url(1) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">1</a>
                    @if ($start > 2)
                        <span class="px-3 py-1 text-gray-500">...</span>
                    @endif
                @endif
                
                @for ($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <span class="px-3 py-1 rounded-lg border bg-violet-600 text-white text-sm font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $registrations->url($page) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="px-3 py-1 text-gray-500">...</span>
                    @endif
                    <a href="{{ $registrations->url($lastPage) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">{{ $lastPage }}</a>
                @endif

                @if($registrations->hasMorePages())
                    <a href="{{ $registrations->nextPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">
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

<!-- Registration Details Modal -->
<dialog id="detailsModal" class="modal-lux bg-white">
    <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Registration Details</h3>
        <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <div class="p-4 sm:p-6 overflow-y-auto max-h-[60vh]" id="modalContent">
        <!-- Content will be loaded via AJAX -->
    </div>
    
    <div class="flex justify-end space-x-3 p-4 sm:p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
        <button type="button" onclick="closeDetailsModal()" 
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium text-sm sm:text-base">
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
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
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
});

// Show registration details
function showDetails(registrationId) {
    fetch(`/staff/registrations/${registrationId}/details`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            const modal = document.getElementById('detailsModal');
            modal.showModal();
            
            // Mobile positioning
            if (window.innerWidth < 640) {
                modal.style.margin = '1rem auto';
                modal.style.maxHeight = '90vh';
                modal.style.overflowY = 'auto';
            }
        })
        .catch(error => {
            console.error('Error loading details:', error);
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                    <p class="text-gray-600">Failed to load registration details.</p>
                </div>
            `;
            const modal = document.getElementById('detailsModal');
            modal.showModal();
            
            // Mobile positioning
            if (window.innerWidth < 640) {
                modal.style.margin = '1rem auto';
                modal.style.maxHeight = '90vh';
                modal.style.overflowY = 'auto';
            }
        });
}

function closeDetailsModal() {
    document.getElementById('detailsModal').close();
}

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

// Close modal on backdrop click
document.getElementById('detailsModal').addEventListener('click', function(e) {
    if (e.target === this) closeDetailsModal();
});
</script>
@endsection