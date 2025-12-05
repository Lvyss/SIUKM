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

{{-- Bagian Title dan Counter --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Staff Assignment Management</h1>
    <div class="text-sm font-semibold lux-gold-text px-3 py-1 rounded-full border border-gray-300 bg-amber-50 whitespace-nowrap">
        <i class="fas fa-link mr-1 lux-gold-text"></i> **{{ $ukmStaff->count() }}** Active Assignments
    </div>
</div>


{{-- Bagian Stats Cards - 2 kolom di mobile --}}
<div class="grid grid-cols-2 gap-3 sm:gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
    {{-- Total Staff Users (Warna Gold) --}}
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-amber-100 lux-gold-text mr-4 flex-shrink-0">
                <i class="fas fa-users text-lg"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-600 truncate">Total Staff</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $staffUsers->count() }}</p>
            </div>
        </div>
    </div>
    
    {{-- Active Assignments (Warna Gold) --}}
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-amber-100 lux-gold-text mr-4 flex-shrink-0">
                <i class="fas fa-link text-lg"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-600 truncate">Assignments</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $ukmStaff->count() }}</p>
            </div>
        </div>
    </div>
    
    {{-- UKM with Staff (Warna Gold) --}}
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-amber-100 lux-gold-text mr-4 flex-shrink-0">
                <i class="fas fa-building text-lg"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-600 truncate">UKM Staff</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $ukmStaff->unique('ukm_id')->count() }}</p>
            </div>
        </div>
    </div>
    
    {{-- Available UKM (Warna Gold) --}}
    <div class="floating-card p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-amber-100 lux-gold-text mr-4 flex-shrink-0">
                <i class="fas fa-clipboard-list text-lg"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-600 truncate">UKM Available</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $ukms->count() }}</p>
            </div>
        </div>
    </div>
</div>


{{-- Bagian Form Assign Staff --}}
<div class="floating-card mb-6">
    <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50/50">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-user-plus mr-2 lux-gold-text"></i>
            Assign Staff to UKM
        </h2>
    </div>
    <div class="p-4 sm:p-5">
        <form id="assignStaffForm" action="{{ route('admin.staff.assign') }}" method="POST">
            @csrf
            <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-1 md:grid-cols-2 sm:gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Staff User *</label>
                    <select name="user_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base">
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
 {{-- Detail Card --}}
                    <div id="staffDetails" class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg hidden">
                        <div class="text-sm text-gray-700 space-y-1">
                            <div><strong class="lux-gold-text">Email:</strong> <span id="staffEmail" class="break-words">-</span></div>
                            <div><strong class="lux-gold-text">NIM:</strong> <span id="staffNim">-</span></div>
                            <div><strong class="lux-gold-text">Current Assignments:</strong> <span id="staffAssignments">0</span></div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">UKM *</label>
                    <select name="ukm_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 input-lux:focus transition duration-200 text-sm sm:text-base">
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
 {{-- Detail Card --}}
                    <div id="ukmDetails" class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg hidden">
                        <div class="text-sm text-gray-700 space-y-1">
                            <div><strong class="lux-gold-text">Current Staff:</strong> <span id="ukmStaffCount">0</span></div>
                            <div><strong class="lux-gold-text">Status:</strong> <span id="ukmStatus" class="px-2 py-1 rounded text-xs inline-block mt-1"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                <button type="button" onclick="resetForm()" 
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium text-sm order-2 sm:order-1 ">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </button>
                <button type="submit" 
                        class="lux-button px-6 py-2.5 rounded-lg hover:bg-amber-700 transition duration-200 font-medium flex items-center justify-center text-sm order-1 sm:order-2 mb-3 sm:mb-0">
                    <i class="fas fa-link mr-2"></i>Assign Staff
                </button>
            </div>
        </form>
    </div>
</div>



{{-- Bagian Table Header --}}
<div class="floating-card overflow-hidden">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 sm:p-5 border-b border-gray-100 bg-gray-50/50 gap-4">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-list-check mr-2 lux-gold-text"></i>
            Staff Assignments
        </h2>
        
        <div class="w-full sm:w-auto">
            <div class="relative">
                <input type="text" id="searchAssignments" placeholder="Search assignments..." 
                       class="border border-gray-300 rounded-lg px-4 py-2 pl-10 input-lux:focus w-full sm:w-64 text-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto -mx-2 sm:mx-0">
        <table class="w-full min-w-full">
            <thead class="hidden sm:table-header-group">
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
                <tr class="hover-row-table transition duration-150 assignment-row block sm:table-row border-b sm:border-b-0 last:border-b-0">
                    {{-- Mobile Card View --}}
                    <td class="block sm:hidden p-4">
                        <div class="bg-gray-50 rounded-lg p-4 mb-2">
                            {{-- Staff Info Mobile --}}
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3 shadow-md">
                                    {{ strtoupper(substr($assignment->user->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-900 truncate">{{ $assignment->user->name }}</div>
                                    <div class="text-sm text-gray-500">Staff Member</div>
                                </div>
                            </div>
                            
                            {{-- Contact Info Mobile --}}
                            <div class="mb-3">
                                <div class="text-sm text-gray-900 font-medium truncate">{{ $assignment->user->email }}</div>
                                <div class="text-xs text-gray-600"><i class="fas fa-id-card mr-1"></i>{{ $assignment->user->nim ?? 'No NIM' }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-phone mr-1"></i>{{ $assignment->user->phone ?? 'No phone' }}
                                </div>
                            </div>
                            
                            {{-- UKM Details Mobile --}}
                            <div class="flex items-center mb-3">
                                @if($assignment->ukm->logo)
                                    <img src="{{ $assignment->ukm->logo }}" alt="{{ $assignment->ukm->name }}" 
                                         class="w-8 h-8 rounded-lg object-cover mr-3 border border-gray-200">
                                @else
                                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center mr-3 border border-amber-200">
                                        <i class="fas fa-users lux-gold-text text-sm"></i>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-900 truncate">{{ $assignment->ukm->name }}</div>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                            {{ $assignment->ukm->category->name }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                            {{ $assignment->ukm->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $assignment->ukm->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Assignment Date Mobile --}}
                            <div class="mb-3">
                                <div class="text-sm text-gray-900">{{ $assignment->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $assignment->created_at->diffForHumans() }}</div>
                            </div>
                            
                            {{-- Actions Mobile --}}
                            <div class="flex space-x-2 pt-3 border-t border-gray-200">
                                <button onclick="viewAssignmentDetails({{ $assignment }})" 
                                        class="flex-1 bg-amber-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-amber-600 transition duration-200 flex items-center justify-center font-medium">
                                    <i class="fas fa-eye mr-2"></i> View
                                </button>
                                <form action="{{ route('admin.staff.remove', $assignment->id) }}" method="POST" 
                                      onsubmit="return confirmRemoveAssignment('{{ $assignment->user->name }}', '{{ $assignment->ukm->name }}')" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center justify-center font-medium">
                                        <i class="fas fa-unlink mr-2"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                    
                    {{-- Desktop Table View --}}
                    <td class="hidden sm:table-cell p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3 shadow-md">
                                {{ strtoupper(substr($assignment->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $assignment->user->name }}</div>
                                <div class="text-sm text-gray-500">Staff Member</div>
                            </div>
                        </div>
                    </td>
                    
                    <td class="hidden sm:table-cell p-4">
                        <div class="text-sm text-gray-900 font-medium">{{ $assignment->user->email }}</div>
                        <div class="text-xs text-gray-600"><i class="fas fa-id-card mr-1"></i>{{ $assignment->user->nim ?? 'No NIM' }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-phone mr-1"></i>{{ $assignment->user->phone ?? 'No phone' }}
                        </div>
                    </td>
                    
                    <td class="hidden sm:table-cell p-4">
                        <div class="flex items-center">
                            @if($assignment->ukm->logo)
                                <img src="{{ $assignment->ukm->logo }}" alt="{{ $assignment->ukm->name }}" 
                                     class="w-8 h-8 rounded-lg object-cover mr-3 border border-gray-200">
                            @else
                                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center mr-3 border border-amber-200">
                                    <i class="fas fa-users lux-gold-text text-sm"></i>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $assignment->ukm->name }}</div>
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
                    
                    <td class="hidden sm:table-cell p-4">
                        <div class="text-sm text-gray-900">{{ $assignment->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $assignment->created_at->diffForHumans() }}</div>
                    </td>
                    
                    <td class="hidden sm:table-cell p-4">
                        <div class="flex space-x-2">
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
        <i class="fas fa-user-tie text-4xl sm:text-5xl text-gray-300 mb-4 lux-gold-text"></i>
        <p class="text-gray-600 text-base sm:text-lg mb-2 font-semibold">No staff assignments yet</p>
        <p class="text-gray-500 text-xs sm:text-sm mb-4 px-4">Assign staff members to UKM to get started</p>
        <button onclick="scrollToAssignForm()" 
                class="lux-button px-4 py-2 rounded-lg hover:bg-amber-700 transition duration-200 font-semibold mx-auto text-sm sm:text-base">
            <i class="fas fa-user-plus mr-2"></i>Assign First Staff
        </button>
    </div>
    @endif
</div>


{{-- Modal Responsive --}}
<dialog id="assignmentDetailsModal" class="bg-white rounded-lg shadow-xl w-[95vw] sm:w-full max-w-lg max-h-[90vh] overflow-y-auto backdrop:bg-black/50 mx-auto sm:mx-0">
    <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-100">
        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-info-circle mr-2 lux-gold-text"></i> Assignment Details
        </h3>
        <button onclick="closeAssignmentModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
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
    
     <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3 p-4 sm:p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-lg">
        <button onclick="closeAssignmentModal()" 
                class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-200 font-medium text-sm">
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


const modal = document.getElementById('assignmentDetailsModal');
    modal.showModal();
    
    // Untuk mobile, pastikan modal bisa di-scroll
    if (window.innerWidth < 640) {
        modal.classList.add('fixed', 'inset-0', 'z-50', 'm-0');
    }
}

function closeAssignmentModal() {
    const modal = document.getElementById('assignmentDetailsModal');
    modal.close();
    
    if (window.innerWidth < 640) {
        modal.classList.remove('fixed', 'inset-0', 'z-50', 'm-0');
    }
}

function confirmRemoveAssignment(staffName, ukmName) {
    return confirm(`Are you sure you want to remove ${staffName} from ${ukmName}? This action cannot be undone.`);
}

// Responsive table search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchAssignments');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.assignment-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});


</script>
@endsection