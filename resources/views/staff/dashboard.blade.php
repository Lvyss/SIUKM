@extends('layouts.staff')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 border-b pb-2 border-indigo-200">Staff Dashboard</h1>
</div>

<!-- Stats Cards - Mobile: 2 per row, Desktop: 4 per row -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    
    <!-- Managed UKM Card -->
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border-l-4 border-indigo-500">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-indigo-100 rounded-full">
                <i class="fas fa-users text-indigo-600 text-lg sm:text-xl"></i>
            </div>
            <div class="ml-3 sm:ml-4">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['managed_ukms'] }}</h3>
                <p class="text-gray-500 text-xs sm:text-sm">Managed UKM</p>
            </div>
        </div>
    </div>
    
    <!-- Pending Registrations Card -->
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-yellow-100 rounded-full">
                <i class="fas fa-clipboard-list text-yellow-600 text-lg sm:text-xl"></i>
            </div>
            <div class="ml-3 sm:ml-4">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['pending_registrations'] }}</h3>
                <p class="text-gray-500 text-xs sm:text-sm">Pending Registrations</p>
            </div>
        </div>
    </div>
    
    <!-- Total Events Card -->
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-blue-100 rounded-full">
                <i class="fas fa-calendar text-blue-600 text-lg sm:text-xl"></i>
            </div>
            <div class="ml-3 sm:ml-4">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_events'] }}</h3>
                <p class="text-gray-500 text-xs sm:text-sm">Total Events</p>
            </div>
        </div>
    </div>
    
    <!-- Total Feeds Card -->
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border-l-4 border-fuchsia-500">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-fuchsia-100 rounded-full">
                <i class="fas fa-newspaper text-fuchsia-600 text-lg sm:text-xl"></i>
            </div>
            <div class="ml-3 sm:ml-4">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_feeds'] }}</h3>
                <p class="text-gray-500 text-xs sm:text-sm">Total Feeds</p>
            </div>
        </div>
    </div>
</div>

<!-- My Managed UKM Section -->
<div class="bg-white rounded-lg shadow-xl mb-6 overflow-hidden">
    <div class="p-4 sm:p-5 border-b border-gray-100 bg-indigo-50">
        <div class="flex justify-between items-center">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                <i class="fas fa-users text-indigo-600 mr-2"></i>My Managed UKM
            </h2>
        </div>
    </div>
    <div class="p-4 sm:p-5">
        @if($managedUkms->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                @foreach($managedUkms as $ukm)
                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-lg transition duration-300 bg-white">
                    <div class="flex items-start mb-3">
                        @if($ukm->logo)
                            <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" 
                                 class="w-10 h-10 rounded-full object-cover mr-3 border-2 border-indigo-400 flex-shrink-0">
                        @else
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3 border-2 border-indigo-400 flex-shrink-0">
                                <i class="fas fa-users text-indigo-500"></i>
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-base sm:text-lg text-gray-900 truncate">{{ $ukm->name }}</h3>
                            <p class="text-xs text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full inline-block mt-1">
                                {{ $ukm->category->name }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('staff.ukms.index', $ukm->id) }}" 
                       class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 mt-2">
                        Manage UKM <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 sm:py-10 bg-gray-50 rounded-lg">
                <i class="fas fa-users text-gray-300 text-3xl mb-3"></i>
                <p class="text-gray-500">You are not assigned to any UKM yet.</p>
            </div>
        @endif
    </div>
</div>

<!-- Recent Registrations Section -->
<div class="bg-white rounded-lg shadow-xl overflow-hidden">
    <div class="p-4 sm:p-5 border-b border-gray-100 bg-indigo-50">
        <div class="flex justify-between items-center">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                <i class="fas fa-history text-indigo-600 mr-2"></i>Recent Registrations
            </h2>
            <a href="{{ route('staff.registrations.index') }}" 
               class="text-sm text-indigo-500 hover:text-indigo-700 flex items-center">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    <div class="p-0 sm:p-5">
        @if($recentRegistrations->count() > 0)
            <!-- Mobile View - Card Layout -->
            <div class="sm:hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($recentRegistrations as $registration)
                    <div class="p-4 hover:bg-indigo-50 transition duration-150">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $registration->user->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $registration->ukm->name }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold uppercase 
                                {{ $registration->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $registration->status == 'approved' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $registration->status }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span>{{ $registration->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Desktop View - Table Layout -->
            <div class="hidden sm:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-indigo-100 text-indigo-800">
                                <th class="p-3 text-left text-xs font-bold uppercase tracking-wider rounded-tl-lg">User</th>
                                <th class="p-3 text-left text-xs font-bold uppercase tracking-wider">UKM</th>
                                <th class="p-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                                <th class="p-3 text-left text-xs font-bold uppercase tracking-wider rounded-tr-lg">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentRegistrations as $registration)
                            <tr class="hover:bg-indigo-50 transition duration-150">
                                <td class="p-3 text-sm text-gray-700">{{ $registration->user->name }}</td>
                                <td class="p-3 text-sm text-gray-700 font-medium">{{ $registration->ukm->name }}</td>
                                <td class="p-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase 
                                        {{ $registration->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $registration->status == 'approved' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $registration->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm text-gray-500">{{ $registration->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-8 sm:py-10 bg-gray-50 rounded-lg">
                <i class="fas fa-clipboard-list text-gray-300 text-3xl mb-3"></i>
                <p class="text-gray-500">No registrations yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection