@extends('layouts.admin')

@section('content')
<style>
    /* CUSTOM STYLES DARI CATEGORY MANAGEMENT */
    .lux-bg { background-color: #f3f4f6; }
    .lux-gold-text { color: #b45309; } 

    /* Soft Shadow Card (SEKARANG MENGGUNAKAN NAMA CLASS 'floating-card' YANG SAMA) */
    .floating-card {
        background-color: white;
        border-radius: 12px;
        /* Shadow yang lebih kuat seperti di Category Management */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .floating-card:hover {
        /* Hover agar terasa 'hidup' seperti di Category Management */
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }
    
    /* Tombol Utama Lux (Gold/Amber) */
    .lux-button {
        background-color: #b45309;
        color: white;
        transition: background-color 0.2s;
    }
    .lux-button:hover {
        background-color: #92400e;
    }
    
    /* Fokus Input Lux (Gold/Amber Ring) */
    .input-lux:focus {
        border-color: #b45309; /* lux-gold */
        box-shadow: 0 0 0 2px rgba(180, 83, 9, 0.4);
    }

    /* Modal Animation */
    dialog.modal-lux::backdrop {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(2px);
    }
    dialog.modal-lux {
        animation: fadeIn 0.3s ease-out;
        border: none;
        padding: 0;
        border-radius: 12px;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    /* Hover Row yang lebih jelas (DISELARASKAN dengan hover-row-table) */
    .hover-row-table:hover {
        background-color: #fffaf0; /* Amber sangat muda */
    }
</style>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">User Matrix & Management</h1>
    <div class="text-sm font-semibold lux-gold-text px-3 py-1 rounded-full border border-gray-300 bg-amber-50">
        {{-- Ikon juga diselaraskan dengan warna Gold --}}
        <i class="fas fa-users-cog mr-1 lux-gold-text"></i> Total: **{{ $users->total() }} users**
    </div>
</div>
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100">
                <i class="fas fa-users text-blue-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-red-100">
                <i class="fas fa-crown text-red-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Admins</p>
                <p class="text-xl font-bold text-gray-900">{{ $adminUsers }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-purple-100">
                <i class="fas fa-user-tie text-purple-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Staff</p>
                <p class="text-xl font-bold text-gray-900">{{ $staffUsers }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-gray-100">
                <i class="fas fa-user text-gray-600 text-sm"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-600">Regular Users</p>
                <p class="text-xl font-bold text-gray-900">{{ $regularUsers }}</p>
            </div>
        </div>
    </div>
</div>
{{-- Mengubah soft-card menjadi floating-card --}}
<div class="floating-card mb-6">
    <div class="p-5 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            {{-- Mengubah ikon filter dari biru menjadi Gold --}}
            <i class="fas fa-filter mr-2 lux-gold-text"></i> Search & Filters
        </h2>
    </div>
    <div class="p-5">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Search User</label>
                {{-- Menerapkan input-lux:focus --}}
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Name, email, or NIM..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus transition duration-150">
            </div>
            
            <div>
                <label for="role" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Role</label>
                {{-- Menerapkan input-lux:focus --}}
                <select name="role" id="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus transition duration-150">
                    <option value="">All Roles</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            
            <div>
                <label for="sort" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Sort By</label>
                {{-- Menerapkan input-lux:focus --}}
                <select name="sort" id="sort" class="w-full border border-gray-300 rounded-lg px-4 py-2 input-lux:focus transition duration-150">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                </select>
            </div>
            
            <div class="flex space-x-3">
                {{-- Mengubah tombol Search dari bg-blue-600 menjadi lux-button (Gold) --}}
                <button type="submit" 
                            class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 flex items-center font-semibold text-sm">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200 flex items-center font-semibold text-sm">
                    <i class="fas fa-refresh mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Mengubah soft-card menjadi floating-card --}}
<div class="floating-card overflow-hidden">
    <div class="flex justify-between items-center p-5 border-b bg-gray-50/50">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            {{-- Ikon diselaraskan dengan warna Gold --}}
            <i class="fas fa-database mr-2 lux-gold-text"></i>
            @if(request('role'))
                {{ ucfirst(request('role')) }} User List
            @else
                All User List
            @endif
        </h2>
        
        <div class="flex items-center space-x-2">
            <span class="text-xs font-medium text-gray-600 uppercase">Show:</span>
            {{-- Menerapkan input-lux:focus --}}
            <select onchange="window.location.href = this.value" 
                    class="border border-gray-300 rounded-lg px-3 py-1 text-sm input-lux:focus">
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}" 
                        {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" 
                        {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" 
                        {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" 
                        {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        @if($users->count() > 0)
        <table class="w-full min-w-full">
            <thead>
                <tr class="bg-gray-100/70 border-b border-gray-200">
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">User Details</th>
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Contact</th>
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Role</th>
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Registrations</th>
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Joined</th>
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                {{-- Warna baris Anda disesuaikan dengan tema lux --}}
                <tr class="hover-row-table transition duration-150 
                    {{ $user->id === auth()->id() ? 'bg-amber-50/70' : '' }}"> 
                    <td class="p-4">
                        <div class="flex items-center">
                            {{-- Warna avatar diselaraskan dengan tema lux --}}
                            <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3 flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 flex items-center">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="ml-2 bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-bold">YOU</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">NIM: **{{ $user->nim ?? '-' }}**</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-sm">
                        <div class="text-gray-800 font-medium">{{ $user->email }}</div>
                        <div class="text-xs text-gray-500">{{ $user->phone ?? 'No phone' }}</div>
                    </td>
                    <td class="p-4">
                        <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="inline">
                            @csrf @method('PUT')
                            {{-- Fokus input peran diselaraskan dengan tema lux --}}
                            <select name="role" onchange="this.form.submit()" 
                                    class="text-xs border rounded-lg px-2 py-1 font-bold input-lux:focus appearance-none transition duration-150
                                        {{ $user->role == 'admin' ? 'bg-red-500 text-white border-red-500' : '' }}
                                        {{ $user->role == 'staff' ? 'bg-blue-600 text-white border-blue-600' : '' }}
                                        {{ $user->role == 'user' ? 'bg-gray-200 text-gray-800 border-gray-300' : '' }}
                                        {{ $user->id === auth()->id() ? 'cursor-not-allowed opacity-75' : '' }}"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @if($user->id === auth()->id())
                                <p class="text-xs text-red-500 mt-1 font-medium">Self role protected</p>
                            @endif
                        </form>
                    </td>
                    <td class="p-4">
                        {{-- Badge registrasi diselaraskan dengan tema lux (Amber/Green) --}}
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
                            {{ $user->registrations_count > 0 ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $user->registrations_count }} reg
                        </span>
                    </td>
                    <td class="p-4 text-sm text-gray-500">
                        {{ $user->created_at->format('d M Y') }}
                        <div class="text-xs text-gray-400 mt-0.5">{{ $user->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="p-4">
                        <div class="flex space-x-2 items-center">
                            {{-- Mengubah tombol View dari biru menjadi Gold --}}
                            <button onclick="viewUserDetails({{ json_encode($user->load('registrations')) }})" 
                                    class="lux-button px-3 py-1.5 rounded-lg text-sm hover:bg-amber-700 transition duration-200 flex items-center font-medium shadow-md shadow-amber-500/20">
                                <i class="fas fa-eye mr-1"></i> View
                            </button>
                            
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                    onsubmit="return confirmDeleteUser('{{ $user->name }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center font-medium shadow-md shadow-red-500/20">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            @else
                                <button disabled
                                        class="bg-gray-300 text-gray-500 px-3 py-1.5 rounded-lg text-sm cursor-not-allowed flex items-center font-medium"
                                        title="Cannot delete your own account">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    
    @if($users->count() == 0)
    <div class="text-center py-12">
        <i class="fas fa-user-slash text-5xl text-gray-300 mb-4"></i>
        <p class="text-gray-600 text-lg mb-2 font-semibold">No users match the criteria</p>
        <p class="text-gray-500 text-sm">
            @if(request()->hasAny(['search', 'role']))
                Try adjusting your search or filters to see more results.
            @else
                No users have registered to the system yet.
            @endif
        </p>
        {{-- Mengubah tombol reset dari biru menjadi Gold --}}
        <a href="{{ route('admin.users.index') }}" class="mt-4 inline-flex items-center text-sm font-semibold lux-gold-text hover:text-amber-700 transition duration-150">
            <i class="fas fa-refresh mr-1"></i> Reset Filters
        </a>
    </div>
    @endif
    
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 rounded-b-lg">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div class="text-sm text-gray-700 font-medium">
                Showing **{{ $users->firstItem() }}** to **{{ $users->lastItem() }}** of **{{ $users->total() }}** results
            </div>
            <div class="flex space-x-1.5">
                {{-- Pagination diselaraskan dengan warna Gold --}}
                @if($users->onFirstPage())
                    <span class="px-3 py-1 rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed text-sm">
                        <i class="fas fa-angle-left"></i> Previous
                    </span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">
                        <i class="fas fa-angle-left"></i> Previous
                    </a>
                @endif

                @php
                    $currentPage = $users->currentPage();
                    $lastPage = $users->lastPage();
                    $start = max(1, $currentPage - 1);
                    $end = min($lastPage, $currentPage + 1);
                @endphp

                @if ($start > 1)
                    <a href="{{ $users->url(1) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">1</a>
                    @if ($start > 2)
                        <span class="px-3 py-1 text-gray-500">...</span>
                    @endif
                @endif
                
                @for ($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        {{-- Warna aktif pagination menjadi Gold --}}
                        <span class="px-3 py-1 rounded-lg border bg-amber-600 text-white text-sm font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $users->url($page) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="px-3 py-1 text-gray-500">...</span>
                    @endif
                    <a href="{{ $users->url($lastPage) }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">{{ $lastPage }}</a>
                @endif

                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200 text-sm font-medium">
                        Next <i class="fas fa-angle-right"></i>
                    </a>
                @else
                    <span class="px-3 py-1 rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed text-sm">
                        Next <i class="fas fa-angle-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Modal juga diselaraskan dengan class modal-lux dan ikon Gold --}}
<dialog id="userDetailsModal" class="modal-lux bg-white w-full max-w-md">
    <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-user-circle mr-2 lux-gold-text"></i> User Profile
        </h3>
        <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-700 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <div class="p-6 space-y-5">
        <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-amber-500 to-red-600 rounded-full flex items-center justify-center text-white font-extrabold text-2xl mx-auto mb-3 shadow-lg">
                <span id="userAvatar">AB</span>
            </div>
            <h4 id="userName" class="text-2xl font-extrabold text-gray-900"></h4>
            <p id="userRole" class="text-sm font-bold mt-1 inline-block px-3 py-0.5 rounded-full"></p>
        </div>
        
        <div class="space-y-3 p-4 border rounded-lg bg-gray-50">
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600 flex items-center"><i class="fas fa-envelope mr-2 text-gray-400"></i> Email:</span>
                <span id="userEmail" class="text-gray-900 font-medium"></span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600 flex items-center"><i class="fas fa-id-card mr-2 text-gray-400"></i> NIM:</span>
                <span id="userNim" class="text-gray-900 font-medium"></span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600 flex items-center"><i class="fas fa-phone mr-2 text-gray-400"></i> Phone:</span>
                <span id="userPhone" class="text-gray-900 font-medium"></span>
            </div>
            <div class="flex justify-between pb-1">
                <span class="font-semibold text-gray-600 flex items-center"><i class="fas fa-tasks mr-2 text-gray-400"></i> Registrations:</span>
                <span id="userRegistrations" class="text-gray-900 font-medium"></span>
            </div>
        </div>
        
        {{-- Mengubah warna kotak info Join Date dari biru menjadi Gold --}}
        <div class="flex justify-between p-2 rounded-lg bg-amber-50">
            <span class="font-semibold text-amber-700 flex items-center"><i class="fas fa-calendar-alt mr-2 lux-gold-text"></i> Joined Date:</span>
            <span id="userJoined" class="text-amber-900 font-bold"></span>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 p-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
        <button onclick="closeUserModal()" 
                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium text-sm">
            Close
        </button>
    </div>
</dialog>

<script>
// Fungsi JavaScript Anda tidak berubah, hanya badge role yang disesuaikan
function getRoleBadge(role) {
    if (role === 'admin') return 'bg-red-500 text-white';
    if (role === 'staff') return 'bg-blue-600 text-white';
    return 'bg-gray-400 text-gray-800';
}

function viewUserDetails(user) {
    const roleBadge = getRoleBadge(user.role);
    
    document.getElementById('userAvatar').textContent = user.name.substring(0, 2).toUpperCase();
    document.getElementById('userName').textContent = user.name;
    
    const roleElement = document.getElementById('userRole');
    roleElement.textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);
    roleElement.className = `text-sm font-bold mt-1 inline-block px-3 py-0.5 rounded-full capitalize ${roleBadge}`;
    
    document.getElementById('userEmail').textContent = user.email;
    document.getElementById('userNim').textContent = user.nim || '-';
    document.getElementById('userPhone').textContent = user.phone || '-';

    const registrationCount = user.registrations ? user.registrations.length : (user.registrations_count || 0);
    document.getElementById('userRegistrations').textContent = registrationCount + ' registrations';
    
    document.getElementById('userJoined').textContent = new Date(user.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    document.getElementById('userDetailsModal').showModal();
}

function closeUserModal() {
    document.getElementById('userDetailsModal').close();
}

function confirmDeleteUser(userName) {
    return confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone and all their data will be lost.`);
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('userDetailsModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeUserModal();
            }
        });
    }
});
</script>
@endsection