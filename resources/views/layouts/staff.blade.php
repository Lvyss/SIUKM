<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - SI-UKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-green-800 text-white flex flex-col">
            <div class="p-4 border-b border-green-700">
                <h1 class="text-xl font-bold">SI-UKM Staff</h1>
                <p class="text-sm text-green-200 mt-1">Welcome, {{ auth()->user()->name }}</p>
            </div>
            
            <nav class="flex-1 p-4">
                <div class="space-y-2">
                    <a href="{{ route('staff.dashboard') }}" class="flex items-center py-2 px-3 rounded-lg transition {{ request()->routeIs('staff.dashboard') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700' }}">
                        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('staff.ukms.index') }}" class="flex items-center py-2 px-3 rounded-lg transition {{ request()->routeIs('staff.ukms.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700' }}">
                        <i class="fas fa-users w-5 mr-3"></i>
                        My UKM
                    </a>
                    
                    <a href="{{ route('staff.events.index') }}" class="flex items-center py-2 px-3 rounded-lg transition {{ request()->routeIs('staff.events.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700' }}">
                        <i class="fas fa-calendar w-5 mr-3"></i>
                        Events
                    </a>
                    
                    <a href="{{ route('staff.feeds.index') }}" class="flex items-center py-2 px-3 rounded-lg transition {{ request()->routeIs('staff.feeds.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700' }}">
                        <i class="fas fa-newspaper w-5 mr-3"></i>
                        Feeds
                    </a>
                    
                    <a href="{{ route('staff.registrations.index') }}" class="flex items-center py-2 px-3 rounded-lg transition {{ request()->routeIs('staff.registrations.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700' }}">
                        <i class="fas fa-clipboard-list w-5 mr-3"></i>
                        Registrations
                    </a>
                </div>
                
                <div class="mt-8 pt-4 border-t border-green-700">
                    <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-3 rounded-lg text-green-100 hover:bg-green-700 transition">
                        <i class="fas fa-arrow-left w-5 mr-3"></i>
                        Back to Main
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="flex items-center w-full py-2 px-3 rounded-lg text-red-200 hover:bg-red-600 transition">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-in slide-in-from-top duration-500">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-in slide-in-from-top duration-500">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <script>
        // Auto hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.fixed');
            messages.forEach(msg => {
                setTimeout(() => {
                    msg.remove();
                }, 5000);
            });
        });

        // Prevent layout shift by ensuring consistent heights
        function stabilizeLayout() {
            const sidebar = document.querySelector('.flex.h-screen');
            if (sidebar) {
                sidebar.style.minHeight = '100vh';
            }
        }
        
        stabilizeLayout();
    </script>
</body>
</html>