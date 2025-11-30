@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>
        <p class="text-gray-600">Kelola informasi profil Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Personal Information</h2>
                
                <form id="profileForm" action="{{ route('user.profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Full Name</label>
                            <input type="text" name="name" id="profileName" value="{{ $user->name }}" 
                                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            <span id="profileNameError" class="error-message"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" value="{{ $user->email }}" 
                                   class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
                            <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Phone Number</label>
                            <input type="text" name="phone" id="profilePhone" value="{{ $user->phone }}" 
                                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            <span id="profilePhoneError" class="error-message"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">NIM</label>
                            <input type="text" value="{{ $user->nim }}" 
                                   class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium mb-1">Fakultas</label>
                            <input type="text" name="fakultas" id="profileFakultas" value="{{ $user->fakultas }}" 
                                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            <span id="profileFakultasError" class="error-message"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jurusan</label>
                            <input type="text" name="jurusan" id="profileJurusan" value="{{ $user->jurusan }}" 
                                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            <span id="profileJurusanError" class="error-message"></span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-1">Angkatan</label>
                        <input type="number" name="angkatan" id="profileAngkatan" value="{{ $user->angkatan }}" 
                               class="w-full md:w-1/2 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                        <span id="profileAngkatanError" class="error-message"></span>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" id="profileSubmit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- UKM Registrations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">My UKM Registrations</h2>
            
            @if($registrations->count() > 0)
                <div class="space-y-3">
                    @foreach($registrations as $registration)
                    <div class="p-3 border rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold">{{ $registration->ukm->name }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $registration->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $registration->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $registration->status }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-2">
                            Applied: {{ $registration->created_at->format('d M Y') }}
                        </p>
                        
                        @if($registration->approved_at)
                        <p class="text-sm text-gray-600">
                            Processed: {{ $registration->approved_at->format('d M Y') }}
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-600">No UKM registrations yet.</p>
                    <a href="{{ route('user.ukm.list') }}" class="text-blue-600 hover:text-blue-700 text-sm mt-2 inline-block">
                        Browse UKM →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Validation Rules untuk Profile
const profileValidationRules = {
    profileName: {
        required: true,
        minLength: 2,
        maxLength: 255,
        messages: {
            required: 'Nama wajib diisi.',
            minLength: 'Nama minimal 2 karakter.',
            maxLength: 'Nama maksimal 255 karakter.'
        }
    },
    profilePhone: {
        required: true,
        minLength: 10,
        maxLength: 15,
        pattern: /^[0-9+-\s()]+$/,
        messages: {
            required: 'Nomor HP wajib diisi.',
            minLength: 'Nomor HP minimal 10 digit.',
            maxLength: 'Nomor HP maksimal 15 digit.',
            pattern: 'Format nomor HP tidak valid.'
        }
    },
    profileFakultas: {
        required: true,
        minLength: 2,
        maxLength: 255,
        messages: {
            required: 'Fakultas wajib diisi.',
            minLength: 'Fakultas minimal 2 karakter.',
            maxLength: 'Fakultas maksimal 255 karakter.'
        }
    },
    profileJurusan: {
        required: true,
        minLength: 2,
        maxLength: 255,
        messages: {
            required: 'Jurusan wajib diisi.',
            minLength: 'Jurusan minimal 2 karakter.',
            maxLength: 'Jurusan maksimal 255 karakter.'
        }
    },
    profileAngkatan: {
        required: true,
        min: 2000,
        max: new Date().getFullYear() + 1,
        messages: {
            required: 'Angkatan wajib diisi.',
            min: 'Angkatan minimal tahun 2000.',
            max: `Angkatan maksimal tahun ${new Date().getFullYear() + 1}.`
        }
    }
};

// Utility functions (sama seperti di layout)
function showError(inputId, message) {
    const input = document.getElementById(inputId);
    const errorSpan = document.getElementById(inputId + 'Error');

    if (input && errorSpan) {
        input.classList.add('error-input');
        input.classList.remove('success-input');
        errorSpan.textContent = message;
    }
}

function showSuccess(inputId) {
    const input = document.getElementById(inputId);
    const errorSpan = document.getElementById(inputId + 'Error');

    if (input && errorSpan) {
        input.classList.remove('error-input');
        input.classList.add('success-input');
        errorSpan.textContent = '';
    }
}

function clearError(inputId) {
    const input = document.getElementById(inputId);
    const errorSpan = document.getElementById(inputId + 'Error');

    if (input && errorSpan) {
        input.classList.remove('error-input', 'success-input');
        errorSpan.textContent = '';
    }
}

// Validate individual field
function validateProfileField(fieldId) {
    const rules = profileValidationRules[fieldId];
    if (!rules) return true;

    const input = document.getElementById(fieldId);
    if (!input) return true;

    const value = input.value.trim();
    let isValid = true;

    // Clear previous error
    clearError(fieldId);

    // Required validation
    if (rules.required && !value) {
        showError(fieldId, rules.messages.required);
        return false;
    }

    // Skip further validation if not required and empty
    if (!rules.required && !value) {
        return true;
    }

    // Pattern validation
    if (rules.pattern && value && !rules.pattern.test(value)) {
        showError(fieldId, rules.messages.pattern);
        isValid = false;
    }

    // Min length validation
    if (rules.minLength && value.length < rules.minLength) {
        showError(fieldId, rules.messages.minLength);
        isValid = false;
    }

    // Max length validation
    if (rules.maxLength && value.length > rules.maxLength) {
        showError(fieldId, rules.messages.maxLength);
        isValid = false;
    }

    // Min value validation (for numbers)
    if (rules.min !== undefined && parseInt(value) < rules.min) {
        showError(fieldId, rules.messages.min);
        isValid = false;
    }

    // Max value validation (for numbers)
    if (rules.max !== undefined && parseInt(value) > rules.max) {
        showError(fieldId, rules.messages.max);
        isValid = false;
    }

    // Show success if valid
    if (isValid && value) {
        showSuccess(fieldId);
    }

    return isValid;
}

// Validate entire profile form
function validateProfileForm() {
    let isValid = true;
    const fields = Object.keys(profileValidationRules);

    fields.forEach(field => {
        if (!validateProfileField(field)) {
            isValid = false;
        }
    });

    return isValid;
}

// Check if profile form is valid (for button state)
function isProfileFormValid() {
    const fields = Object.keys(profileValidationRules);
    
    for (let fieldId of fields) {
        const field = document.getElementById(fieldId);
        if (!field || !field.value.trim()) {
            return false;
        }
        
        const rules = profileValidationRules[fieldId];
        const value = field.value.trim();
        
        // Required check
        if (rules.required && !value) return false;
        
        // Length checks
        if (rules.minLength && value.length < rules.minLength) return false;
        if (rules.maxLength && value.length > rules.maxLength) return false;
        
        // Pattern check
        if (rules.pattern && !rules.pattern.test(value)) return false;
        
        // Numeric checks
        if (rules.min !== undefined && parseInt(value) < rules.min) return false;
        if (rules.max !== undefined && parseInt(value) > rules.max) return false;
    }
    
    return true;
}

// Update submit button state
function updateProfileSubmitButton() {
    const submitBtn = document.getElementById('profileSubmit');
    if (submitBtn) {
        submitBtn.disabled = !isProfileFormValid();
    }
}

// Setup real-time validation for profile form
function setupProfileValidation() {
    Object.keys(profileValidationRules).forEach(fieldId => {
        const input = document.getElementById(fieldId);
        if (input) {
            input.addEventListener('input', () => {
                validateProfileField(fieldId);
                updateProfileSubmitButton();
            });

            input.addEventListener('blur', () => validateProfileField(fieldId));
        }
    });

    // Set initial button state
    updateProfileSubmitButton();
}

// Form submission handling
function setupProfileFormSubmission() {
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            if (!validateProfileForm()) {
                e.preventDefault();
            } else {
                // Show loading state
                const submitBtn = document.getElementById('profileSubmit');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
                
                // Allow form to submit normally
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 100);
            }
        });
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupProfileValidation();
    setupProfileFormSubmission();
});
</script>

<style>
.error-input {
    border-color: #ef4444 !important;
    background-color: #fef2f2;
}

.error-message {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: block;
}

.success-input {
    border-color: #10b981 !important;
    background-color: #f0fdf4;
}
</style>
@endsection