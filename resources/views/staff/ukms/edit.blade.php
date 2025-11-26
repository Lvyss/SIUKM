@extends('layouts.staff')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Edit UKM - {{ $ukm->name }}</h1>
</div>

<!-- Edit UKM Form -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">UKM Information</h2>
    </div>
    <div class="p-6">
        <form action="{{ route('staff.ukms.update', $ukm->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Current Logo</label>
                @if($ukm->logo)
                    <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" class="w-32 h-32 rounded object-cover mb-2">
                @else
                    <div class="w-32 h-32 bg-gray-200 rounded flex items-center justify-center mb-2">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">Upload new logo to replace current one</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" required class="w-full border rounded px-3 py-2" rows="3">{{ $ukm->description }}</textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Vision</label>
                    <textarea name="vision" class="w-full border rounded px-3 py-2" rows="2">{{ $ukm->vision }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Mission</label>
                    <textarea name="mission" class="w-full border rounded px-3 py-2" rows="2">{{ $ukm->mission }}</textarea>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ $ukm->contact_person }}" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Instagram</label>
                    <input type="text" name="instagram" value="{{ $ukm->instagram }}" class="w-full border rounded px-3 py-2" placeholder="@username">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email UKM</label>
                <input type="email" name="email_ukm" value="{{ $ukm->email_ukm }}" class="w-full border rounded px-3 py-2">
            </div>
            
            <div class="flex justify-end space-x-2">
                <a href="{{ route('staff.ukms.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update UKM</button>
            </div>
        </form>
    </div>
</div>
@endsection