@extends('layouts.app')
@section('title', 'Tambah UKM')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-8">
  <h3 class="text-2xl font-semibold text-gray-800 mb-6">🌱 Tambah UKM Baru</h3>

  <form action="{{ route('admin.ukm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf

    <!-- Nama UKM -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Nama UKM <span class="text-red-500">*</span></label>
      <input type="text" name="nama" required
             class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition">
    </div>

    <!-- Kategori -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
      <input type="text" name="kategori"
             class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition">
    </div>

    <!-- Deskripsi -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
      <textarea name="deskripsi" rows="4"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"></textarea>
    </div>

    <!-- Logo Upload -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Logo UKM</label>
      <input type="file" name="logo" id="logoInput"
             class="w-full border border-gray-300 rounded-lg px-4 py-2 file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0 file:text-sm file:font-semibold
                    file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200
                    focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">

      <!-- Preview -->
      <div id="logoPreview" class="mt-4 hidden">
        <p class="text-sm text-gray-500 mb-1">Preview Logo:</p>
        <img id="previewImage" src="#" alt="Preview Logo"
             class="w-40 h-40 object-cover rounded-xl border border-gray-200 shadow-sm">
      </div>
    </div>

    <!-- Tombol -->
    <div class="flex justify-end gap-3 pt-4">
      <a href="{{ route('admin.ukm.index') }}"
         class="px-5 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
        Kembali
      </a>
      <button type="submit"
              class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md transition">
        💾 Simpan
      </button>
    </div>
  </form>
</div>

<!-- Preview JS -->
<script>
  document.getElementById('logoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewContainer = document.getElementById('logoPreview');
    const previewImage = document.getElementById('previewImage');
    if (file) {
      previewContainer.classList.remove('hidden');
      previewImage.src = URL.createObjectURL(file);
    } else {
      previewContainer.classList.add('hidden');
      previewImage.src = '#';
    }
  });
</script>
@endsection
