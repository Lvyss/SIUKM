@extends('layouts.staff')

@section('content')
<style>
    /* Purple-Violet Luxury Theme Styles */
    /* Warna utama: #6b21a8 (Purple-800) / #4c1d95 (Violet-800) */
    /* Warna aksen: #a78bfa (Violet-400) / #e879f9 (Fuchsia-300) */
    
    .lux-bg { background-color: #f3f4f6; } /* Tailwind gray-100 */
    .lux-purple-text { color: #6b21a8; } /* Tailwind purple-800 */
    .lux-purple-bg-light { background-color: #f3e8ff; } /* Tailwind purple-50 */
    
    .floating-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb; /* Tailwind gray-200 */
    }
    .floating-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }
    
    .lux-button {
        background-color: #7c3aed; /* Tailwind violet-600 */
        color: white;
        transition: background-color 0.2s;
        font-weight: 600;
    }
    .lux-button:hover {
        background-color: #6d28d9; /* Tailwind violet-700 */
    }
    
    .input-lux:focus {
        border-color: #a78bfa; /* Tailwind violet-400 */
        box-shadow: 0 0 0 2px rgba(167, 139, 250, 0.4);
        outline: none;
    }
    
    .hover-row-table:hover {
        background-color: #faf5ff; /* Tailwind violet-50 / off-white */
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

    /* Error Styles */
    .border-error {
        border-color: #dc2626 !important; /* Tailwind red-600 */
    }
    .text-error {
        color: #dc2626; /* Tailwind red-600 */
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Loading Spinner */
    .loading-spinner {
        border: 3px solid #f3f4f6;
        border-top: 3px solid #7c3aed; /* Warna spinner violet */
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">My Managed UKM</h1>
    
    <div class="text-sm font-semibold lux-purple-text px-4 py-2 rounded-full border border-violet-300 bg-violet-50 shadow-sm">
        <i class="fas fa-users-cog mr-1 lux-purple-text"></i> Total: {{ $managedUkms->count() }} UKM
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <div class="floating-card p-5 border-l-4 border-violet-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-violet-100/70">
                <i class="fas fa-users lux-purple-text text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total UKM</p>
                <p class="text-2xl font-bold text-gray-900">{{ $managedUkms->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-5 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100/70">
                <i class="fas fa-calendar text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Events</p>
                <p class="text-2xl font-bold text-gray-900">{{ $managedUkms->sum('events_count') }}</p>
            </div>
        </div>
    </div>
    
    <div class="floating-card p-5 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100/70">
                <i class="fas fa-newspaper text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Feeds</p>
                <p class="text-2xl font-bold text-gray-900">{{ $managedUkms->sum('feeds_count') }}</p>
            </div>
        </div>
    </div>
</div>


<div class="floating-card overflow-hidden">
    <div class="flex justify-between items-center p-5 border-b bg-violet-50/50">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-database mr-2 lux-purple-text"></i>
            UKM Data Overview
        </h2>
    </div>
    
    <div class="p-6">
        @if($managedUkms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($managedUkms as $ukm)
                <div class="floating-card p-5 hover:shadow-xl transition cursor-pointer" 
                     onclick="openDetailModal({{ $ukm->id }})">
                    <div class="flex items-start mb-4">
                        @if($ukm->logo)
                            <img src="{{ $ukm->logo }}" alt="{{ $ukm->name }}" 
                                 class="w-12 h-12 rounded-full object-cover mr-3 border-2 border-violet-300 shadow-inner">
                        @else
                            <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center mr-3 border-2 border-violet-300">
                                <i class="fas fa-users lux-purple-text text-xl"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $ukm->name }}</h3>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800">
                                {{ $ukm->category->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-4 text-sm text-gray-600 italic border-l-2 border-violet-300 pl-3">
                        {{ Str::limit($ukm->description, 70) }}
                    </div>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800"
                              title="{{ $ukm->staff_count }} Staff Members">
                            <i class="fas fa-user-friends mr-1"></i> Staff: {{ $ukm->staff_count }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-fuchsia-100 text-fuchsia-800"
                              title="{{ $ukm->events_count }} Total Events">
                            <i class="fas fa-calendar mr-1"></i> Events: {{ $ukm->events_count }}
                        </span>
                    </div>
                    
                    <div class="flex space-x-2 pt-3 border-t border-gray-100">
                        <button onclick="event.stopPropagation(); openEditModal({{ $ukm->id }})" 
                                class="flex-1 lux-button px-4 py-2 rounded-lg hover:bg-violet-700 transition duration-200 flex items-center justify-center font-semibold text-sm">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </button>
                        <button onclick="event.stopPropagation(); openDetailModal({{ $ukm->id }})" 
                                class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition duration-200 flex items-center justify-center font-semibold text-sm">
                            <i class="fas fa-eye mr-2"></i>View
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 lux-purple-bg-light rounded-lg border border-violet-200">
                <i class="fas fa-users-slash text-5xl text-violet-500 mb-4"></i>
                <p class="text-gray-700 text-lg mb-2 font-semibold">No UKM Assigned</p>
                <p class="text-gray-500 text-sm mb-4">You are not assigned to manage any UKM yet.</p>
            </div>
        @endif
    </div>
</div>

<dialog id="detailModal" class="bg-white rounded-xl shadow-2xl w-full max-w-2xl backdrop:bg-black/50">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center lux-purple-text">
                <i class="fas fa-info-circle mr-2"></i> UKM Details
            </h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-red-500 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="detailContent" class="space-y-6">
            </div>
    </div>
</dialog>

<dialog id="editModal" class="bg-white rounded-xl shadow-2xl w-full max-w-4xl backdrop:bg-black/50">
    <div class="p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6 border-b pb-3">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center lux-purple-text">
                <i class="fas fa-edit mr-2"></i> Edit UKM Information
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editUkmForm" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div id="currentLogoContainer" class="text-center p-5 lux-purple-bg-light rounded-xl border border-violet-200">
                <label class="block text-sm font-bold text-gray-700 mb-3">Current Logo</label>
                <div id="currentLogoPreview" class="flex justify-center mb-3">
                    </div>
                <p class="text-xs text-gray-600 italic">Upload new logo below to replace current one</p>
            </div>

            <div>
                <label for="editLogo" class="block text-sm font-bold text-gray-700 mb-2">Upload New Logo</label>
                <input type="file" id="editLogo" name="logo" accept="image/*" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-0 input-lux"
                        onchange="validateFileSize(this, 2)">
                <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG, GIF | Max: 2MB</p>
                <div id="logoError" class="text-error"></div>
            </div>

            <div>
                <label for="editDescription" class="block text-sm font-bold text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea id="editDescription" name="description" required rows="4" maxlength="1000"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-0 input-lux resize-none"
                              placeholder="Enter UKM description..."></textarea>
                <div class="flex justify-between items-center mt-1">
                    <div id="descriptionError" class="text-error"></div>
                    <div class="text-xs text-gray-500">
                        <span id="descriptionCount">0</span>/1000 characters
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="editVision" class="block text-sm font-bold text-gray-700 mb-2">Vision</label>
                    <textarea id="editVision" name="vision" rows="3" maxlength="500"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-0 input-lux resize-none"
                              placeholder="Enter UKM vision..."></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <div id="visionError" class="text-error"></div>
                        <div class="text-xs text-gray-500">
                            <span id="visionCount">0</span>/500 characters
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="editMission" class="block text-sm font-bold text-gray-700 mb-2">Mission</label>
                    <textarea id="editMission" name="mission" rows="3" maxlength="500"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-0 input-lux resize-none"
                              placeholder="Enter UKM mission..."></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <div id="missionError" class="text-error"></div>
                        <div class="text-xs text-gray-500">
                            <span id="missionCount">0</span>/500 characters
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="editContactPerson" class="block text-sm font-bold text-gray-700 mb-2">Contact Person</label>
                    <input type="text" id="editContactPerson" name="contact_person"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-0 input-lux"
                           placeholder="Enter contact person name...">
                    <div id="contactPersonError" class="text-error"></div>
                </div>
                
                <div>
                    <label for="editInstagram" class="block text-sm font-bold text-gray-700 mb-2">Instagram</label>
                    <input type="text" id="editInstagram" name="instagram"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-0 input-lux"
                           placeholder="@username">
                    <div id="instagramError" class="text-error"></div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" 
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-semibold">
                    Cancel
                </button>
                <button type="submit" id="submitEditBtn"
                        class="lux-button px-6 py-3 rounded-lg hover:bg-violet-700 transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    <span>Update UKM</span>
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
// Global variables
let currentEditingUkmId = null;

// Helper to display flash messages (from your parent layout)
function showFlashMessage(type, message) {
    // Implementasi ini harus ada di layout induk (layouts/staff.blade.php)
    // Untuk saat ini, kita buat implementasi sederhana agar berfungsi:
    const flashDiv = document.createElement('div');
    flashDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-semibold ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } animate-in slide-in-from-top duration-500`;
    flashDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(flashDiv);
    
    setTimeout(() => {
        flashDiv.remove();
    }, 5000);
}

// VALIDASI TAMBAHAN: File Size
function validateFileSize(input, maxMB) {
    const maxBytes = maxMB * 1024 * 1024;
    const errorElement = document.getElementById('logoError');
    
    if (input.files.length > 0) {
        if (input.files[0].size > maxBytes) {
            errorElement.innerHTML = `File size exceeds ${maxMB}MB limit.`;
            input.classList.add('border-error');
            input.value = ''; // Clear file input
        } else {
            errorElement.innerHTML = '';
            input.classList.remove('border-error');
        }
    }
}


// --- MODAL FUNCTIONS ---

function closeDetailModal() {
    document.getElementById('detailModal').close();
}

function openDetailModal(ukmId) {
    const detailContent = document.getElementById('detailContent');
    detailContent.innerHTML = `<div class="text-center py-8"><div class="loading-spinner"></div> <p class="mt-2 text-gray-600">Loading details...</p></div>`; // Loading State

    // Menggunakan route yang didefinisikan di Laravel (asumsi route 'staff.ukms.show' mengembalikan JSON)
    fetch(`/staff/ukms/${ukmId}`) 
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const ukm = data.data;
                
                // Gunakan template string untuk mengisi detail konten
                detailContent.innerHTML = `
                    <div class="flex items-center space-x-4 pb-4 border-b">
                        ${ukm.logo ? 
                            `<img src="${ukm.logo}" alt="${ukm.name}" class="w-20 h-20 rounded-xl object-cover border shadow-sm">` :
                            `<div class="w-20 h-20 bg-violet-50 rounded-xl flex items-center justify-center border border-violet-300">
                                <i class="fas fa-users lux-purple-text text-3xl"></i>
                            </div>`
                        }
                        <div>
                            <h4 class="text-2xl font-extrabold text-gray-900">${ukm.name}</h4>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-violet-100 text-violet-800">
                                ${ukm.category.name}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                            <p class="text-gray-600">${ukm.description}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${ukm.vision ? `
                            <div class="p-4 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-1 lux-purple-text">Vision</label>
                                <p class="text-gray-600 text-sm">${ukm.vision}</p>
                            </div>
                            ` : ''}
                            
                            ${ukm.mission ? `
                            <div class="p-4 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-1 lux-purple-text">Mission</label>
                                <p class="text-gray-600 text-sm">${ukm.mission}</p>
                            </div>
                            ` : ''}
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 pt-2 border-t">
                            ${ukm.contact_person ? `
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Contact Person</label>
                                <p class="text-gray-600 flex items-center text-sm">
                                    <i class="fas fa-user mr-2 text-violet-600"></i>${ukm.contact_person}
                                </p>
                            </div>
                            ` : ''}
                            
                            ${ukm.instagram ? `
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Instagram</label>
                                <p class="text-gray-600 flex items-center text-sm">
                                    <i class="fab fa-instagram mr-2 text-violet-600"></i>${ukm.instagram}
                                </p>
                            </div>
                            ` : ''}
                        </div>
                        
                        <div class="border-t pt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Statistics</label>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-violet-100 text-violet-800">
                                    <i class="fas fa-users mr-1"></i>${ukm.staff_count} Staff
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-fuchsia-100 text-fuchsia-800">
                                    <i class="fas fa-calendar mr-1"></i>${ukm.events_count} Events
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-newspaper mr-1"></i>${ukm.feeds_count} Feeds
                                </span>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('detailModal').showModal();
            } else {
                showFlashMessage('error', 'Failed to load UKM details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            detailContent.innerHTML = `<p class="text-center text-red-600 py-8">An error occurred. Check console for details.</p>`;
            showFlashMessage('error', 'Failed to load UKM details');
        });
}

function closeEditModal() {
    document.getElementById('editModal').close();
    resetEditForm();
    currentEditingUkmId = null;
}

function openEditModal(ukmId) {
    currentEditingUkmId = ukmId;
    
    resetEditForm();
    
    fetch(`/staff/ukms/${ukmId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const ukm = data.data;
                
                // Isi form
                document.getElementById('editDescription').value = ukm.description || '';
                document.getElementById('editVision').value = ukm.vision || '';
                document.getElementById('editMission').value = ukm.mission || '';
                document.getElementById('editContactPerson').value = ukm.contact_person || '';
                document.getElementById('editInstagram').value = ukm.instagram || '';
                
                // Update character counters
                updateCharacterCount('editDescription', 'descriptionCount');
                updateCharacterCount('editVision', 'visionCount');
                updateCharacterCount('editMission', 'missionCount');
                
                // Set logo preview
                const logoPreview = document.getElementById('currentLogoPreview');
                if (ukm.logo) {
                    logoPreview.innerHTML = `
                        <img src="${ukm.logo}" alt="${ukm.name}" 
                             class="w-32 h-32 rounded-xl object-cover border-2 border-violet-400 shadow-md">
                    `;
                } else {
                    logoPreview.innerHTML = `
                        <div class="w-32 h-32 bg-violet-100 rounded-xl flex items-center justify-center border border-violet-300">
                            <i class="fas fa-users lux-purple-text text-4xl"></i>
                        </div>
                    `;
                }
                
                document.getElementById('editModal').showModal();
            } else {
                showFlashMessage('error', 'Failed to load UKM data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessage('error', 'Failed to load UKM data');
        });
}


// --- FORM & VALIDATION LOGIC ---

function resetEditForm() {
    document.getElementById('editUkmForm').reset();
    
    // Clear all errors and borders
    resetErrors();
    
    // Reset character counters
    document.getElementById('descriptionCount').textContent = '0';
    document.getElementById('visionCount').textContent = '0';
    document.getElementById('missionCount').textContent = '0';
}

function updateCharacterCount(textareaId, counterId) {
    const textarea = document.getElementById(textareaId);
    const counter = document.getElementById(counterId);
    if (textarea && counter) {
        counter.textContent = textarea.value.length;
    }
}

function resetErrors() {
    const errorElements = document.querySelectorAll('.text-error');
    errorElements.forEach(el => el.innerHTML = '');
    
    const inputElements = document.querySelectorAll('#editUkmForm input, #editUkmForm textarea');
    inputElements.forEach(el => el.classList.remove('border-error'));
}

function displayValidationErrors(errors) {
    resetErrors();
    
    Object.keys(errors).forEach(field => {
        // Logika untuk logoError
        if (field === 'logo') {
             document.getElementById('logoError').innerHTML = errors[field][0];
             document.getElementById('editLogo').classList.add('border-error');
             return;
        }
        
        // Logika untuk field lain
        const errorElement = document.getElementById(field + 'Error');
        const inputElement = document.querySelector(`[name="${field}"]`);
        
        if (errorElement && inputElement) {
            errorElement.innerHTML = errors[field][0];
            inputElement.classList.add('border-error');
        }
    });
    
    showFlashMessage('error', 'Please check the form for errors');
}


// --- EVENT LISTENERS ---

document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editUkmForm');
    
    // Character count listeners
    document.getElementById('editDescription').addEventListener('input', function() {
        updateCharacterCount('editDescription', 'descriptionCount');
    });
    document.getElementById('editVision').addEventListener('input', function() {
        updateCharacterCount('editVision', 'visionCount');
    });
    document.getElementById('editMission').addEventListener('input', function() {
        updateCharacterCount('editMission', 'missionCount');
    });
    
    // Form Submission Handler
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentEditingUkmId) {
                showFlashMessage('error', 'No UKM selected for editing');
                return;
            }
            
            // Perbaikan untuk mengirimkan method PUT/PATCH via form data
            const formData = new FormData(this);
            formData.append('_method', 'PUT'); // Tambahkan _method=PUT secara manual
            
            resetErrors();
            
            // Show loading state
            const submitBtn = document.getElementById('submitEditBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<div class="loading-spinner mr-2"></div> <span>Updating...</span>';
            submitBtn.disabled = true;
            
            fetch(`/staff/ukms/${currentEditingUkmId}`, {
                method: 'POST', // Kirim sebagai POST karena HTML Form tidak mendukung PUT/PATCH
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '',
                    // Penting: Jangan sertakan 'Content-Type': 'application/json' saat mengirim FormData dengan file upload
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFlashMessage('success', data.message);
                    closeEditModal();
                    
                    // Reload page to reflect changes
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Handle validation errors or server-side errors
                    if (data.errors) {
                        displayValidationErrors(data.errors);
                    } else {
                        showFlashMessage('error', data.message || 'Update failed due to server error.');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFlashMessage('error', 'An error occurred while updating UKM. Please check your network.');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Auto-close modals on backdrop click
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) closeDetailModal();
    });

    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
});
</script>
@endsection