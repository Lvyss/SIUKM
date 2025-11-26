@extends('layouts.staff')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Edit Event - {{ $event->title }}</h1>
</div>

<!-- Edit Event Form -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">Event Information</h2>
    </div>
    <div class="p-6">
        <form action="{{ route('staff.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Current Poster</label>
                @if($event->poster_image)
                    <img src="{{ $event->poster_image }}" alt="{{ $event->title }}" class="w-32 h-32 rounded object-cover mb-2">
                @else
                    <div class="w-32 h-32 bg-gray-200 rounded flex items-center justify-center mb-2">
                        <i class="fas fa-calendar text-gray-400 text-3xl"></i>
                    </div>
                @endif
                <input type="file" name="poster_image" accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">Upload new poster to replace current one</p>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">UKM</label>
                    <select name="ukm_id" required class="w-full border rounded px-3 py-2">
                        @foreach($managedUkms as $ukm)
                            <option value="{{ $ukm->id }}" {{ $event->ukm_id == $ukm->id ? 'selected' : '' }}>{{ $ukm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Event Title</label>
                    <input type="text" name="title" required class="w-full border rounded px-3 py-2" value="{{ $event->title }}">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" required class="w-full border rounded px-3 py-2" rows="3">{{ $event->description }}</textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Event Date</label>
                    <input type="date" name="event_date" required class="w-full border rounded px-3 py-2" value="{{ $event->event_date->format('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Event Time</label>
                    <input type="time" name="event_time" required class="w-full border rounded px-3 py-2" value="{{ $event->event_time }}">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Location</label>
                <input type="text" name="location" required class="w-full border rounded px-3 py-2" value="{{ $event->location }}">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Registration Link</label>
                <input type="url" name="registration_link" class="w-full border rounded px-3 py-2" value="{{ $event->registration_link }}" placeholder="https://...">
            </div>
            
            <div class="flex justify-end space-x-2">
                <a href="{{ route('staff.events.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update Event</button>
            </div>
        </form>
    </div>
</div>
@endsection