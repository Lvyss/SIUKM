@extends('layouts.staff')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Manage Events</h1>
    <a href="{{ route('staff.events.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        <i class="fas fa-plus mr-2"></i>Add Event
    </a>
</div>

<!-- Events Table -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">All Events</h2>
    </div>
    <div class="p-4">
        @if($events->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">Poster</th>
                            <th class="p-2 text-left">Event</th>
                            <th class="p-2 text-left">UKM</th>
                            <th class="p-2 text-left">Date & Time</th>
                            <th class="p-2 text-left">Location</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr class="border-b">
                            <td class="p-2">
                                @if($event->poster_image)
                                    <img src="{{ $event->poster_image }}" alt="{{ $event->title }}" class="w-12 h-12 rounded object-cover">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="p-2">
                                <div class="font-semibold">{{ $event->title }}</div>
                                <div class="text-sm text-gray-600">{{ Str::limit($event->description, 50) }}</div>
                            </td>
                            <td class="p-2">{{ $event->ukm->name }}</td>
                            <td class="p-2">
                                <div>{{ $event->event_date->format('d M Y') }}</div>
                                <div class="text-sm text-gray-600">{{ $event->event_time }}</div>
                            </td>
                            <td class="p-2">{{ $event->location }}</td>
                            <td class="p-2">
                                <a href="{{ route('staff.events.edit', $event->id) }}" 
                                   class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 mr-2 inline-block">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('staff.events.destroy', $event->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                                            onclick="return confirm('Delete this event?')">
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
            <div class="text-center py-8">
                <i class="fas fa-calendar text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-600">No Events Yet</h3>
                <p class="text-gray-500">Create your first event to get started.</p>
                <a href="{{ route('staff.events.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 inline-block mt-4">
                    <i class="fas fa-plus mr-2"></i>Create Event
                </a>
            </div>
        @endif
    </div>
</div>
@endsection