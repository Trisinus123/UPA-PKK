@extends('layouts.admin')

@section('content')

<div class="container">
    {{-- show artikel --}}
    <h2 class="mb-4">Show Artikel</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-3">
        <h4>{{ $artikel->judul_artikel }}</h4>
        {{-- display gambar --}}
        @if($artikel->gambar)
            <img src="{{ asset('storage/gambar_artikel/' . $artikel->gambar) }}" alt="Gambar Artikel" width="300" class="mb-3">
        @endif
        <p>{{ $artikel->deskripsi }}</p>
        <a href="{{ route('artikel.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
