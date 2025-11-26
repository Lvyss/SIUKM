@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Manage Staff</h1>
</div>

<!-- Assign Staff Form -->
<div class="bg-white rounded shadow mb-6">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">Assign Staff to UKM</h2>
    </div>
    <div class="p-4">
        <form action="{{ route('admin.staff.assign') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Staff User</label>
                    <select name="user_id" required class="w-full border rounded px-3 py-2">
                        <option value="">Select Staff</option>
                        @foreach($staffUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">UKM</label>
                    <select name="ukm_id" required class="w-full border rounded px-3 py-2">
                        <option value="">Select UKM</option>
                        @foreach($ukms as $ukm)
                            <option value="{{ $ukm->id }}">{{ $ukm->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Assign Staff
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Staff Assignments Table -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">Staff Assignments</h2>
    </div>
    <div class="p-4">
        @if($ukmStaff->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">Staff Name</th>
                            <th class="p-2 text-left">Email</th>
                            <th class="p-2 text-left">UKM</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ukmStaff as $assignment)
                        <tr class="border-b">
                            <td class="p-2">{{ $assignment->user->name }}</td>
                            <td class="p-2">{{ $assignment->user->email }}</td>
                            <td class="p-2">{{ $assignment->ukm->name }}</td>
                            <td class="p-2">
                                <form action="{{ route('admin.staff.remove', $assignment->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                                            onclick="return confirm('Remove this staff from UKM?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">No staff assignments yet.</p>
        @endif
    </div>
</div>
@endsection