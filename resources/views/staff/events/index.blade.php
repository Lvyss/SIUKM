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

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Event Management</h1>
    <div class="text-sm font-semibold lux-purple-text px-3 py-1 rounded-full border border-gray-300 bg-violet-50">
        <i class="fas fa-calendar-alt mr-1 lux-purple-text"></i> Total: {{ $events->total() }} events
    </div>
</div>

<!-- Statistics Cards - SESUAI DENGAN DASHBOARD -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-indigo-100">
                <i class="fas fa-calendar-alt text-indigo-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Total Events</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalEvents }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-violet-100">
                <i class="fas fa-rocket text-violet-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Upcoming</p>
                <p class="text-xl font-bold text-gray-900">{{ $upcomingEvents }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-purple-100">
                <i class="fas fa-history text-purple-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Past Events</p>
                <p class="text-xl font-bold text-gray-900">{{ $pastEvents }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-fuchsia-100">
                <i class="fas fa-star text-fuchsia-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Today</p>
                <p class="text-xl font-bold text-gray-900">{{ $todayEvents }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100">
                <i class="fas fa-image text-blue-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">With Posters</p>
                <p class="text-xl font-bold text-gray-900">{{ $eventsWithPosters }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="floating-card mb-6">
    <div class="p-5 border-b border-gray-100 bg-violet-50/50">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-filter mr-2 lux-purple-text"></i> Search & Filters
        </h2>
    </div>
    <div class="p-5">
        <form action="{{ route('staff.events.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Search Events</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Title, description, location, or UKM..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus transition duration-150">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">UKM</label>
                <select name="ukm_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus transition duration-150">
                    <option value="">All UKM</option>
                    @foreach($managedUkms as $ukm)
                        <option value="{{ $ukm->id }}" {{ request('ukm_id') == $ukm->id ? 'selected' : '' }}>
                            {{ $ukm->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Date Filter</label>
                <select name="date_filter" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux focus:input-lux:focus transition duration-150">
                    <option value="">All Dates</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="past" {{ request('date_filter') == 'past' ? 'selected' : '' }}>Past Events</option>
                    <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                </select>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        class="lux-button px-4 py-2 rounded-lg hover:bg-violet-700 transition duration-200 flex items-center font-semibold text-sm">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('staff.events.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200 flex items-center font-semibold text-sm">
                    <i class="fas fa-refresh mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Events Table -->
<div class="floating-card overflow-hidden">
    <div class="flex justify-between items-center p-5 border-b bg-violet-50/50">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-database mr-2 lux-purple-text"></i>
            @if(request('ukm_id'))
                @php
                    $selectedUkm = $managedUkms->where('id', request('ukm_id'))->first();
                @endphp
                Events - {{ $selectedUkm->name ?? 'Selected UKM' }}
            @elseif(request('date_filter'))
                {{ ucfirst(str_replace('_', ' ', request('date_filter'))) }} Events
            @else
                My Managed Events
            @endif
        </h2>
        
        <div class="flex items-center space-x-4">
            <button onclick="openAddModal()" 
                    class="lux-button px-4 py-2 rounded-lg hover:bg-violet-700 transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>Add Event
            </button>
            
            <span class="text-sm text-gray-600">Show:</span>
            <select onchange="window.location.href = this.value" 
                    class="border border-gray-300 rounded px-3 py-1 text-sm input-lux focus:input-lux:focus">
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
        @if($events->count() > 0)
            <table class="w-full min-w-full">
                <thead>
                    <tr class="bg-violet-100/70 border-b border-gray-200">
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Poster</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Event Details</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">UKM</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date & Time</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Location</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($events as $event)
                    @php
                        $isPast = $event->event_date->isPast();
                        $isToday = $event->event_date->isToday();
                    @endphp
                    <tr class="hover-row-table transition duration-150 {{ $isPast ? 'bg-gray-50' : '' }}">
                        <td class="p-4">
                            @if($event->poster_image)
                                <img src="{{ $event->poster_image }}" alt="{{ $event->title }}" 
                                     class="w-16 h-16 rounded-lg object-cover shadow-sm border">
                            @else
                                <div class="w-16 h-16 bg-violet-100 rounded-lg flex items-center justify-center border border-violet-200">
                                    <i class="fas fa-calendar-alt text-violet-400 text-xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="font-semibold text-gray-900">{{ $event->title }}</div>
                            <div class="text-sm text-gray-600 mt-1">{{ Str::limit($event->description, 80) }}</div>
                            @if($event->registration_link)
                                <a href="{{ $event->registration_link }}" target="_blank" 
                                   class="text-xs text-violet-600 hover:text-violet-800 mt-1 inline-block font-medium">
                                    <i class="fas fa-link mr-1"></i>Registration Link
                                </a>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800">
                                {{ $event->ukm->name }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="font-medium text-gray-900">{{ $event->event_date->format('d M Y') }}</div>
                            <div class="text-sm text-gray-600">{{ date('H:i', strtotime($event->event_time)) }}</div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $event->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="p-4 text-gray-700 max-w-xs">
                            <div class="text-sm">{{ Str::limit($event->location, 30) }}</div>
                        </td>
                        <td class="p-4">
                            @if($isPast)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-clock mr-1"></i> Past
                                </span>
                            @elseif($isToday)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-star mr-1"></i> Today
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-calendar-check mr-1"></i> Upcoming
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex space-x-2">
                                <button onclick="editEvent({{ $event }})" 
                                        class="lux-button px-3 py-1.5 rounded-lg text-sm hover:bg-violet-700 transition duration-200 flex items-center font-medium shadow-md shadow-violet-500/20">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <form action="{{ route('staff.events.destroy', $event->id) }}" method="POST" 
                                      class="inline" onsubmit="return confirmDeleteEvent('{{ $event->title }}')">
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
                <i class="fas fa-calendar-times text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg mb-2 font-semibold">No events found</p>
                <p class="text-gray-500 text-sm">
                    @if(request()->hasAny(['search', 'ukm_id', 'date_filter']))
                        Try adjusting your search or filters to see more results.
                    @else
                        No events have been created for your managed UKMs yet.
                    @endif
                </p>
                <button onclick="openAddModal()" 
                        class="mt-4 lux-button px-4 py-2 rounded-lg hover:bg-violet-700 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Create First Event
                </button>
            </div>
        @endif
    </div>
    
    @if($events->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 bg-violet-50/50 rounded-b-lg">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div class="text-sm text-gray-700 font-medium">
                Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} results
            </div>
            <div class="flex space-x-1.5">
                @if($events->onFirstPage())
                    <span class="px-3 py-1 rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed text-sm">
                        <i class="fas fa-angle-left"></i> Previous
                    </span>
                @else
                    <a href="{{ $events->previousPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">
                        <i class="fas fa-angle-left"></i> Previous
                    </a>
                @endif

                @php
                    $currentPage = $events->currentPage();
                    $lastPage = $events->lastPage();
                    $start = max(1, $currentPage - 1);
                    $end = min($lastPage, $currentPage + 1);
                @endphp

                @if ($start > 1)
                    <a href="{{ $events->url(1) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">1</a>
                    @if ($start > 2)
                        <span class="px-3 py-1 text-gray-500">...</span>
                    @endif
                @endif
                
                @for ($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <span class="px-3 py-1 rounded-lg border bg-violet-600 text-white text-sm font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $events->url($page) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="px-3 py-1 text-gray-500">...</span>
                    @endif
                    <a href="{{ $events->url($lastPage) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">{{ $lastPage }}</a>
                @endif

                @if($events->hasMorePages())
                    <a href="{{ $events->nextPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">
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

<!-- Add Event Modal -->
<dialog id="addEventModal" class="modal-lux bg-white w-full max-w-2xl">
    <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-violet-50 rounded-t-xl">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-2 lux-purple-text"></i> Create New Event
        </h3>
        <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-700 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="addEventForm" action="{{ route('staff.events.store') }}" method="POST" enctype="multipart/form-data">
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
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Title *</label>
                    <input type="text" name="title" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  {{ $errors->has('title') && !session('edit_errors') ? 'border-red-500' : '' }}"
                           value="{{ old('title') }}"
                           placeholder="Enter event title">
                    @if($errors->has('title') && !session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('title') }}</p>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                <textarea name="description" required 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                 {{ $errors->has('description') && !session('edit_errors') ? 'border-red-500' : '' }}"
                          rows="4" 
                          placeholder="Describe your event...">{{ old('description') }}</textarea>
                @if($errors->has('description') && !session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('description') }}</p>
                @endif
                <p class="text-gray-500 text-xs mt-2">Minimal 10 karakter, maksimal 2000 karakter</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date *</label>
                    <input type="date" name="event_date" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  {{ $errors->has('event_date') && !session('edit_errors') ? 'border-red-500' : '' }}"
                           value="{{ old('event_date') }}"
                           min="{{ date('Y-m-d') }}">
                    @if($errors->has('event_date') && !session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('event_date') }}</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Time *</label>
                    <input type="time" name="event_time" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  {{ $errors->has('event_time') && !session('edit_errors') ? 'border-red-500' : '' }}"
                           value="{{ old('event_time') }}">
                    @if($errors->has('event_time') && !session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('event_time') }}</p>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Location *</label>
                <input type="text" name="location" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                              {{ $errors->has('location') && !session('edit_errors') ? 'border-red-500' : '' }}"
                       value="{{ old('location') }}"
                       placeholder="Enter event location">
                @if($errors->has('location') && !session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('location') }}</p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Poster Image</label>
                    <input type="file" name="poster_image" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                  file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700
                                  hover:file:bg-violet-100
                                  {{ $errors->has('poster_image') && !session('edit_errors') ? 'border-red-500' : '' }}">
                    @if($errors->has('poster_image') && !session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('poster_image') }}</p>
                    @endif
                    <p class="text-gray-500 text-xs mt-2">Format: JPEG, PNG, JPG, GIF, WebP | Maksimal 5MB</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Registration Link</label>
                    <input type="url" name="registration_link" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  {{ $errors->has('registration_link') && !session('edit_errors') ? 'border-red-500' : '' }}"
                           value="{{ old('registration_link') }}"
                           placeholder="https://example.com/register">
                    @if($errors->has('registration_link') && !session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('registration_link') }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3 p-6 border-t border-gray-100 bg-violet-50 rounded-b-xl">
            <button type="button" onclick="closeAddModal()" 
                    class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium">
                Cancel
            </button>
            <button type="submit" 
                    class="lux-button px-4 py-2.5 rounded-lg hover:bg-violet-700 transition duration-200 font-medium flex items-center">
                <i class="fas fa-save mr-2"></i> Save Event
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Event Modal -->
<dialog id="editEventModal" class="modal-lux bg-white w-full max-w-2xl">
    <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-violet-50 rounded-t-xl">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-edit mr-2 lux-purple-text"></i> Edit Event
        </h3>
        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-700 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <form id="editEventForm" method="POST" enctype="multipart/form-data">
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
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                   {{ $errors->has('ukm_id') && session('edit_errors') ? 'border-red-500' : '' }}"
                            id="editUkmId">
                        @foreach($managedUkms as $ukm)
                            <option value="{{ $ukm->id }}">{{ $ukm->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('ukm_id') && session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('ukm_id') }}</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Title *</label>
                    <input type="text" name="title" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  {{ $errors->has('title') && session('edit_errors') ? 'border-red-500' : '' }}"
                           id="editTitle">
                    @if($errors->has('title') && session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('title') }}</p>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                <textarea name="description" required 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                 {{ $errors->has('description') && session('edit_errors') ? 'border-red-500' : '' }}"
                          rows="4" 
                          id="editDescription"></textarea>
                @if($errors->has('description') && session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('description') }}</p>
                @endif
                <p class="text-gray-500 text-xs mt-2">Minimal 10 karakter, maksimal 2000 karakter</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date *</label>
                    <input type="date" name="event_date" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  {{ $errors->has('event_date') && session('edit_errors') ? 'border-red-500' : '' }}"
                           id="editEventDate">
                    @if($errors->has('event_date') && session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('event_date') }}</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Time *</label>
                    <input type="time" name="event_time" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  {{ $errors->has('event_time') && session('edit_errors') ? 'border-red-500' : '' }}"
                           id="editEventTime">
                    @if($errors->has('event_time') && session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('event_time') }}</p>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Location *</label>
                <input type="text" name="location" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                              {{ $errors->has('location') && session('edit_errors') ? 'border-red-500' : '' }}"
                       id="editLocation">
                @if($errors->has('location') && session('edit_errors'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('location') }}</p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Poster Image</label>
                    <input type="file" name="poster_image" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                  file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700
                                  hover:file:bg-violet-100
                                  {{ $errors->has('poster_image') && session('edit_errors') ? 'border-red-500' : '' }}">
                    @if($errors->has('poster_image') && session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('poster_image') }}</p>
                    @endif
                    <div class="mt-3" id="currentPoster">
                        <p class="text-sm font-medium text-gray-600 mb-2">Current Poster:</p>
                        <img id="currentPosterImage" src="" class="w-32 h-32 rounded-lg border object-cover shadow-sm">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Registration Link</label>
                    <input type="url" name="registration_link" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux focus:input-lux:focus transition duration-200
                                  {{ $errors->has('registration_link') && session('edit_errors') ? 'border-red-500' : '' }}"
                           id="editRegistrationLink">
                    @if($errors->has('registration_link') && session('edit_errors'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('registration_link') }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3 p-6 border-t border-gray-100 bg-violet-50 rounded-b-xl">
            <button type="button" onclick="closeEditModal()" 
                    class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium">
                Cancel
            </button>
            <button type="submit" 
                    class="lux-button px-4 py-2.5 rounded-lg hover:bg-violet-700 transition duration-200 font-medium flex items-center">
                <i class="fas fa-save mr-2"></i> Update Event
            </button>
        </div>
    </form>
</dialog>

<script>
function confirmDeleteEvent(title) {
    return confirm(`Are you sure you want to delete event "${title}"? This action cannot be undone.`);
}

// Modal Functions
function openAddModal() {
    document.getElementById('addEventModal').showModal();
    document.getElementById('addEventForm').reset();
    
    const errorInputs = document.querySelectorAll('#addEventForm .border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    const errorMessages = document.querySelectorAll('#addEventForm .text-red-500');
    errorMessages.forEach(msg => msg.remove());
}

function closeAddModal() {
    document.getElementById('addEventModal').close();
}

function closeEditModal() {
    document.getElementById('editEventModal').close();
}

function editEvent(event) {
    const form = document.getElementById('editEventForm');
    form.action = `/staff/events/${event.id}`;
    
    document.getElementById('editUkmId').value = event.ukm_id;
    document.getElementById('editTitle').value = event.title;
    document.getElementById('editDescription').value = event.description;
    document.getElementById('editEventDate').value = event.event_date;
    document.getElementById('editEventTime').value = event.event_time;
    document.getElementById('editLocation').value = event.location;
    document.getElementById('editRegistrationLink').value = event.registration_link || '';
    
    const currentPoster = document.getElementById('currentPoster');
    const currentPosterImage = document.getElementById('currentPosterImage');
    if (event.poster_image) {
        currentPosterImage.src = event.poster_image;
        currentPoster.style.display = 'block';
    } else {
        currentPoster.style.display = 'none';
    }
    
    document.getElementById('editEventModal').showModal();
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const hasAddErrors = {{ $errors->any() && !session('edit_errors') ? 'true' : 'false' }};
    if (hasAddErrors) {
        document.getElementById('addEventModal').showModal();
    }

    const hasEditErrors = {{ $errors->any() && session('edit_errors') ? 'true' : 'false' }};
    if (hasEditErrors) {
        document.getElementById('editEventModal').showModal();
    }

    // Close modals on backdrop click
    document.getElementById('addEventModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddModal();
    });
    
    document.getElementById('editEventModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // Image validation
    const imageInputs = document.querySelectorAll('input[type="file"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    this.value = '';
                    return;
                }
            }
        });
    });
});

setTimeout(() => {
    const flashMessages = document.querySelectorAll('.fixed');
    flashMessages.forEach(msg => msg.remove());
}, 5000);
</script>
@endsection