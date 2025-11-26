    @extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Manage Categories</h1>
    <button onclick="document.getElementById('addCategoryModal').showModal()" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Add Category
    </button>
</div>

<!-- Categories Table -->
<div class="bg-white rounded shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">All Categories</h2>
    </div>
    <div class="p-4">
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Description</th>
                            <th class="p-2 text-left">UKM Count</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr class="border-b">
                            <td class="p-2 font-semibold">{{ $category->name }}</td>
                            <td class="p-2">{{ $category->description ?? '-' }}</td>
                            <td class="p-2">{{ $category->ukms_count ?? 0 }}</td>
                            <td class="p-2">
                                <button onclick="editCategory({{ $category }})" 
                                        class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                                            onclick="return confirm('Delete this category?')">
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
            <p class="text-gray-600">No categories yet.</p>
        @endif
    </div>
</div>

<!-- Add Category Modal -->
<dialog id="addCategoryModal" class="bg-white rounded shadow-lg p-6 w-full max-w-md">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Add New Category</h3>
        <button onclick="document.getElementById('addCategoryModal').close()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Category Name</label>
            <input type="text" name="name" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
        </div>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addCategoryModal').close()" 
                    class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</dialog>

<!-- Edit Category Modal -->
<dialog id="editCategoryModal" class="bg-white rounded shadow-lg p-6 w-full max-w-md">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Edit Category</h3>
        <button onclick="document.getElementById('editCategoryModal').close()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <form id="editCategoryForm" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Category Name</label>
            <input type="text" name="name" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
        </div>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('editCategoryModal').close()" 
                    class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</dialog>

<script>
function editCategory(category) {
    document.getElementById('editCategoryForm').action = `/admin/categories/${category.id}`;
    document.getElementById('editCategoryForm').querySelector('input[name="name"]').value = category.name;
    document.getElementById('editCategoryForm').querySelector('textarea[name="description"]').value = category.description || '';
    document.getElementById('editCategoryModal').showModal();
}
</script>
@endsection