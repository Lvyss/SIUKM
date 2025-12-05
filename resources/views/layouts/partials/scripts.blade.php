<script>
// =================================================================
// FLASH MESSAGE AUTOHIDE
// =================================================================
setTimeout(() => {
    const messages = document.querySelectorAll('.fixed');
    messages.forEach(msg => {
        if (msg.classList.contains('bg-green-500') || msg.classList.contains('bg-red-500')) {
            msg.remove();
        }
    });
}, 5000);

// =================================================================
// DROPDOWN PROFILE TOGGLE  
// =================================================================
const profileButton = document.getElementById('profile-menu-button');
const profileDropdown = document.getElementById('profile-dropdown');
const profileContainer = document.getElementById('profile-menu-container');

function toggleProfileDropdown() {
    if (!profileButton || !profileDropdown) return;

    const isHidden = profileDropdown.classList.contains('hidden');
    
    if (isHidden) {
        profileDropdown.classList.remove('hidden', 'opacity-0', 'translate-y-2');
        profileDropdown.classList.add('opacity-100', 'translate-y-0');
    } else {
        profileDropdown.classList.remove('opacity-100', 'translate-y-0');
        profileDropdown.classList.add('opacity-0', 'translate-y-2');
        
        setTimeout(() => {
            profileDropdown.classList.add('hidden');
        }, 200);
    }
}

if (profileButton) {
    profileButton.addEventListener('click', toggleProfileDropdown);
}

document.addEventListener('click', function(event) {
    if (!profileContainer) return;
    
    const isClickInside = profileContainer.contains(event.target);
    
    if (!isClickInside && !profileDropdown.classList.contains('hidden')) {
        profileDropdown.classList.remove('opacity-100', 'translate-y-0');
        profileDropdown.classList.add('opacity-0', 'translate-y-2');
        
        setTimeout(() => {
            profileDropdown.classList.add('hidden');
        }, 200);
    }
});
// =================================================================
// MOBILE MENU TOGGLE  
// =================================================================
function setupMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (!mobileMenuButton || !mobileMenu) return;
    
    mobileMenuButton.addEventListener('click', function() {
        // Toggle menu visibility
        const isHidden = mobileMenu.classList.contains('hidden');
        
        if (isHidden) {
            mobileMenu.classList.remove('hidden');
            // Toggle icon (hamburger to X)
            this.querySelector('svg.block').classList.add('hidden');
            this.querySelector('svg.hidden').classList.remove('hidden');
            this.setAttribute('aria-expanded', 'true');
        } else {
            mobileMenu.classList.add('hidden');
            // Toggle icon (X to hamburger)
            this.querySelector('svg.block').classList.remove('hidden');
            this.querySelector('svg.hidden').classList.add('hidden');
            this.setAttribute('aria-expanded', 'false');
        }
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!mobileMenuButton.contains(event.target) && 
            !mobileMenu.contains(event.target) && 
            !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.querySelector('svg.block').classList.remove('hidden');
            mobileMenuButton.querySelector('svg.hidden').classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
    });
}
// =================================================================
// PROFILE DROPDOWN TOGGLE - Desktop & Mobile  
// =================================================================
function setupProfileDropdowns() {
    // Desktop version
    const profileButtonDesktop = document.getElementById('profile-menu-button-desktop');
    const profileDropdownDesktop = document.getElementById('profile-dropdown-desktop');
    
    if (profileButtonDesktop && profileDropdownDesktop) {
        profileButtonDesktop.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = profileDropdownDesktop.classList.contains('hidden');
            
            if (isHidden) {
                profileDropdownDesktop.classList.remove('hidden', 'opacity-0', 'translate-y-2');
                profileDropdownDesktop.classList.add('opacity-100', 'translate-y-0');
            } else {
                profileDropdownDesktop.classList.remove('opacity-100', 'translate-y-0');
                profileDropdownDesktop.classList.add('opacity-0', 'translate-y-2');
                
                setTimeout(() => {
                    profileDropdownDesktop.classList.add('hidden');
                }, 200);
            }
        });
    }
    
    // Mobile version
    const profileButtonMobile = document.getElementById('profile-menu-button-mobile');
    const profileDropdownMobile = document.getElementById('profile-dropdown-mobile');
    
    if (profileButtonMobile && profileDropdownMobile) {
        profileButtonMobile.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = profileDropdownMobile.classList.contains('hidden');
            
            if (isHidden) {
                profileDropdownMobile.classList.remove('hidden', 'opacity-0', 'translate-y-2');
                profileDropdownMobile.classList.add('opacity-100', 'translate-y-0');
            } else {
                profileDropdownMobile.classList.remove('opacity-100', 'translate-y-0');
                profileDropdownMobile.classList.add('opacity-0', 'translate-y-2');
                
                setTimeout(() => {
                    profileDropdownMobile.classList.add('hidden');
                }, 200);
            }
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        // Close desktop dropdown
        if (profileDropdownDesktop && !profileDropdownDesktop.classList.contains('hidden')) {
            profileDropdownDesktop.classList.remove('opacity-100', 'translate-y-0');
            profileDropdownDesktop.classList.add('opacity-0', 'translate-y-2');
            
            setTimeout(() => {
                profileDropdownDesktop.classList.add('hidden');
            }, 200);
        }
        
        // Close mobile dropdown
        if (profileDropdownMobile && !profileDropdownMobile.classList.contains('hidden')) {
            profileDropdownMobile.classList.remove('opacity-100', 'translate-y-0');
            profileDropdownMobile.classList.add('opacity-0', 'translate-y-2');
            
            setTimeout(() => {
                profileDropdownMobile.classList.add('hidden');
            }, 200);
        }
    });
}
// =================================================================
// SIMPLE PASSWORD TOGGLE - SETUP ONCE
// =================================================================
function setupPasswordToggle() {
    // Login password toggle
    const loginToggle = document.getElementById('loginPasswordToggle');
    if (loginToggle) {
        loginToggle.addEventListener('click', function() {
            const field = document.getElementById('loginPassword');
            const icon = this.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                field.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        });
    }
    
    // Register password toggle
    const registerToggle = document.getElementById('registerPasswordToggle');
    if (registerToggle) {
        registerToggle.addEventListener('click', function() {
            const field = document.getElementById('registerPassword');
            const icon = this.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                field.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        });
    }
    
    // Confirm password toggle
    const confirmToggle = document.getElementById('registerPasswordConfirmationToggle');
    if (confirmToggle) {
        confirmToggle.addEventListener('click', function() {
            const field = document.getElementById('registerPasswordConfirmation');
            const icon = this.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                field.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        });
    }
}

// =================================================================
// VALIDATION RULES & FUNCTIONS - OPTIMIZED
// =================================================================
const validationRules = {
    loginEmail: {
        required: true,
        email: true,
        messages: {
            required: 'Email wajib diisi.',
            email: 'Format email tidak valid.'
        }
    },
    loginPassword: {
        required: true,
        messages: {
            required: 'Password wajib diisi.'
        }
    },
    registerName: {
        required: true,
        minLength: 2,
        maxLength: 255,
        messages: {
            required: 'Nama wajib diisi.',
            minLength: 'Nama minimal 2 karakter.',
            maxLength: 'Nama maksimal 255 karakter.'
        }
    },
    registerEmail: {
        required: true,
        email: true,
        pattern: /^[a-zA-Z0-9._%+-]+@students\.um\.ac\.id$/,
        messages: {
            required: 'Email wajib diisi.',
            email: 'Format email tidak valid.',
            pattern: 'Email harus menggunakan domain @students.um.ac.id.'
        }
    },
    registerPhone: {
        required: true,
        minLength: 11,
        maxLength: 15,
        pattern: /^[0-9+-\s()]+$/,
        messages: {
            required: 'Nomor HP wajib diisi.',
            minLength: 'Nomor HP minimal 11 digit.',
            maxLength: 'Nomor HP maksimal 15 digit.',
            pattern: 'Format nomor HP tidak valid.'
        }
    },
    registerNim: {
        required: true,
        minLength: 12,
        messages: {
            required: 'NIM wajib diisi.',
            minLength: 'NIM minimal 12 karakter.'
        }
    },
    registerFakultas: {
        required: true,
        minLength: 2,
        maxLength: 255,
        messages: {
            required: 'Fakultas wajib diisi.',
            minLength: 'Fakultas minimal 2 karakter.',
            maxLength: 'Fakultas maksimal 255 karakter.'
        }
    },
    registerJurusan: {
        required: true,
        minLength: 2,
        maxLength: 255,
        messages: {
            required: 'Jurusan wajib diisi.',
            minLength: 'Jurusan minimal 2 karakter.',
            maxLength: 'Jurusan maksimal 255 karakter.'
        }
    },
    registerAngkatan: {
        required: true,
        min: 2000,
        max: new Date().getFullYear() + 1,
        messages: {
            required: 'Angkatan wajib diisi.',
            min: 'Angkatan minimal tahun 2000.',
            max: `Angkatan maksimal tahun ${new Date().getFullYear() + 1}.`
        }
    },
    registerPassword: {
        required: true,
        minLength: 8,
        pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/,
        messages: {
            required: 'Password wajib diisi.',
            minLength: 'Password minimal 8 karakter.',
            pattern: 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol (@$!%*?&).'
        }
    },
    registerPasswordConfirmation: {
        required: true,
        match: 'registerPassword',
        messages: {
            required: 'Konfirmasi password wajib diisi.',
            match: 'Konfirmasi password tidak cocok.'
        }
    }
};

// Utility functions
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

// Validation functions
function validateLoginFormSilent() {
    const email = document.getElementById('loginEmail');
    const password = document.getElementById('loginPassword');
    if (!email || !password) return false;
    
    const emailValid = email.value.trim() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    const passwordValid = password.value.length >= 1;
    
    return emailValid && passwordValid;
}


function validateField(fieldId) {
    const rules = validationRules[fieldId];
    if (!rules) return true;

    const input = document.getElementById(fieldId);
    if (!input) return true;

    const value = input.value.trim();
    let isValid = true;

    clearError(fieldId);

    if (rules.required && !value) {
        showError(fieldId, rules.messages.required);
        return false;
    }

    if (!rules.required && !value) return true;

    // Email validation
    if (rules.email && value && fieldId === 'loginEmail') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showError(fieldId, rules.messages.email);
            isValid = false;
        }
    }

    // Pattern validation
    if (rules.pattern && value && fieldId !== 'loginEmail' && fieldId !== 'loginPassword') {
        if (!rules.pattern.test(value)) {
            showError(fieldId, rules.messages.pattern);
            isValid = false;
        }
    }

    // Length validation
    if (rules.minLength && value && !fieldId.includes('login') && value.length < rules.minLength) {
        showError(fieldId, rules.messages.minLength);
        isValid = false;
    }

    if (rules.maxLength && value && !fieldId.includes('login') && value.length > rules.maxLength) {
        showError(fieldId, rules.messages.maxLength);
        isValid = false;
    }

    // Number validation
    if (rules.min !== undefined && parseInt(value) < rules.min && !fieldId.includes('login')) {
        showError(fieldId, rules.messages.min);
        isValid = false;
    }

    if (rules.max !== undefined && parseInt(value) > rules.max && !fieldId.includes('login')) {
        showError(fieldId, rules.messages.max);
        isValid = false;
    }

    // Password confirmation
    if (rules.match && value && fieldId.includes('register')) {
        const matchField = document.getElementById(rules.match);
        if (matchField && value !== matchField.value) {
            showError(fieldId, rules.messages.match);
            isValid = false;
        }
    }

    if (isValid && value) showSuccess(fieldId);
    return isValid;
}

function validateForm(formType) {
    let isValid = true;
    const fields = Object.keys(validationRules).filter(field => field.startsWith(formType));
    fields.forEach(field => {
        if (!validateField(field)) isValid = false;
    });
    return isValid;
}

// =================================================================
// FIX: UPDATE SUBMIT BUTTONS (SIMPLE VERSION)
// =================================================================
function updateSubmitButtons() {
    const loginSubmit = document.getElementById('loginSubmit');
    const registerSubmit = document.getElementById('registerSubmit');
    
    // Untuk login: cek email dan password ada isinya
    if (loginSubmit) {
        const email = document.getElementById('loginEmail');
        const password = document.getElementById('loginPassword');
        const isValid = email && email.value.trim() && password && password.value.trim();
        loginSubmit.disabled = !isValid;
    }
    
    // Untuk register: cek semua field ada isinya
    if (registerSubmit) {
        const requiredFields = [
            'registerName', 'registerEmail', 'registerPhone', 'registerNim',
            'registerFakultas', 'registerJurusan', 'registerAngkatan', 
            'registerPassword', 'registerPasswordConfirmation'
        ];
        
        let allFilled = true;
        for (const fieldId of requiredFields) {
            const field = document.getElementById(fieldId);
            if (!field || field.value.trim() === '') {
                allFilled = false;
                break;
            }
        }
        
        // Juga cek password konfirmasi match
        const password = document.getElementById('registerPassword');
        const confirmPassword = document.getElementById('registerPasswordConfirmation');
        if (password && confirmPassword && password.value !== confirmPassword.value) {
            allFilled = false;
        }
        
        registerSubmit.disabled = !allFilled;
    }
}

function setupRealTimeValidation() {
    Object.keys(validationRules).forEach(fieldId => {
        const input = document.getElementById(fieldId);
        if (input) {
            input.addEventListener('input', () => {
                validateField(fieldId);
                updateSubmitButtons();
            });
            input.addEventListener('blur', () => validateField(fieldId));
        }
    });

    const passwordField = document.getElementById('registerPassword');
    if (passwordField) {
        passwordField.addEventListener('input', () => {
            const confirmField = document.getElementById('registerPasswordConfirmation');
            if (confirmField && confirmField.value) validateField('registerPasswordConfirmation');
            updateSubmitButtons();
        });
    }
}

function setupFormSubmission() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateForm('login')) e.preventDefault();
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validateForm('register')) {
                e.preventDefault();
            } else {
                const submitBtn = document.getElementById('registerSubmit');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mendaftarkan...';
                
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 100);
            }
        });
    }
}

// =================================================================
// MODAL FUNCTIONS - OPTIMIZED
// =================================================================
function _animateModalIn(modalId, contentId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(contentId);
    if (!modal || !content) return;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    void content.offsetWidth;
    content.classList.remove('scale-95', 'opacity-0');
    content.classList.add('scale-100', 'opacity-100');
    clearFormErrors(modalId);
}

function _animateModalOut(modalId, contentId, callback = () => {}) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(contentId);
    if (!modal || !content) return;

    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        callback();
    }, 300);
}

function clearFormErrors(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const errorMessages = modal.querySelectorAll('.error-message');
    const errorInputs = modal.querySelectorAll('.error-input, .success-input');

    errorMessages.forEach(el => el.textContent = '');
    errorInputs.forEach(el => el.classList.remove('error-input', 'success-input'));
}

function checkBodyScroll() {
    const loginHidden = document.getElementById('loginModal').classList.contains('hidden');
    const registerHidden = document.getElementById('registerModal').classList.contains('hidden');
    if (loginHidden && registerHidden) document.body.style.overflow = 'auto';
}

function showLoginModal() {
    _animateModalIn('loginModal', 'loginModalContent');
}

function closeLoginModal() {
    _animateModalOut('loginModal', 'loginModalContent', checkBodyScroll);
}

function showRegisterModal() {
    if (!document.getElementById('loginModal').classList.contains('hidden')) {
        _animateModalOut('loginModal', 'loginModalContent', () => {
            _animateModalIn('registerModal', 'registerModalContent');
        });
    } else {
        _animateModalIn('registerModal', 'registerModalContent');
    }
}

function closeRegisterModal() {
    _animateModalOut('registerModal', 'registerModalContent', checkBodyScroll);
}

// =================================================================
// REGISTER MODAL SPECIFIC FUNCTIONS
// =================================================================

function clearRegisterErrors() {
    const errorInputs = document.querySelectorAll('#registerForm .border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    const errorMessages = document.querySelectorAll('#registerForm .text-red-500');
    errorMessages.forEach(msg => msg.remove());

    const errorAlerts = document.querySelectorAll('#registerForm .bg-red-100');
    errorAlerts.forEach(alert => alert.remove());
}
// =================================================================
// MODAL SWITCH FUNCTIONS
// =================================================================
function switchToLoginFromRegister() {
    closeRegisterModal();
    setTimeout(() => {
        showLoginModal();
    }, 300);
}

function switchToRegisterFromLogin() {
    closeLoginModal();
    setTimeout(() => {
        showRegisterModal();
    }, 300);
}

// =================================================================
// INITIALIZATION
// =================================================================
document.addEventListener('DOMContentLoaded', function() {
    // Cek jika ada error dari register form
    // Tambahkan event listeners untuk tombol switch modal
    const switchToLoginBtn = document.getElementById('switchToLoginFromRegister');
    const switchToRegisterBtn = document.getElementById('switchToRegisterFromLogin');
    
    if (switchToLoginBtn) {
        switchToLoginBtn.addEventListener('click', switchToLoginFromRegister);
    }
    
    if (switchToRegisterBtn) {
        switchToRegisterBtn.addEventListener('click', switchToRegisterFromLogin);
    }
    
    // Juga untuk tombol "Daftar di sini" di modal login
    const registerFromLoginBtn = document.querySelector('#loginModal button[onclick*="showRegisterModal"]');
    if (registerFromLoginBtn) {
        // Hapus onclick dari HTML
        registerFromLoginBtn.removeAttribute('onclick');
        registerFromLoginBtn.addEventListener('click', switchToRegisterFromLogin);
    }
    const hasRegisterErrors = {{ $errors->any() && session('register_errors') ? 'true' : 'false' }};
    
    // Initialize semua fungsi
    setupRealTimeValidation();
    setupFormSubmission();
    updateSubmitButtons();
        setupMobileMenu();
    
    // Setup profile dropdowns (desktop & mobile)
    setupProfileDropdowns();
    // Setup password toggle HANYA SEKALI
    setupPasswordToggle();
    
    // Jika ada error dari register, show modal
    if (hasRegisterErrors) {
        setTimeout(() => {
            showRegisterModal();
        }, 300);
    }

    // Handle guest navigation
    const isGuest = {{ auth()->check() ? 'false' : 'true' }};
    if (isGuest) {
        document.querySelectorAll('nav button[onclick*="showLoginModal"]').forEach(btn => {
            btn.addEventListener('click', showLoginModal);
        });
    }

// Di bagian DOMContentLoaded, GANTI event listeners ini:
document.getElementById('loginModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLoginModal();
    }
});

document.getElementById('registerModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRegisterModal();
    }
});

    // Close modal dengan Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLoginModal();
            closeRegisterModal();
            if (profileDropdown && !profileDropdown.classList.contains('hidden')) {
                toggleProfileDropdown();
            }
        }
    });
});
</script>