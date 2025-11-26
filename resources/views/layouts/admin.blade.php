<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SI-UKM</title>
    
    <!-- TAILWIND CSS dari CDN yang reliable -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Fallback jika CDN gagal load
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#64748b',
                    }
                }
            }
        }
        
        // Backup CSS jika Tailwind gagal
        const loadBackupCSS = () => {
            const backupCSS = `
                .flex { display: flex; }
                .hidden { display: none; }
                .bg-blue-800 { background: #1e40af; }
                .text-white { color: white; }
                .p-4 { padding: 1rem; }
                .min-h-screen { min-height: 100vh; }
                .w-64 { width: 16rem; }
            `;
            const style = document.createElement('style');
            style.textContent = backupCSS;
            document.head.appendChild(style);
        };
        
        // Cek apakah Tailwind loaded
        setTimeout(() => {
            if (!document.styleSheets[0] || !document.styleSheets[0].cssRules.length) {
                console.log('Tailwind failed, loading backup CSS');
                loadBackupCSS();
            }
        }, 1000);
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-800 text-white min-h-screen">
            <div class="p-4 border-b border-blue-700">
                <h1 class="text-xl font-bold">SI-UKM Admin</h1>
                <p class="text-sm text-blue-200 mt-1">Welcome, {{ auth()->user()->name }}</p>
            </div>
            
            <nav class="mt-4">
                @php
                    $currentRoute = request()->route()->getName();
                @endphp
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 transition duration-200 {{ str_contains($currentRoute, 'admin.dashboard') ? 'bg-blue-700 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>Dashboard
                </a>
                
                <a href="{{ route('admin.categories.index') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 transition duration-200 {{ str_contains($currentRoute, 'admin.categories') ? 'bg-blue-700 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-tags w-5 mr-3"></i>Kategori
                </a>
                
                <a href="{{ route('admin.ukms.index') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 transition duration-200 {{ str_contains($currentRoute, 'admin.ukms') ? 'bg-blue-700 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i>UKM
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 transition duration-200 {{ str_contains($currentRoute, 'admin.users') ? 'bg-blue-700 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-user-friends w-5 mr-3"></i>Users
                </a>
                
                <a href="{{ route('admin.staff.index') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 transition duration-200 {{ str_contains($currentRoute, 'admin.staff') ? 'bg-blue-700 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-user-tie w-5 mr-3"></i>Staff
                </a>
                
                <a href="{{ route('admin.registrations.index') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 transition duration-200 {{ str_contains($currentRoute, 'admin.registrations') ? 'bg-blue-700 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-clipboard-list w-5 mr-3"></i>Pendaftaran
                </a>
                
{{-- Ganti link Content dengan Events & Feeds terpisah --}}
<a href="{{ route('admin.events.index') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.events.*') ? 'bg-blue-700' : '' }}">
    <i class="fas fa-calendar mr-2"></i>Events
</a>

<a href="{{ route('admin.feeds.index') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.feeds.*') ? 'bg-blue-700' : '' }}">
    <i class="fas fa-newspaper mr-2"></i>Feeds
</a>
                
                <div class="mt-8 pt-4 border-t border-blue-700">
                    <a href="{{ route('dashboard') }}" 
                       class="block py-3 px-4 hover:bg-blue-700 transition duration-200 text-blue-200">
                        <i class="fas fa-arrow-left w-5 mr-3"></i>Back to Main
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full text-left py-3 px-4 hover:bg-red-600 transition duration-200 text-red-200">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <script>
        // Auto hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('[class*="bg-green-500"], [class*="bg-red-500"]');
            flashMessages.forEach(msg => {
                msg.style.opacity = '0';
                msg.style.transition = 'opacity 0.5s ease';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);

        // Simple modal functions
        window.showModal = (modalId) => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        };

        window.hideModal = (modalId) => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };

        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.add('hidden');
            }
        });

        // Ping untuk keep session alive
        setInterval(() => {
            fetch('{{ route('admin.dashboard') }}', { 
                method: 'HEAD',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(err => console.log('Keep-alive ping'));
        }, 300000); // 5 menit

        console.log('Admin layout loaded successfully');
    </script>

    <style>
        /* Custom animations */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Modal styles */
        .modal-overlay {
            background: rgba(0, 0, 0, 0.5);
        }
    </style>

    @yield('scripts')
</body>
</html>