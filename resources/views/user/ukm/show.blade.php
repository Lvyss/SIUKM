@extends('layouts.app')
@section('title', $ukm->nama)

@section('content')
<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <div class="d-flex align-items-center mb-3">
      @if($ukm->logo)
        <img src="{{ asset('storage/'.$ukm->logo) }}" width="100" class="rounded me-3">
      @endif
      <div>
        <h3>{{ $ukm->nama }}</h3>
        <p class="text-muted">{{ $ukm->kategori }}</p>
      </div>
    </div>

    <hr>
    <h5>Deskripsi:</h5>
    <p>{{ $ukm->deskripsi ?? 'Belum ada deskripsi lengkap.' }}</p>

    <a href="/" class="btn btn-secondary mt-3">← Kembali</a>
  </div>
</div>
@endsection
