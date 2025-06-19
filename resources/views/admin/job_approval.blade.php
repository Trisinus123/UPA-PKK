@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Verifikasi Pekerjaan</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <ul class="nav nav-tabs mb-4" id="jobTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab"
                aria-controls="pending" aria-selected="true">
                Menunggu <span class="badge badge-warning">{{ count($pendingJobs) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved"
                aria-selected="false">
                Disetujui <span class="badge badge-success">{{ count($approvedJobs) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected"
                aria-selected="false">
                Ditolak <span class="badge badge-danger">{{ count($rejectedJobs) }}</span>
            </a>
        </li>
    </ul>

    <div class="tab-content" id="jobTabsContent">
        <!-- Pending Jobs Tab -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            @if(count($pendingJobs) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-white">
                        <tr>
                            <th>Judul Pekerjaan</th>
                            <th>Kategori Pekerjaan</th>
                            <th>Nama Perusahaan</th>
                            <th>Diposting</th>
                            <th>Lokasi</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingJobs as $job)
                        <tr>
                            <td>{{ $job->title }}</td>
                            <td>{{ $job->categoryJob->nama_category ?? 'Tidak ada kategori'}}</td>
                            <td>{{ $job->company->name }}</td>
                            <td>{{ $job->created_at->format('d M Y') }}</td>
                            <td>{{ $job->location }}</td>
                            <td>
                                @if($job->gambar)
                                <img src="{{ asset('storage/'.$job->gambar) }}" alt="{{ $job->title }}"
                                    class="img-thumbnail" style="width: 100px; height: 60px; object-fit: cover;">
                                @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="width: 100px; height: 60px;">
                                    <i class="ri-image-line text-muted" style="font-size: 24px;"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#jobModal{{ $job->id }}">Detail</a>
                                    <form action="{{ route('jobs.update.status', $job->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                    </form>
                                    <form action="{{ route('jobs.update.status', $job->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">Tidak ada pekerjaan yang tertunda untuk disetujui.</div>
            @endif
        </div>

        <!-- Approved Jobs Tab -->
        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
            @if(count($approvedJobs) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-white">
                        <tr>
                            <th>Judul Pekerjaan</th>
                            <th>Kategori</th>
                            <th>Nama Perusahaan</th>
                            <th>Tanggal Disetujui</th>
                            <th>Lokasi</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>
                    <tbody>
                        @foreach($approvedJobs as $job)
                        <tr>
                            <td>{{ $job->title }}</td>
                            <td>{{ $job->categoryJob->nama_category ?? 'Tidak ada kategori' }}</td>
                            <td>{{ $job->company->name }}</td>
                            <td>{{ $job->updated_at->format('d M Y') }}</td>
                            <td>{{ $job->location }}</td>
                            <td>
                                @if($job->gambar)
                                <img src="{{ asset('storage/'.$job->gambar) }}" alt="{{ $job->title }}"
                                    class="img-thumbnail" style="width: 100px; height: 60px; object-fit: cover;">
                                @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="width: 100px; height: 60px;">
                                    <i class="ri-image-line text-muted" style="font-size: 24px;"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                                    data-target="#jobModal{{ $job->id }}">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">Tidak ada pekerjaan yang disetujui.</div>
            @endif
        </div>

        <!-- Rejected Jobs Tab -->
        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
            @if(count($rejectedJobs) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-white">
                        <tr>
                            <th>Judul Pekerjaan</th>
                            <th>Kategori</th>
                            <th>Nama Perusahaan</th>
                            <th>Tanggal Ditolak</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rejectedJobs as $job)
                        <tr>
                            <td>{{ $job->title }}</td>
                            <td>{{ $job->categoryJob->nama_category ?? 'Tidak ada kategori' }}</td>
                            <td>{{ $job->company->name }}</td>
                            <td>{{ $job->updated_at->format('d M Y') }}</td>
                            <td>{{ $job->location }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#jobModal{{ $job->id }}">Detail</a>
                                    <form action="{{ route('jobs.update.status', $job->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">Tidak ada pekerjaan yang ditolak.</div>
            @endif
        </div>
    </div>

    @php
    // Menggabungkan semua collection jobs
    $allJobs = collect()
    ->merge($pendingJobs)
    ->merge($approvedJobs)
    ->merge($rejectedJobs);
    @endphp

    @foreach($allJobs as $job)
    <div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1" role="dialog"
        aria-labelledby="jobModalLabel{{ $job->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="jobModalLabel{{ $job->id }}">{{ $job->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    {{-- Gambar --}}
                    @if($job->gambar)
                    <div class="d-flex justify-content-center mb-3">
                        <img src="{{ asset('storage/'.$job->gambar) }}" alt="{{ $job->title }}" class="img-thumbnail"
                            style="width: 400px; height: 400px; object-fit: cover;">
                    </div>
                    @endif

                    <!-- Informasi Pekerjaan -->
                    <div class="mb-3">
                        <p><strong>Perusahaan:</strong> {{ $job->company->name }}</p>
                        <p><strong>Lokasi:</strong> {{ $job->location }}</p>
                        <p><strong>Kategori Pekerjaan:</strong>
                            {{ $job->categoryJob->nama_category ?? 'Tidak ada kategori' }}</p>
                    </div>
                    <!-- Deskripsi Pekerjaan -->
                    <div class="job-description mb-4">
                        <h5 class="mb-4 fw-semibold text-dark">Deskripsi Pekerjaan</h5>
                        <div class="ps-4">
                            @foreach(explode("\n", $job->description) as $point)
                            @if(trim($point) !== '')
                            <div class="d-flex mb-3" style="line-height: 1.4;">
                                <!-- Tinggi baris diperbesar -->
                                <div class="me-3" style="min-width: 20px;">•</div>
                                <!-- Bullet point dengan spacing konsisten -->
                                <div>{{ trim($point) }}</div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Kualifikasi -->
                    <div class="job-qualifications mb-4">
                        <h5 class="mb-4 fw-semibold text-dark">Persyaratan</h5>
                        <div class="ps-4">
                            @foreach(explode("\n", $job->requirements) as $point)
                            @if(trim($point) !== '')
                            <div class="d-flex mb-3" style="line-height: 1.4;">
                                <!-- Tinggi baris sama dengan deskripsi -->
                                <div class="me-3" style="min-width: 20px;">•</div>
                                <div>{{ trim($point) }}</div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>

                        @if($job->status === 'pending' || $job->status === 'rejected')
                        <form action="{{ route('jobs.update.status', $job->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </form>
                        @endif

                        @if($job->status === 'pending')
                        <form action="{{ route('jobs.update.status', $job->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger">Tolak</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endsection
