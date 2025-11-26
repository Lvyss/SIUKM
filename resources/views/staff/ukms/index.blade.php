@extends('layouts.staff')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">My Managed UKM</h1>
</div>

<!-- UKM List -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">UKM List</h2>
    </div>
    <div class="p-4">
        @if($managedUkms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($managedUkms as $ukm)
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="flex items-center mb-4">
                        @if($ukm->logo)
                            <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" class="w-16 h-16 rounded object-cover mr-4">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center mr-4">
                                <i class="fas fa-users text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold">{{ $ukm->name }}</h3>
                            <p class="text-gray-600">{{ $ukm->category->name }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-700 text-sm">{{ Str::limit($ukm->description, 100) }}</p>
                    </div>
                    
                    <div class="space-y-2 text-sm text-gray-600">
                        @if($ukm->contact_person)
                            <div><i class="fas fa-phone mr-2"></i>{{ $ukm->contact_person }}</div>
                        @endif
                        @if($ukm->instagram)
                            <div><i class="fab fa-instagram mr-2"></i>{{ $ukm->instagram }}</div>
                        @endif
                        @if($ukm->email_ukm)
                            <div><i class="fas fa-envelope mr-2"></i>{{ $ukm->email_ukm }}</div>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('staff.ukms.edit', $ukm->id) }}" 
                           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 inline-block">
                            <i class="fas fa-edit mr-2"></i>Manage UKM
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-600">No UKM Assigned</h3>
                <p class="text-gray-500">You are not assigned to manage any UKM yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection