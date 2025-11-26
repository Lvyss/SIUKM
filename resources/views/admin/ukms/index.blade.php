@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Manage UKM</h1>
    <button onclick="document.getElementById('addUkmModal').showModal()" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Add UKM
    </button>
</div>

<!-- UKM Table -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">All UKM</h2>
    </div>
    <div class="p-4">
        @if($ukms->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">Logo</th>
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Category</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ukms as $ukm)
                        <tr class="border-b">
                            <td class="p-2">
                                @if($ukm->logo)
                                    <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" class="w-10 h-10 rounded object-cover">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-users text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="p-2">
                                <div class="font-semibold">{{ $ukm->name }}</div>
                                <div class="text-sm text-gray-600">{{ Str::limit($ukm->description, 50) }}</div>
                            </td>
                            <td class="p-2">{{ $ukm->category->name }}</td>
                            <td class="p-2">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ $ukm->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $ukm->status }}
                                </span>
                            </td>
<td class="p-2">
    <div class="flex items-center space-x-2">
        <button onclick="editUkm({{ $ukm }})" 
                class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 flex items-center">
            <i class="fas fa-edit"></i>
        </button>
        <form action="{{ route('admin.ukms.destroy', $ukm->id) }}" method="POST" class="m-0">
            @csrf @method('DELETE')
            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 flex items-center"
                    onclick="return confirm('Delete this UKM?')">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">No UKM yet.</p>
        @endif
    </div>
</div>

<!-- Add UKM Modal -->
<dialog id="addUkmModal" class="bg-white rounded shadow-lg p-6 w-full max-w-2xl">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Add New UKM</h3>
        <button onclick="document.getElementById('addUkmModal').close()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <form action="{{ route('admin.ukms.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">UKM Name</label>
                <input type="text" name="name" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Category</label>
                <select name="category_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" required class="w-full border rounded px-3 py-2" rows="3"></textarea>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Vision</label>
                <textarea name="vision" class="w-full border rounded px-3 py-2" rows="2"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Mission</label>
                <textarea name="mission" class="w-full border rounded px-3 py-2" rows="2"></textarea>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Contact Person</label>
                <input type="text" name="contact_person" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Logo</label>
                <input type="file" name="logo" accept="image/*" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addUkmModal').close()" 
                    class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</dialog>

<!-- Edit UKM Modal -->
<dialog id="editUkmModal" class="bg-white rounded shadow-lg p-6 w-full max-w-2xl">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Edit UKM</h3>
        <button onclick="document.getElementById('editUkmModal').close()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <form id="editUkmForm" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">UKM Name</label>
                <input type="text" name="name" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Category</label>
                <select name="category_id" required class="w-full border rounded px-3 py-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" required class="w-full border rounded px-3 py-2" rows="3"></textarea>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Vision</label>
                <textarea name="vision" class="w-full border rounded px-3 py-2" rows="2"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Mission</label>
                <textarea name="mission" class="w-full border rounded px-3 py-2" rows="2"></textarea>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Contact Person</label>
                <input type="text" name="contact_person" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Logo</label>
            <input type="file" name="logo" accept="image/*" class="w-full border rounded px-3 py-2">
        </div>
        
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('editUkmModal').close()" 
                    class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</dialog>

<script>
function editUkm(ukm) {
    const form = document.getElementById('editUkmForm');
    form.action = `/admin/ukms/${ukm.id}`;
    form.querySelector('input[name="name"]').value = ukm.name;
    form.querySelector('select[name="category_id"]').value = ukm.category_id;
    form.querySelector('textarea[name="description"]').value = ukm.description;
    form.querySelector('textarea[name="vision"]').value = ukm.vision || '';
    form.querySelector('textarea[name="mission"]').value = ukm.mission || '';
    form.querySelector('input[name="contact_person"]').value = ukm.contact_person || '';
    form.querySelector('select[name="status"]').value = ukm.status;
    document.getElementById('editUkmModal').showModal();
}

</script>
@endsection