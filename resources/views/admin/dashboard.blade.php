@extends('layouts.admin')

@section('content')
<style>
    /* Soft Cream Background */
    .lux-bg { background-color: #f3f4f6; } 
    /* Amber-700 Accent Color */
    .lux-gold-text { color: #b45309; } 

    /* Soft Shadow Card (Kontras Jelas tapi Lembut) */
    .soft-card {
        background-color: white; /* Konten wajib putih */
        border-radius: 12px;
        /* Shadow yang lebih tipis dan lembut dari Shadow Box sebelumnya */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb; 
    }
    .soft-card:hover {
        /* Hover agar terasa 'hidup' tanpa terlalu dramatis */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }
    
    /* Hover Row yang lebih jelas */
    .hover-row:hover {
        background-color: #fafafa; /* Sangat lembut */
    }
</style>

<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">System Overview</h1>
    <p class="text-gray-500">Essential metrics for SI-UKM exclusive access.</p>
</div>


<div class="grid grid-cols-3 gap-2 md:gap-6 mb-10">
    <div class="soft-card p-4 md:p-6 border-l-4 border-amber-600">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <p class="text-xs md:text-sm font-bold lux-gold-text uppercase tracking-wider">Total Users</p>
                <p class="text-2xl md:text-4xl font-extrabold text-gray-900 mt-1">{{ $stats['total_users'] }}</p>
            </div>
            <i class="fas fa-users text-amber-500/50 text-2xl md:text-4xl mt-2 md:mt-0"></i>
        </div>
    </div>
    
    <div class="soft-card p-4 md:p-6 border-l-4 border-blue-600">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <p class="text-xs md:text-sm font-bold text-blue-600 uppercase tracking-wider">Total Staff</p>
                <p class="text-2xl md:text-4xl font-extrabold text-gray-900 mt-1">{{ $stats['total_staff'] }}</p>
            </div>
            <i class="fas fa-user-tie text-blue-500/50 text-2xl md:text-4xl mt-2 md:mt-0"></i>
        </div>
    </div>
    
    <div class="soft-card p-4 md:p-6 border-l-4 border-red-600">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <p class="text-xs md:text-sm font-bold text-red-600 uppercase tracking-wider">Pending Audit</p>
                <p class="text-2xl md:text-4xl font-extrabold text-red-600 mt-1">{{ $stats['pending_registrations'] }}</p>
            </div>
            <i class="fas fa-exclamation-circle text-red-500/50 text-2xl md:text-4xl mt-2 md:mt-0"></i>
        </div>
    </div>
</div>

{{-- KOREKSI: Mengubah lg:grid-cols-2 menjadi grid-cols-1 di mobile/tablet, dan lg:grid-cols-2 di desktop --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="soft-card p-6 h-full flex flex-col">
        <div class="pb-4 mb-2 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-scroll mr-2 lux-gold-text"></i> Pendaftaran Terbaru
            </h2>
            <a href="{{ route('admin.registrations.index') }}" class="text-xs lux-gold-text font-bold hover:underline">View All <i class="fas fa-angle-right ml-1"></i></a>
        </div>
        
        <div class="pt-2 flex-grow overflow-x-auto">
            @php
                // Batasi hanya 3 item untuk tampilan compact, agar tidak ada scroll
                $recentRegistrationsLimited = $recentRegistrations->take(3); 
            @endphp
            @if($recentRegistrationsLimited->count() > 0)
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200">
                            <th class="py-2 px-1 text-xs font-bold text-gray-600 uppercase tracking-wider">User ID</th>
                            <th class="py-2 px-1 text-xs font-bold text-gray-600 uppercase tracking-wider">UKM Target</th>
                            <th class="py-2 px-1 text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="py-2 px-1 text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentRegistrationsLimited as $registration)
                        <tr class="hover-row transition duration-150">
                            <td class="py-3 px-1 font-medium text-gray-800">{{ $registration->user->name }}</td>
                            <td class="py-3 px-1 text-gray-600">{{ $registration->ukm->name }}</td>
                            <td class="py-3 px-1">
                                <span class="px-2.5 py-1 text-xs font-bold rounded capitalize
                                    {{ $registration->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $registration->status == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ $registration->status }}
                                </span>
                            </td>
                            <td class="py-3 px-1 text-xs text-gray-500">{{ $registration->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-10 text-gray-500 border border-gray-200 bg-gray-50 rounded-lg">
                    <i class="fas fa-check-double text-5xl mb-3 text-green-500"></i>
                    <p class="text-sm font-medium">Audit complete. No pending items.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="soft-card p-6 h-full flex flex-col">
        <div class="pb-4 mb-2 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-plus mr-2 text-blue-600"></i> New User Log
            </h2>
             <a href="{{ route('admin.users.index') }}" class="text-xs text-blue-600 font-bold hover:underline">View All <i class="fas fa-angle-right ml-1"></i></a>
        </div>
        
        <div class="pt-2 flex-grow overflow-x-auto">
            @php
                // Batasi hanya 3 item untuk tampilan compact, agar tidak ada scroll
                $recentUsersLimited = $recentUsers->take(3); 
            @endphp
            @if($recentUsersLimited->count() > 0)
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200">
                            <th class="py-2 px-1 text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="py-2 px-1 text-xs font-bold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="py-2 px-1 text-xs font-bold text-gray-600 uppercase tracking-wider">Role</th>
                            <th class="py-2 px-1 text-xs font-bold text-gray-600 uppercase tracking-wider">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentUsersLimited as $user)
                        <tr class="hover-row transition duration-150">
                            <td class="py-3 px-1 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="py-3 px-1 text-gray-600">{{ $user->email }}</td>
                            <td class="py-3 px-1">
                                <span class="px-2.5 py-1 text-xs font-bold rounded capitalize
                                    {{ $user->role == 'admin' ? 'bg-red-600 text-white' : '' }}
                                    {{ $user->role == 'staff' ? 'bg-blue-600 text-white' : '' }}
                                    {{ $user->role == 'user' ? 'bg-gray-400 text-gray-800' : '' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-3 px-1 text-xs text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-10 text-gray-500 border border-gray-200 bg-gray-50 rounded-lg">
                    <i class="fas fa-users-cog text-5xl mb-3 text-blue-500"></i>
                    <p class="text-sm font-medium">No new users detected in the matrix.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection