@extends('layouts.staff')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-extrabold text-gray-800 border-b pb-2 border-indigo-200">Staff Dashboard</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 border-l-4 border-indigo-500">
        <div class="flex items-center">
            <div class="p-3 bg-indigo-100 rounded-full">
                <i class="fas fa-users text-indigo-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['managed_ukms'] }}</h3>
                <p class="text-gray-500 text-sm">Managed UKM</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full">
                <i class="fas fa-clipboard-list text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['pending_registrations'] }}</h3>
                <p class="text-gray-500 text-sm">Pending Registrations</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="fas fa-calendar text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_events'] }}</h3>
                <p class="text-gray-500 text-sm">Total Events</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 border-l-4 border-fuchsia-500">
        <div class="flex items-center">
            <div class="p-3 bg-fuchsia-100 rounded-full">
                <i class="fas fa-newspaper text-fuchsia-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_feeds'] }}</h3>
                <p class="text-gray-500 text-sm">Total Feeds</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-xl mb-6">
    <div class="p-5 border-b border-gray-100 bg-indigo-50 rounded-t-lg">
        <h2 class="text-xl font-semibold text-gray-800">My Managed UKM <i class="fas fa-users ml-2 text-indigo-600"></i></h2>
    </div>
    <div class="p-5">
        @if($managedUkms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($managedUkms as $ukm)
                <div class="border border-gray-200 rounded-xl p-5 hover:shadow-lg transition duration-300 bg-white">
                    <div class="flex items-start mb-3">
                        @if($ukm->logo)
                            <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" class="w-10 h-10 rounded-full object-cover mr-3 border-2 border-indigo-400">
                        @else
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3 border-2 border-indigo-400">
                                <i class="fas fa-users text-indigo-500"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">{{ $ukm->name }}</h3>
                            <p class="text-xs text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full inline-block mt-1">{{ $ukm->category->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('staff.ukms.edit', $ukm->id) }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 mt-2">
                        Manage UKM <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 bg-gray-50 rounded-lg">
                <p class="text-gray-500 italic">You are not assigned to any UKM yet.</p>
                <i class="fas fa-info-circle text-gray-400 mt-2"></i>
            </div>
        @endif
    </div>
</div>

<div class="bg-white rounded-lg shadow-xl">
    <div class="p-5 border-b border-gray-100 bg-indigo-50 rounded-t-lg">
        <h2 class="text-xl font-semibold text-gray-800">Recent Registrations <i class="fas fa-history ml-2 text-indigo-600"></i></h2>
        <a href="{{ route('staff.registrations.index') }}" class="text-sm text-indigo-500 hover:text-indigo-700 float-right -mt-6">View All →</a>
    </div>
    <div class="p-5">
        @if($recentRegistrations->count() > 0)
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
        @else
            <div class="text-center py-4 bg-gray-50 rounded-lg">
                <p class="text-gray-500 italic">No registrations yet.</p>
                <i class="fas fa-clipboard-list text-gray-400 mt-2"></i>
            </div>
        @endif
    </div>
</div>
@endsection