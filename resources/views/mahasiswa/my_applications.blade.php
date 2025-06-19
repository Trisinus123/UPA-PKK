@extends('mahasiswa.mahasiswa')

@section('title', 'Lamaran Saya')

@section('content')
<div class="container-fluid mt-5" style="margin-top: 200px;">
    <h4 class="mb-3">Lamaran Pekerjaan</h4>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            @if($applications->count() > 0)
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Posisi</th>
                                <th>Perusahaan</th>
                                <th>Tanggal Lamar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                @php
                                    $statusClasses = [
                                        'pending' => 'warning',
                                        'reviewing' => 'info',
                                        'rejected' => 'danger',
                                        'interview' => 'primary',
                                        'accepted' => 'success',
                                    ];
                                    
                                    $statusText = [
                                        'pending' => 'Menunggu',
                                        'reviewing' => 'Ditinjau',
                                        'interview' => 'Wawancara',
                                        'rejected' => 'Ditolak',
                                        'accepted' => 'Diterima',
                                    ];
                                    
                                    $status = $application->status;
                                    $badgeColor = $statusClasses[$status] ?? 'secondary';
                                    $displayText = $statusText[$status] ?? $status;
                                @endphp
                                <tr>
                                    <td class="fw-medium">{{ $application->job->title }}</td>
                                    <td>{{ $application->job->company->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($application->created_at)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $badgeColor }} text-uppercase">{{ $displayText }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('jobs.show', $application->job->id) }}" class="btn btn-sm btn-info">
                                            <i class="ri-eye-line"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $applications->links('pagination::bootstrap-5') }}
                    </div>

            @else
                <div class="alert alert-info d-flex align-items-center mt-3" role="alert">
                    <i class="ri-information-line me-2 fs-5"></i>
                    <div>
                        Kamu belum melamar pekerjaan apapun.
                        <a href="{{ route('jobs.browse') }}" class="alert-link">Lihat lowongan kerja</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
