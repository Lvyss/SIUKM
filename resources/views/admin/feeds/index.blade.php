@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Manage Feeds</h1>
    <button onclick="document.getElementById('addFeedModal').showModal()" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Add Feed
    </button>
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
                                <button onclick="editFeed({{ $feed }})" 
                                        class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.feeds.destroy', $feed->id) }}" method="POST" class="inline">
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
            <p class="text-gray-600">No feeds yet.</p>
        @endif
    </div>
</div>

<!-- Add Feed Modal -->
<dialog id="addFeedModal" class="bg-white rounded shadow-lg p-6 w-full max-w-2xl">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Add New Feed</h3>
        <button onclick="document.getElementById('addFeedModal').close()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <form action="{{ route('admin.feeds.store') }}" method="POST" enctype="multipart/form-data">
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
                <label class="block text-sm font-medium mb-1">Feed Title</label>
                <input type="text" name="title" required class="w-full border rounded px-3 py-2">
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Content</label>
            <textarea name="content" required class="w-full border rounded px-3 py-2" rows="4" placeholder="Write feed content..."></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Image</label>
            <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
        </div>
        
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addFeedModal').close()" 
                    class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</dialog>

<!-- Edit Feed Modal -->
<dialog id="editFeedModal" class="bg-white rounded shadow-lg p-6 w-full max-w-2xl">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Edit Feed</h3>
        <button onclick="document.getElementById('editFeedModal').close()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <form id="editFeedForm" method="POST" enctype="multipart/form-data">
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
                <label class="block text-sm font-medium mb-1">Feed Title</label>
                <input type="text" name="title" required class="w-full border rounded px-3 py-2">
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Content</label>
            <textarea name="content" required class="w-full border rounded px-3 py-2" rows="4"></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Image</label>
            <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
        </div>
        
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('editFeedModal').close()" 
                    class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</dialog>

<script>
// FIXED VERSION - No CSSStyleSheet access
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilters = document.querySelectorAll('.category-filter');
    const feedCards = document.querySelectorAll('.feed-card');
    const noFeedsEmpty = document.getElementById('no-feeds-empty');
    const noFilteredFeeds = document.getElementById('no-filtered-feeds');
    const feedsGrid = document.getElementById('feeds-grid');

    // Simple hide/show without CSS rules access
    if (feedCards.length > 0 && noFeedsEmpty) {
        noFeedsEmpty.style.display = 'none';
    }

    // Safe filter functionality
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            const selectedUkm = this.getAttribute('data-category');
            
            // Update active state safely
            categoryFilters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
            let visibleCount = 0;
            
            // Simple show/hide without complex CSS
            feedCards.forEach(card => {
                const cardUkm = card.getAttribute('data-ukm');
                const shouldShow = selectedUkm === 'all' || cardUkm === selectedUkm;
                
                if (shouldShow) {
                    card.style.display = 'block';
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                    visibleCount++;
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
            
            // Handle empty states
            if (visibleCount === 0) {
                if (noFeedsEmpty) noFeedsEmpty.style.display = 'none';
                noFilteredFeeds.classList.remove('hidden');
                if (feedsGrid) feedsGrid.style.display = 'none';
            } else {
                if (noFeedsEmpty) noFeedsEmpty.style.display = 'none';
                noFilteredFeeds.classList.add('hidden');
                if (feedsGrid) feedsGrid.style.display = 'grid';
            }
        });
    });
    
    // Safe animation
    feedCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Safe modal function
function showFeedDetails(feed) {
    // Your existing modal code...
    document.getElementById('feedModal').showModal();
}

// Edit Feed Function
function editFeed(feed) {
    console.log('Editing feed:', feed);
    
    // Parse feed data if it's a string
    const feedData = typeof feed === 'string' ? JSON.parse(feed) : feed;
    
    // Set form action URL
    const form = document.getElementById('editFeedForm');
    form.action = `/admin/feeds/${feedData.id}`;
    
    // Fill form values
    form.querySelector('[name="ukm_id"]').value = feedData.ukm_id;
    form.querySelector('[name="title"]').value = feedData.title;
    form.querySelector('[name="content"]').value = feedData.content;
    
    // Show modal
    document.getElementById('editFeedModal').showModal();
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('dialog');
    
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.close();
            }
        });
    });
});
</script>
@endsection