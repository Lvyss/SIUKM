@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard Admin</h1>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold">{{ $stats['total_users'] }}</h3>
                <p class="text-gray-600">Total Users</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded">
                <i class="fas fa-user-tie text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold">{{ $stats['total_staff'] }}</h3>
                <p class="text-gray-600">Total Staff</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded">
                <i class="fas fa-clipboard-list text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold">{{ $stats['pending_registrations'] }}</h3>
                <p class="text-gray-600">Pending Registrations</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Registrations -->
<div class="bg-white rounded shadow mb-6">
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

<!-- Recent Users -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">Recent Users</h2>
    </div>
    <div class="p-4">
        @if($recentUsers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Email</th>
                            <th class="p-2 text-left">Role</th>
                            <th class="p-2 text-left">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr class="border-b">
                            <td class="p-2">{{ $user->name }}</td>
                            <td class="p-2">{{ $user->email }}</td>
                            <td class="p-2">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $user->role == 'staff' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $user->role == 'user' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-2">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">No users yet.</p>
        @endif
    </div>
</div>
@endsection