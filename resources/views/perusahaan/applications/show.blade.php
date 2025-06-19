@extends('layouts.perusahaan')

@section('title', 'Lamaran Pekerjaan - ' . $job->title)

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">{{ $job->title }}</h2>
        <p class="text-secondary fs-5 mb-1">{{ $job->company->name }}</p>
        <p class="text-muted">{{ $applications->count() }} {{ Str::plural('Lamaran', $applications->count()) }} Diterima</p>
    </div>

    @if($applications->isEmpty())
        <div class="alert alert-info text-center rounded-4 py-4 shadow-sm">
            <i class="fas fa-info-circle me-2 fs-5"></i> Belum ada lamaran yang dikirimkan.
        </div>
    @else
        <div class="d-flex flex-column align-items-center gap-4">
            @foreach($applications as $application)
            <div class="card rounded-4 shadow-sm border-0 p-4 w-100" style="max-width: 720px;">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mb-1 fw-semibold">{{ $application->student->name }}</h4>
                        <small class="text-muted">
                            Dilamar {{ $application->created_at->diffForHumans() }} 
                            ({{ $application->created_at->format('d M Y') }})
                        </small>
                    </div>
                    <span class="badge rounded-pill px-3 py-2 fs-6 
                        {{ $application->status === 'accepted' ? 'bg-success' : 
                           ($application->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        {{ $application->status === 'accepted' ? 'Diterima' : 
                           ($application->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                    </span>
                </div>

                {{-- Kontak --}}
                <div class="mb-3">
                    <h6 class="text-muted fw-semibold mb-2"><i class="fas fa-user me-2"></i>Informasi Kontak</h6>
                    <div class="ps-2">
                        <p class="mb-1"><strong>Email:</strong> {{ $application->student->email }}</p>
                        @if($application->student->no_hp)
                        <p><strong>No. HP:</strong> {{ $application->student->no_hp }}</p>
                        @endif
                    </div>
                </div>

                {{-- Dokumen --}}
                <div class="mb-3">
                    <h6 class="text-muted fw-semibold mb-2"><i class="fas fa-file-alt me-2 text-danger"></i>Dokumen</h6>
                    @if($application->documents->count() > 0)
                        <ul class="list-unstyled ps-2 mb-0">
                            @foreach($application->documents as $document)
                            <li class="mb-2">
                                <a href="{{ asset('storage/' . $document->file_path) }}" 
                                   target="_blank" 
                                   class="text-decoration-none fw-medium">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>{{ $document->requirement->document_name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted ps-2">Tidak ada dokumen yang dikirimkan.</p>
                    @endif
                </div>

                {{-- Kontak WhatsApp --}}
                @if($application->status === 'accepted' && $application->student->no_hp)
                <div class="text-end">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $application->student->no_hp) }}"
                       target="_blank"
                       class="btn btn-sm btn-outline-success rounded-pill px-4">
                        <i class="fab fa-whatsapp me-2"></i>Hubungi via WhatsApp
                    </a>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Custom CSS --}}
<style>
    .card {
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    h4, h6 {
        font-weight: 600;
    }

    .badge {
        font-size: 0.9rem;
    }
</style>
@endsection
