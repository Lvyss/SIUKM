@extends('layouts.staff')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Create New Event</h1>
</div>

<!-- Create Event Form -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">Event Information</h2>
    </div>
    <div class="p-6">
        <form action="{{ route('staff.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">UKM</label>
                    <select name="ukm_id" required class="w-full border rounded px-3 py-2">
                        <option value="">Select UKM</option>
                        @foreach($managedUkms as $ukm)
                            <option value="{{ $ukm->id }}">{{ $ukm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Event Title</label>
                    <input type="text" name="title" required class="w-full border rounded px-3 py-2" value="{{ old('title') }}">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" required class="w-full border rounded px-3 py-2" rows="3">{{ old('description') }}</textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Event Date</label>
                    <input type="date" name="event_date" required class="w-full border rounded px-3 py-2" value="{{ old('event_date') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Event Time</label>
                    <input type="time" name="event_time" required class="w-full border rounded px-3 py-2" value="{{ old('event_time') }}">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Location</label>
                    <input type="text" name="location" required class="w-full border rounded px-3 py-2" value="{{ old('location') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Poster Image</label>
                    <input type="file" name="poster_image" accept="image/*" class="w-full border rounded px-3 py-2">
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Registration Link</label>
                <input type="url" name="registration_link" class="w-full border rounded px-3 py-2" value="{{ old('registration_link') }}" placeholder="https://...">
            </div>
            
            <div class="flex justify-end space-x-2">
                <a href="{{ route('staff.events.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create Event</button>
            </div>
        </form>
    </div>
</div>
@endsection