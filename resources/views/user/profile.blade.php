@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>
        <p class="text-gray-600">Kelola informasi profil Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Personal Information</h2>
                
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" 
                                   class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" value="{{ $user->email }}" 
                                   class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
                            <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" 
                                   class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">NIM</label>
                            <input type="text" value="{{ $user->nim }}" 
                                   class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium mb-1">Fakultas</label>
                            <input type="text" name="fakultas" value="{{ $user->fakultas }}" 
                                   class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jurusan</label>
                            <input type="text" name="jurusan" value="{{ $user->jurusan }}" 
                                   class="w-full border rounded px-3 py-2" required>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- UKM Registrations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">My UKM Registrations</h2>
            
            @if($registrations->count() > 0)
                <div class="space-y-3">
                    @foreach($registrations as $registration)
                    <div class="p-3 border rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold">{{ $registration->ukm->name }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $registration->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $registration->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $registration->status }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-2">
                            Applied: {{ $registration->created_at->format('d M Y') }}
                        </p>
                        
                        @if($registration->approved_at)
                        <p class="text-sm text-gray-600">
                            Processed: {{ $registration->approved_at->format('d M Y') }}
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-600">No UKM registrations yet.</p>
                    <a href="{{ route('user.ukm.list') }}" class="text-blue-600 hover:text-blue-700 text-sm mt-2 inline-block">
                        Browse UKM →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection