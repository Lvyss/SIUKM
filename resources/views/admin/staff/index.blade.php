@extends('layouts.admin')

@section('content')
<style>
    /* CUSTOM STYLES DARI THEMA LUX */
    .lux-gold-text { color: #b45309; } 
    .lux-gold-bg-light { background-color: #fffbeb; } /* Amber 50 */
    
    /* Soft Shadow Card (floating-card) */
    .floating-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .floating-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }
    
    /* Tombol Utama Lux (Gold/Amber) */
    .lux-button {
        background-color: #b45309; /* Amber-700 */
        color: white;
        transition: background-color 0.2s;
    }
    .lux-button:hover {
        background-color: #92400e; /* Amber-800 */
    }
    
    /* Fokus Input Lux (Gold/Amber Ring) */
    .input-lux:focus {
        border-color: #b45309; /* lux-gold */
        box-shadow: 0 0 0 2px rgba(180, 83, 9, 0.4);
    }
    
    /* Hover Row yang diselaraskan dengan tema lux */
    .hover-row-table:hover {
        background-color: #fffaf0; /* Amber sangat muda */
    }
    
    /* Modal Animation */
    dialog[open] {
        animation: slideIn 0.2s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Staff Assignment Management</h1>
    <div class="text-sm font-semibold lux-gold-text px-3 py-1 rounded-full border border-gray-300 bg-amber-50">
        <i class="fas fa-link mr-1 lux-gold-text"></i> **{{ $ukmStaff->count() }}** Active Assignments
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    
    {{-- Total Staff Users (Warna Gold) --}}
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-amber-100 lux-gold-text mr-4">
                <i class="fas fa-users text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Staff Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ $staffUsers->count() }}</p>
            </div>
        </div>
    </div>
    
    {{-- Active Assignments (Warna Gold) --}}
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-amber-100 lux-gold-text mr-4">
                <i class="fas fa-link text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Active Assignments</p>
                <p class="text-2xl font-bold text-gray-900">{{ $ukmStaff->count() }}</p>
            </div>
        </div>
    </div>
    
    {{-- UKM with Staff (Warna Gold) --}}
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-amber-100 lux-gold-text mr-4">
                <i class="fas fa-building text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">UKM with Staff</p>
                <p class="text-2xl font-bold text-gray-900">{{ $ukmStaff->unique('ukm_id')->count() }}</p>
            </div>
        </div>
    </div>
    
    {{-- Available UKM (Warna Gold) --}}
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-amber-100 lux-gold-text mr-4">
                <i class="fas fa-clipboard-list text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Available UKM</p>
                <p class="text-2xl font-bold text-gray-900">{{ $ukms->count() }}</p>
            </div>
        </div>
    </div>
</div>



<div class="floating-card mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-user-plus mr-2 lux-gold-text"></i>
            Assign Staff to UKM
        </h2>
    </div>
    <div class="p-5">
        <form id="assignStaffForm" action="{{ route('admin.staff.assign') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Staff User *</label>
                    {{-- Menerapkan input-lux:focus --}}
                    <select name="user_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200">
                        <option value="">Select Staff User</option>
                        @foreach($staffUsers as $user)
                            <option value="{{ $user->id }}" 
                                    data-email="{{ $user->email }}"
                                    data-nim="{{ $user->nim }}"
                                    data-assignments="{{ $user->ukmStaff->count() }}">
                                {{ $user->name }} 
                                @if($user->ukmStaff->count() > 0)
                                    ({{ $user->ukmStaff->count() }} assignments)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    {{-- Detail Card diselaraskan ke Gold/Amber --}}
                    <div id="staffDetails" class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg hidden">
                        <div class="text-sm text-gray-700 space-y-1">
                            <div><strong class="lux-gold-text">Email:</strong> <span id="staffEmail">-</span></div>
                            <div><strong class="lux-gold-text">NIM:</strong> <span id="staffNim">-</span></div>
                            <div><strong class="lux-gold-text">Current Assignments:</strong> <span id="staffAssignments">0</span></div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">UKM *</label>
                    {{-- Menerapkan input-lux:focus --}}
                    <select name="ukm_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200">
                        <option value="">Select UKM</option>
                        @foreach($ukms as $ukm)
                            <option value="{{ $ukm->id }}"
                                    data-staff-count="{{ $ukm->staff->count() }}"
                                    data-status="{{ $ukm->status }}">
                                {{ $ukm->name }}
                                @if($ukm->staff->count() > 0)
                                    ({{ $ukm->staff->count() }} staff)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    {{-- Detail Card diselaraskan ke Gold/Amber --}}
                    <div id="ukmDetails" class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg hidden">
                        <div class="text-sm text-gray-700 space-y-1">
                            <div><strong class="lux-gold-text">Current Staff:</strong> <span id="ukmStaffCount">0</span></div>
                            <div><strong class="lux-gold-text">Status:</strong> <span id="ukmStatus" class="px-2 py-1 rounded text-xs"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="resetForm()" 
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium text-sm">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </button>
                {{-- Tombol Submit menggunakan lux-button (Gold) --}}
                <button type="submit" 
                        class="lux-button px-6 py-2.5 rounded-lg hover:bg-amber-700 transition duration-200 font-medium flex items-center text-sm">
                    <i class="fas fa-link mr-2"></i>Assign Staff
                </button>
            </div>
        </form>
    </div>
</div>



<div class="floating-card overflow-hidden">
    <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-list-check mr-2 lux-gold-text"></i>
            Staff Assignments
        </h2>
        
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" id="searchAssignments" placeholder="Search assignments..." 
                       {{-- Menerapkan input-lux:focus --}}
                       class="border border-gray-300 rounded-lg px-4 py-2 pl-10 input-lux:focus w-64 text-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full min-w-full">
            <thead>
                <tr class="bg-gray-100/70 border-b border-gray-200">
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Staff Member</th>
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Contact Info</th>
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">UKM Details</th>
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Assignment Date</th>
                    <th class="p-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="assignmentsTableBody">
                @foreach($ukmStaff as $assignment)
                <tr class="hover-row-table transition duration-150 assignment-row">
                    <td class="p-4">
                        <div class="flex items-center">
                            {{-- Avatar diselaraskan ke Gold/Amber gradient --}}
                            <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3 shadow-md">
                                {{ strtoupper(substr($assignment->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $assignment->user->name }}</div>
                                <div class="text-sm text-gray-500">Staff Member</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-gray-900 font-medium">{{ $assignment->user->email }}</div>
                        <div class="text-xs text-gray-600"><i class="fas fa-id-card mr-1"></i>{{ $assignment->user->nim ?? 'No NIM' }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-phone mr-1"></i>{{ $assignment->user->phone ?? 'No phone' }}
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center">
                            @if($assignment->ukm->logo)
                                <img src="{{ $assignment->ukm->logo }}" alt="{{ $assignment->ukm->name }}" 
                                     class="w-8 h-8 rounded-lg object-cover mr-3 border border-gray-200">
                            @else
                                {{-- Default UKM logo diselaraskan ke Gold/Amber --}}
                                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center mr-3 border border-amber-200">
                                    <i class="fas fa-users lux-gold-text text-sm"></i>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $assignment->ukm->name }}</div>
                                {{-- Badge kategori diselaraskan ke Gold/Amber --}}
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                    {{ $assignment->ukm->category->name }}
                                </span>
                                <div class="flex items-center mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                        {{ $assignment->ukm->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $assignment->ukm->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-gray-900">{{ $assignment->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $assignment->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="p-4">
                        <div class="flex space-x-2">
                            {{-- Tombol View diselaraskan ke Gold/Amber --}}
                            <button onclick="viewAssignmentDetails({{ $assignment }})" 
                                    class="bg-amber-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-amber-600 transition duration-200 flex items-center font-medium shadow-md shadow-amber-500/20">
                                <i class="fas fa-eye mr-1"></i> View
                            </button>
                            <form action="{{ route('admin.staff.remove', $assignment->id) }}" method="POST" 
                                  onsubmit="return confirmRemoveAssignment('{{ $assignment->user->name }}', '{{ $assignment->ukm->name }}')">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center font-medium shadow-md shadow-red-500/20">
                                    <i class="fas fa-unlink mr-1"></i> Remove
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($ukmStaff->count() == 0)
    <div class="text-center py-12">
        <i class="fas fa-user-tie text-5xl text-gray-300 mb-4 lux-gold-text"></i>
        <p class="text-gray-600 text-lg mb-2 font-semibold">No staff assignments yet</p>
        <p class="text-gray-500 text-sm mb-4">Assign staff members to UKM to get started</p>
        {{-- Tombol Assign First Staff menggunakan lux-button (Gold) --}}
        <button onclick="scrollToAssignForm()" 
                class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 font-semibold mx-auto">
            <i class="fas fa-user-plus mr-2"></i>Assign First Staff
        </button>
    </div>
    @endif
</div>

<dialog id="assignmentDetailsModal" class="bg-white rounded-lg shadow-xl w-full max-w-lg backdrop:bg-black/50">
    <div class="flex justify-between items-center p-6 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-info-circle mr-2 lux-gold-text"></i> Assignment Details
        </h3>
        <button onclick="closeAssignmentModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <div class="p-6 space-y-6">
        <div>
            <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide border-b pb-1">Staff Information</h4>
            {{-- Background card diselaraskan ke Gold/Amber --}}
            <div class="flex items-center space-x-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                {{-- Avatar diselaraskan ke Gold/Amber gradient --}}
                <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                    <span id="assignmentStaffAvatar">AB</span>
                </div>
                <div class="flex-1">
                    <h5 id="assignmentStaffName" class="font-semibold text-gray-900 text-lg"></h5>
                    <p id="assignmentStaffEmail" class="text-gray-700 text-sm font-medium"></p>
                    <div class="flex flex-wrap gap-x-4 mt-2 text-xs text-gray-600">
                        <span><i class="fas fa-id-card mr-1 lux-gold-text"></i> NIM: <span id="assignmentStaffNim" class="font-medium"></span></span>
                        <span><i class="fas fa-phone mr-1 lux-gold-text"></i> Phone: <span id="assignmentStaffPhone" class="font-medium"></span></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide border-b pb-1">UKM Information</h4>
            {{-- Background card diselaraskan ke Gold/Amber --}}
            <div class="flex items-center space-x-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                {{-- Default UKM logo diselaraskan ke Gold/Amber --}}
                <div id="assignmentUkmLogo" class="w-16 h-16 bg-amber-100 rounded-lg flex items-center justify-center border border-amber-300">
                    <i class="fas fa-users lux-gold-text text-xl"></i>
                </div>
                <div class="flex-1">
                    <h5 id="assignmentUkmName" class="font-semibold text-gray-900 text-lg"></h5>
                    <p id="assignmentUkmCategory" class="text-gray-700 text-sm font-medium"></p>
                    <div class="flex items-center space-x-4 mt-2">
                        <span id="assignmentUkmStatus" class="px-2 py-1 rounded text-xs font-medium"></span>
                        <span class="text-sm text-gray-600"><i class="fas fa-calendar mr-1 lux-gold-text"></i> Established: <span id="assignmentUkmEstablished" class="font-medium">N/A</span></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide border-b pb-1">Assignment Information</h4>
            <div class="grid grid-cols-2 gap-4 text-sm p-4 bg-gray-50 rounded-lg">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Assigned Date:</span>
                        <span id="assignmentDate" class="font-bold text-gray-900"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Duration:</span>
                        <span id="assignmentDuration" class="font-bold text-gray-900"></span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Assigned By:</span>
                        <span id="assignmentCreator" class="font-bold text-gray-900"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Total UKM Staff:</span>
                        <span id="assignmentTotalStaff" class="font-bold text-gray-900"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-lg">
        <button onclick="closeAssignmentModal()" 
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium text-sm">
            <i class="fas fa-times mr-1"></i> Close
        </button>
    </div>
</dialog>

<script>
// Form interaction
document.addEventListener('DOMContentLoaded', function() {
    const staffSelect = document.querySelector('select[name="user_id"]');
    const ukmSelect = document.querySelector('select[name="ukm_id"]');
    const staffDetails = document.getElementById('staffDetails');
    const ukmDetails = document.getElementById('ukmDetails');
    
    // Staff select change
    if (staffSelect) {
        staffSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value) {
                document.getElementById('staffEmail').textContent = selectedOption.getAttribute('data-email');
                document.getElementById('staffNim').textContent = selectedOption.getAttribute('data-nim') || '-';
                document.getElementById('staffAssignments').textContent = selectedOption.getAttribute('data-assignments');
                staffDetails.classList.remove('hidden');
            } else {
                staffDetails.classList.add('hidden');
            }
        });
    }
    
    // UKM select change
    if (ukmSelect) {
        ukmSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value) {
                const staffCount = selectedOption.getAttribute('data-staff-count');
                const status = selectedOption.getAttribute('data-status');
                
                document.getElementById('ukmStaffCount').textContent = staffCount + ' staff members';
                
                const statusElement = document.getElementById('ukmStatus');
                statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusElement.className = `px-2 py-1 rounded text-xs font-medium ${
                    status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                }`;
                
                ukmDetails.classList.remove('hidden');
            } else {
                ukmDetails.classList.add('hidden');
            }
        });
    }
    
    // Search functionality
    const searchInput = document.getElementById('searchAssignments');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.assignment-row');
            
            let found = 0;
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    found++;
                } else {
                    row.style.display = 'none';
                }
            });
            // You might want to add a function to show/hide the empty state based on `found` count here
        });
    }
});

function resetForm() {
    document.getElementById('assignStaffForm').reset();
    document.getElementById('staffDetails').classList.add('hidden');
    document.getElementById('ukmDetails').classList.add('hidden');
}

function scrollToAssignForm() {
    document.getElementById('assignStaffForm').scrollIntoView({ 
        behavior: 'smooth' 
    });
}

function viewAssignmentDetails(assignment) {
    // Staff information
    document.getElementById('assignmentStaffAvatar').textContent = 
        assignment.user.name.substring(0, 2).toUpperCase();
    document.getElementById('assignmentStaffName').textContent = assignment.user.name;
    document.getElementById('assignmentStaffEmail').textContent = assignment.user.email;
    document.getElementById('assignmentStaffNim').textContent = assignment.user.nim || 'Not provided';
    document.getElementById('assignmentStaffPhone').textContent = assignment.user.phone || 'Not provided';
    
    // UKM information
    document.getElementById('assignmentUkmName').textContent = assignment.ukm.name;
    document.getElementById('assignmentUkmCategory').textContent = assignment.ukm.category.name;
    
    // UKM logo
    const ukmLogoContainer = document.getElementById('assignmentUkmLogo');
    if (assignment.ukm.logo) {
        ukmLogoContainer.innerHTML = `<img src="${assignment.ukm.logo}" alt="${assignment.ukm.name}" class="w-16 h-16 rounded-lg object-cover">`;
    } else {
        // Default UKM logo diselaraskan ke Gold/Amber
        ukmLogoContainer.innerHTML = '<i class="fas fa-users lux-gold-text text-xl"></i>';
    }
    
    // UKM status
    const statusElement = document.getElementById('assignmentUkmStatus');
    const ukmStatus = assignment.ukm.status.charAt(0).toUpperCase() + assignment.ukm.status.slice(1);
    statusElement.textContent = ukmStatus;
    statusElement.className = `px-2 py-1 rounded text-xs font-medium ${
        assignment.ukm.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
    }`;
    
    // Assignment information
    const assignmentDate = new Date(assignment.created_at);
    document.getElementById('assignmentDate').textContent = assignmentDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // Calculate duration
    const now = new Date();
    const diffTime = Math.abs(now - assignmentDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    document.getElementById('assignmentDuration').textContent = `${diffDays} days`;
    
    // Asumsi creator ada. Jika tidak, pakai fallback 'Admin'
    document.getElementById('assignmentCreator').textContent = assignment.creator?.name || 'Admin'; 
    document.getElementById('assignmentTotalStaff').textContent = assignment.ukm.staff_count + ' staff members';
    
    document.getElementById('assignmentDetailsModal').showModal();
}

function closeAssignmentModal() {
    document.getElementById('assignmentDetailsModal').close();
}

function confirmRemoveAssignment(staffName, ukmName) {
    return confirm(`Are you sure you want to remove ${staffName} from ${ukmName}? This action cannot be undone.`);
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('assignmentDetailsModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAssignmentModal();
            }
        });
    }
});

// Auto-close flash messages
setTimeout(() => {
    const flashMessages = document.querySelectorAll('.fixed');
    flashMessages.forEach(msg => msg.remove());
}, 5000);
</script>
@endsection