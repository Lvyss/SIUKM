@extends('layouts.staff')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Manage Registrations</h1>
</div>

<!-- Registrations Table -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">All Registrations</h2>
    </div>
    <div class="p-4">
        @if($registrations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">User</th>
                            <th class="p-2 text-left">UKM</th>
                            <th class="p-2 text-left">Motivation</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-left">Date</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $registration)
                        <tr class="border-b">
                            <td class="p-2">
                                <div class="font-semibold">{{ $registration->user->name }}</div>
                                <div class="text-sm text-gray-600">{{ $registration->user->email }}</div>
                                <div class="text-sm text-gray-500">{{ $registration->user->nim }}</div>
                            </td>
                            <td class="p-2">{{ $registration->ukm->name }}</td>
                            <td class="p-2">
                                <div class="text-sm text-gray-600">{{ Str::limit($registration->motivation, 50) }}</div>
                            </td>
                            <td class="p-2">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ $registration->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $registration->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $registration->status }}
                                </span>
                            </td>
                            <td class="p-2">{{ $registration->created_at->format('d M Y') }}</td>
                            <td class="p-2">
                                @if($registration->status == 'pending')
                                    <form action="{{ route('staff.registrations.approve', $registration->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 mr-1"
                                                onclick="return confirm('Approve this registration?')">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('staff.registrations.reject', $registration->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                                                onclick="return confirm('Reject this registration?')">
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500 text-sm">Processed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-clipboard-list text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-600">No Registrations Yet</h3>
                <p class="text-gray-500">No one has registered to your UKM yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection