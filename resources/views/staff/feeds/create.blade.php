@extends('layouts.staff')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Create New Feed</h1>
</div>

<!-- Create Feed Form -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">Feed Information</h2>
    </div>
    <div class="p-6">
        <form action="{{ route('staff.feeds.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label class="block text-sm font-medium mb-1">Feed Title</label>
                    <input type="text" name="title" required class="w-full border rounded px-3 py-2" value="{{ old('title') }}">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Content</label>
                <textarea name="content" required class="w-full border rounded px-3 py-2" rows="4" placeholder="Write your feed content here...">{{ old('content') }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Image</label>
                <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">Optional: Add an image to your feed</p>
            </div>
            
            <div class="flex justify-end space-x-2">
                <a href="{{ route('staff.feeds.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create Feed</button>
            </div>
        </form>
    </div>
</div>
@endsection