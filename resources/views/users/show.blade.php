@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Detail Pengguna</h3>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Informasi Dasar</h5>
                    <hr>
                    <div class="mb-3">
                        <strong>Nama:</strong> {{ $user->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $user->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Peran:</strong> 
                        <span class="badge {{ $roleBadgeClass }} text-white">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Telepon:</strong> {{ $user->no_hp ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <h5>Informasi Spesifik Peran</h5>
                    <hr>
                    @if($user->role === 'mahasiswa')
                        <div class="mb-3">
                            <strong>NIM:</strong> {{ $user->mahasiswaProfile->nim ?? '-' }}
                        </div>
                    @elseif($user->role === 'perusahaan')
                        <div class="mb-3">
                            <strong>Alamat Perusahaan:</strong> {{ $user->perusahaanProfile->alamat_perusahaan ?? '-' }}
                        </div>
                        <div class="mb-3">
                            <strong>Website:</strong> 
                            @if($user->perusahaanProfile->website ?? false)
                                <a href="{{ $user->perusahaanProfile->website }}" target="_blank">
                                    {{ $user->perusahaanProfile->website }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>Deskripsi:</strong> 
                            {{ $user->perusahaanProfile->deskripsi ?? '-' }}
                        </div>
                    @else
                        <div class="mb-3">
                            <em>Tidak ada informasi tambahan untuk peran admin</em>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembal
                </a>
                <div>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Ubah
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
