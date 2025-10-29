<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🌱 SIUKM - @yield('title', 'Dashboard')</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font (Google Poppins) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      font-family: 'Poppins', sans-serif;
      scroll-behavior: smooth;
    }

    /* Animasi fade in */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    main {
      animation: fadeIn 0.7s ease-out;
    }

    /* Animasi navbar saat scroll */
    .scrolled {
      backdrop-filter: blur(14px);
      background-color: rgba(255, 255, 255, 0.7) !important;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    /* Transisi lembut untuk navbar */
    nav {
      transition: all 0.3s ease-in-out;
    }
  </style>
</head>

<body class="bg-gradient-to-b from-indigo-50 to-white text-gray-800 min-h-screen">

  <!-- 🌱 Navbar -->
  <nav id="navbar" class="fixed w-full top-0 left-0 z-50 bg-white/60 backdrop-blur-lg border-b border-gray-200 shadow-sm transition-all">
    <div class="max-w-6xl mx-auto flex justify-between items-center px-6 py-4">
      <a href="/" class="text-2xl font-semibold text-indigo-600 hover:text-indigo-700 transition">
        🌱 SIUKM
      </a>
      <div class="space-x-6">
        <a href="/" class="text-gray-700 hover:text-indigo-600 font-medium transition">Home</a>
        <a href="/admin/ukm" class="text-gray-700 hover:text-indigo-600 font-medium transition">Admin</a>
      </div>
    </div>
  </nav>

  <!-- ✨ Main Content -->
  <main class="max-w-6xl mx-auto pt-28 px-6">
    @yield('content')
  </main>

  <!-- 🌈 Footer -->
  <footer class="mt-16 py-8 text-center border-t border-gray-200 text-gray-500 text-sm">
    <p>© {{ date('Y') }} SIUKM — Sistem Informasi Unit Kegiatan Mahasiswa</p>
  </footer>

  <!-- Navbar Scroll Effect -->
  <script>
    window.addEventListener('scroll', function() {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 30) navbar.classList.add('scrolled');
      else navbar.classList.remove('scrolled');
    });
  </script>
</body>
</html>
