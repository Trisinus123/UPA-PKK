    @extends('layouts.admin')

    @section('content')

    <div class="container">
        {{-- Show data perusahaan --}}
        <h2 class="mb-4">Detail Perusahaan</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                @if($perusahaan->foto)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('storage/' . $perusahaan->foto) }}" alt="Foto Perusahaan" width="200" class="img-thumbnail">
                    </div>
                @endif

                <h4 class="card-title">{{ $perusahaan->nama_perusahaan }}</h4>

                <p class="card-text"><strong>Alamat:</strong><br>{{ $perusahaan->alamat_perusahaan }}</p>

                <p class="card-text"><strong>Website:</strong><br>
                    @if($perusahaan->website)
                        <a href="{{ $perusahaan->website }}" target="_blank">{{ $perusahaan->website }}</a>
                    @else
                        <span class="text-muted">Tidak tersedia</span>
                    @endif
                </p>

                <p class="card-text"><strong>Deskripsi:</strong><br>{{ $perusahaan->deskripsi }}</p>

                <a href="{{ route('data-perusahaan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
    @endsection
