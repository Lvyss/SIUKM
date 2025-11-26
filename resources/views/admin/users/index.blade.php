@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Manage Users</h1>
</div>

<!-- Users Table -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">All Users</h2>
    </div>
    <div class="p-4">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Email</th>
                            <th class="p-2 text-left">NIM</th>
                            <th class="p-2 text-left">Role</th>
                            <th class="p-2 text-left">Registrations</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="border-b">
                            <td class="p-2 font-semibold">{{ $user->name }}</td>
                            <td class="p-2">{{ $user->email }}</td>
                            <td class="p-2">{{ $user->nim }}</td>
                            <td class="p-2">
                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="inline">
                                    @csrf @method('PUT')
                                    <select name="role" onchange="this.form.submit()" 
                                            class="text-xs border rounded px-2 py-1
                                                {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $user->role == 'staff' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $user->role == 'user' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-2">{{ $user->registrations_count }}</td>
                            <td class="p-2">
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                                            onclick="return confirm('Delete this user?')">
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
            <p class="text-gray-600">No users yet.</p>
        @endif
    </div>
</div>
@endsection