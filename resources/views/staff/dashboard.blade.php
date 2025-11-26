@extends('layouts.staff')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Staff Dashboard</h1>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold">{{ $stats['managed_ukms'] }}</h3>
                <p class="text-gray-600">Managed UKM</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded">
                <i class="fas fa-clipboard-list text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold">{{ $stats['pending_registrations'] }}</h3>
                <p class="text-gray-600">Pending Registrations</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded">
                <i class="fas fa-calendar text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold">{{ $stats['total_events'] }}</h3>
                <p class="text-gray-600">Total Events</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded">
                <i class="fas fa-newspaper text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold">{{ $stats['total_feeds'] }}</h3>
                <p class="text-gray-600">Total Feeds</p>
            </div>
        </div>
    </div>
</div>

<!-- Managed UKMs -->
<div class="bg-white rounded shadow mb-6">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">My Managed UKM</h2>
    </div>
    <div class="p-4">
        @if($managedUkms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($managedUkms as $ukm)
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-center mb-3">
                        @if($ukm->logo)
                            <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" class="w-12 h-12 rounded object-cover mr-3">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center mr-3">
                                <i class="fas fa-users text-gray-400"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold">{{ $ukm->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $ukm->category->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('staff.ukms.edit', $ukm->id) }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
                        Manage UKM →
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">You are not assigned to any UKM yet.</p>
        @endif
    </div>
</div>

<!-- Recent Registrations -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">Recent Registrations</h2>
    </div>
    <div class="p-4">
        @if($recentRegistrations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">User</th>
                            <th class="p-2 text-left">UKM</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRegistrations as $registration)
                        <tr class="border-b">
                            <td class="p-2">{{ $registration->user->name }}</td>
                            <td class="p-2">{{ $registration->ukm->name }}</td>
                            <td class="p-2">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ $registration->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $registration->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $registration->status }}
                                </span>
                            </td>
                            <td class="p-2">{{ $registration->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">No registrations yet.</p>
        @endif
    </div>
</div>
@endsection