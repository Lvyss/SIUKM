@extends('layouts.app')

@section('title', 'Edit UKM')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 transition-transform duration-300 hover:-translate-y-1">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">✏️ Edit UKM</h2>

    <form action="{{ route('admin.ukm.update', $ukm->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Nama -->
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Nama UKM</label>
            <input type="text" name="nama" value="{{ $ukm->nama }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
        </div>

        <!-- Kategori -->
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
            <input type="text" name="kategori" value="{{ $ukm->kategori }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition">{{ $ukm->deskripsi }}</textarea>
        </div>

        <!-- Logo -->
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Logo (opsional)</label>
            @if($ukm->logo)
                <div class="mb-3">
                    <img src="{{ asset('storage/'.$ukm->logo) }}" alt="Logo UKM" class="w-24 h-24 object-cover rounded-lg shadow">
                </div>
            @endif
            <input type="file" name="logo"
                class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
        </div>

        <!-- Tombol -->
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('admin.ukm.index') }}"
                class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">⬅ Kembali</a>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 active:scale-95 transition">
                💾 Update UKM
            </button>
        </div>
    </form>
</div>
@endsection
