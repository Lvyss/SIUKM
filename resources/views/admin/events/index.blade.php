    @extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Manage Events</h1>
    <button onclick="document.getElementById('addEventModal').showModal()" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Add Event
    </button>
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
                                <button onclick="editEvent({{ $event }})" 
                                        class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline">
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
            <p class="text-gray-600">No events yet.</p>
        @endif
    </div>
</div>

<!-- Add Event Modal -->
<dialog id="addEventModal" class="bg-white rounded shadow-lg p-6 w-full max-w-2xl">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Add New Event</h3>
        <button onclick="document.getElementById('addEventModal').close()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">UKM</label>
                <select name="ukm_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Select UKM</option>
                    @foreach($ukms as $ukm)
                        <option value="{{ $ukm->id }}">{{ $ukm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Event Title</label>
                <input type="text" name="title" required class="w-full border rounded px-3 py-2">
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" required class="w-full border rounded px-3 py-2" rows="3"></textarea>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Event Date</label>
                <input type="date" name="event_date" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Event Time</label>
                <input type="time" name="event_time" required class="w-full border rounded px-3 py-2">
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Location</label>
                <input type="text" name="location" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Poster Image</label>
                <input type="file" name="poster_image" accept="image/*" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Registration Link</label>
            <input type="url" name="registration_link" class="w-full border rounded px-3 py-2" placeholder="https://...">
        </div>
        
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addEventModal').close()" 
                    class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</dialog>

<!-- Edit Event Modal -->
<dialog id="editEventModal" class="bg-white rounded shadow-lg p-6 w-full max-w-2xl">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Edit Event</h3>
        <button onclick="document.getElementById('editEventModal').close()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <form id="editEventForm" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">UKM</label>
                <select name="ukm_id" required class="w-full border rounded px-3 py-2">
                    @foreach($ukms as $ukm)
                        <option value="{{ $ukm->id }}">{{ $ukm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Event Title</label>
                <input type="text" name="title" required class="w-full border rounded px-3 py-2">
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" required class="w-full border rounded px-3 py-2" rows="3"></textarea>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Event Date</label>
                <input type="date" name="event_date" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Event Time</label>
                <input type="time" name="event_time" required class="w-full border rounded px-3 py-2">
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Location</label>
                <input type="text" name="location" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Poster Image</label>
                <input type="file" name="poster_image" accept="image/*" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Registration Link</label>
            <input type="url" name="registration_link" class="w-full border rounded px-3 py-2" placeholder="https://...">
        </div>
        
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('editEventModal').close()" 
                    class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</dialog>

<script>
function editEvent(event) {
    const form = document.getElementById('editEventForm');
    form.action = `/admin/events/${event.id}`;
    form.querySelector('select[name="ukm_id"]').value = event.ukm_id;
    form.querySelector('input[name="title"]').value = event.title;
    form.querySelector('textarea[name="description"]').value = event.description;
    form.querySelector('input[name="event_date"]').value = event.event_date;
    form.querySelector('input[name="event_time"]').value = event.event_time;
    form.querySelector('input[name="location"]').value = event.location;
    form.querySelector('input[name="registration_link"]').value = event.registration_link || '';
    document.getElementById('editEventModal').showModal();
}
</script>
@endsection