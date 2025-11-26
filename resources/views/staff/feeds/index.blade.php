@extends('layouts.staff')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Manage Feeds</h1>
    <a href="{{ route('staff.feeds.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        <i class="fas fa-plus mr-2"></i>Add Feed
    </a>
</div>

<!-- Feeds Table -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">All Feeds</h2>
    </div>
    <div class="p-4">
        @if($feeds->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">Image</th>
                            <th class="p-2 text-left">Feed</th>
                            <th class="p-2 text-left">UKM</th>
                            <th class="p-2 text-left">Content</th>
                            <th class="p-2 text-left">Date</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feeds as $feed)
                        <tr class="border-b">
                            <td class="p-2">
                                @if($feed->image)
                                    <img src="{{ $feed->image }}" alt="{{ $feed->title }}" class="w-12 h-12 rounded object-cover">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-newspaper text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="p-2">
                                <div class="font-semibold">{{ $feed->title }}</div>
                            </td>
                            <td class="p-2">{{ $feed->ukm->name }}</td>
                            <td class="p-2">
                                <div class="text-sm text-gray-600">{{ Str::limit($feed->content, 50) }}</div>
                            </td>
                            <td class="p-2">{{ $feed->created_at->format('d M Y') }}</td>
                            <td class="p-2">
                                <a href="{{ route('staff.feeds.edit', $feed->id) }}" 
                                   class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 mr-2 inline-block">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('staff.feeds.destroy', $feed->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                                            onclick="return confirm('Delete this feed?')">
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
                <i class="fas fa-newspaper text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-600">No Feeds Yet</h3>
                <p class="text-gray-500">Create your first feed to share updates.</p>
                <a href="{{ route('staff.feeds.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 inline-block mt-4">
                    <i class="fas fa-plus mr-2"></i>Create Feed
                </a>
            </div>
        @endif
    </div>
</div>
@endsection