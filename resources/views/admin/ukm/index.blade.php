@extends('layouts.app')

@section('title', 'Data UKM')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="flex flex-col md:flex-row justify-between items-center mb-8">
  <h3 class="text-2xl font-semibold text-gray-800 mb-4 md:mb-0">📋 Daftar UKM</h3>
  <a href="{{ route('admin.ukm.create') }}"
     class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2 rounded-lg shadow transition-all duration-300 transform hover:scale-105">
    ➕ Tambah UKM
  </a>
</div>

@if(session('success'))
  <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
  </div>
@endif

<div class="overflow-x-auto bg-white shadow-lg rounded-2xl border border-gray-100">
  <table class="min-w-full text-sm text-left text-gray-700">
    <thead class="bg-indigo-600 text-white text-sm uppercase">
      <tr>
        <th class="py-3 px-4 rounded-tl-2xl">No</th>
        <th class="py-3 px-4">Nama</th>
        <th class="py-3 px-4">Kategori</th>
        <th class="py-3 px-4">Logo</th>
        <th class="py-3 px-4">Deskripsi</th>
        <th class="py-3 px-4 rounded-tr-2xl text-center">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
      @foreach($ukms as $i => $ukm)
        <tr class="hover:bg-indigo-50 transition-all duration-150">
          <td class="py-3 px-4 font-medium text-gray-800">{{ $i + 1 }}</td>
          <td class="py-3 px-4">{{ $ukm->nama }}</td>
          <td class="py-3 px-4">{{ $ukm->kategori ?? '-' }}</td>
          <td class="py-3 px-4">
            @if($ukm->logo)
              <img src="{{ asset('storage/'.$ukm->logo) }}" class="w-14 h-14 object-cover rounded-lg shadow-sm border border-gray-200" alt="Logo {{ $ukm->nama }}">
            @else
              <div class="w-14 h-14 flex items-center justify-center bg-gray-100 text-gray-400 rounded-lg">–</div>
            @endif
          </td>
          <td class="py-3 px-4 text-gray-600">{{ Str::limit($ukm->deskripsi, 80) }}</td>
          <td class="py-3 px-4 flex flex-wrap gap-2 justify-center">
            <a href="{{ route('admin.ukm.edit', $ukm->id) }}"
               class="px-3 py-1.5 bg-amber-400 hover:bg-amber-500 text-white rounded-lg font-medium text-xs shadow-sm transition-all duration-200">
               ✏️ Edit
            </a>
            <form action="{{ route('admin.ukm.destroy', $ukm->id) }}" method="POST" onsubmit="return confirm('Hapus UKM ini?')">
              @csrf @method('DELETE')
              <button type="submit"
                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium text-xs shadow-sm transition-all duration-200">
                🗑️ Hapus
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @if($ukms->isEmpty())
    <div class="text-center text-gray-500 py-8">
      <p>Belum ada data UKM yang tersedia.</p>
    </div>
  @endif
</div>
@endsection
