@extends('mahasiswa.mahasiswa')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show">
        {{ session('info') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card" style="margin-top: 80px;">
        <div class="card-header text-black d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detail Pekerjaan</h4>
            <a href="{{ route('jobs.browse') }}" class="btn btn-sm btn-light">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <!-- Bagian Gambar Pekerjaan -->
                    @if($job->gambar)
                    <div class="mb-4 text-center">
                        <img src="{{ asset('storage/'.$job->gambar) }}" alt="{{ $job->title }}"
                            class="img-fluid rounded mb-3"
                            style="max-height: 500px; max-width: 500%; object-fit: contain; margin: 0 auto; display: block;">
                    </div>
                    @else
                    <div class="bg-light rounded mb-4 d-flex align-items-center justify-content-center"
                        style="height: 200px; width: 80%; margin: 0 auto;">
                        <i class="ri-briefcase-line fs-1 text-muted"></i>
                    </div>
                    @endif

                    <h3>{{ $job->title }}</h3>
                    <h5 class="text-primary">
                        <a href="{{ url('/company' . $job->company_id) }}" class="text-primary text-decoration-none">
                            {{ $job->company->name }}
                        </a>
                    </h5>
                    <p class="mb-1"><i class="fas fa-map-marker-alt mr-2"></i>{{ $job->location }}</p>
                    <p class="mb-3"><i class="fas fa-money-bill-wave mr-2"></i>{{ $job->formatted_salary_range }}</p>

                    @if($job->deadline)
                    <div class="alert alert-warning">
                        <i class="fas fa-calendar-alt mr-1"></i> Batas Akhir Lamaran:
                        {{ \Carbon\Carbon::parse($job->deadline)->format('d M Y') }}
                    </div>
                    @endif

                    <div class="mt-4">
                        <h5>Deskripsi Pekerjaan</h5>
                        <div class="card p-3 bg-light">
                            <ul>
                                @foreach(explode("\n", $job->description) as $point)
                                @if(trim($point))
                                <li>{{ trim($point) }}</li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @if($job->requirements)
                    <div class="mt-4">
                        <h5>Persyaratan</h5>
                        <div class="card p-3 bg-light">
                            <ul>
                                @foreach(explode("\n", $job->requirements) as $point)
                                @if(trim($point))
                                <li>{{ trim($point) }}</li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header text-black">
                            <h5 class="mb-0">Posisi Lamaran</h5>
                        </div>
                        <div class="card-body">
                            <!-- Logo Perusahaan -->
                            @if($job->company->logo)
                            <div class="text-center mb-3">
                                <img src="{{ asset('storage/'.$job->company->logo) }}"
                                    alt="{{ $job->company->name }} logo" class="img-fluid rounded-circle"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            @endif

                            <p>Tertarik dengan peluang kerja ini? Klik di bawah untuk mengirimkan lamaran Anda.</p>

                            @if($alreadyApplied)
                            <button class="btn btn-secondary btn-block" disabled>Sudah Melamar</button>
                            <p class="text-success mt-2"><i class="fas fa-check-circle"></i> Anda telah melamar untuk
                                posisi ini.</p>
                            @else
                            <button type="button" class="btn btn-success"
                                onclick="document.getElementById('applyJobModal-{{ $job->id }}').style.display='block'">
                                Lamar Sekarang
                            </button>
                            @endif

                            <hr>
                            <div class="d-flex justify-content-between">
                                <p class="text-muted mb-0"><small>Diposting
                                        {{ $job->created_at->diffForHumans() }}</small></p>
                                @if($job->gambar)
                                <a href="{{ asset('storage/'.$job->gambar) }}" target="_blank" class="text-muted"
                                    title="Lihat gambar pekerjaan secara penuh">
                                    <i class="fas fa-image"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info Perusahaan -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Tentang Perusahaan</h5>
                        </div>
                        <div class="card-body">
                            @if($job->company->logo)
                            <img src="{{ asset('storage/'.$job->company->logo) }}" alt="{{ $job->company->name }} logo"
                                class="img-fluid rounded mb-3" style="max-height: 80px;">
                            @endif
                            <h6>{{ $job->company->name }}</h6>
                            @if($job->company->description)
                            <p class="text-muted small">{{ Str::limit($job->company->description, 150) }}</p>
                            @endif
                            <a href="{{ route('company.profile', $job->company_id) }}"
                                class="btn btn-sm btn-outline-primary">
                                Lihat Profil Perusahaan
                            </a>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lamar Pekerjaan - HTML/CSS Standar -->
    <!-- Modal Lamar Pekerjaan -->
    <div id="applyJobModal-{{ $job->id }}" class="modal"
        style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4);">
        <div class="modal-dialog" style="margin:10% auto; width:90%; max-width:500px;">
            <div class="modal-content" style="background-color:#fff; border-radius:5px; padding:20px;">
                <div class="modal-header"
                    style="border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:20px;">
                    <h5 class="modal-title">Lamar untuk {{ $job->title }}</h5>
                    <span onclick="document.getElementById('applyJobModal-{{ $job->id }}').style.display='none'"
                        style="float:right; cursor:pointer; font-size:20px;">&times;</span>
                </div>
                <div class="modal-body">
                    <form action="{{ route('applyJob.store', $job->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @foreach($job->documentRequirements as $requirement)
                        <div class="mb-3">
                            <label for="document-{{ $requirement->id }}" class="form-label">
                                {{ $requirement->document_name }}
                                @if($requirement->is_required)
                                <span class="text-danger">*</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" id="document-{{ $requirement->id }}"
                                name="documents[{{ $requirement->id }}]" accept=".pdf" @if($requirement->is_required)
                            required @endif>
                            @if($requirement->description)
                            <small class="text-muted">{{ $requirement->description }}</small>
                            @endif
                        </div>
                        @endforeach

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
