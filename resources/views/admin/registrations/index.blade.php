@extends('layouts.admin')

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
                            </td>
                            <td class="p-2">{{ $registration->ukm->name }}</td>
                            <td class="p-2">
                                <div class="text-sm">{{ Str::limit($registration->motivation, 50) }}</div>
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
                                    <form action="{{ route('admin.registrations.updateStatus', $registration->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 mr-1">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.registrations.updateStatus', $registration->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
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
            <p class="text-gray-600">No registrations yet.</p>
        @endif
    </div>
</div>
@endsection