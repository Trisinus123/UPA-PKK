@extends('layouts.perusahaan')

@section('title', 'Lamaran Pekerjaan')

@section('content')
@component('components.breadcrumb')
@slot('title') Lamaran Pekerjaan @endslot
@slot('li_1') Dashboard @endslot
@slot('li_2') Lamaran @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card-body">
            @if($applications->isEmpty())
            <div class="alert alert-info bg-info bg-opacity-10 border-0 text-center py-4">
                <i class="ri-information-line fs-3 text-info"></i>
                <h5 class="text-info mt-2">Belum Ada Lamaran</h5>
                <p class="mb-0">Belum ada lamaran yang dikirimkan.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Judul Pekerjaan</th>
                            <th>Pelamar</th>
                            <th>Status</th>
                            <th>Dilamar Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td>{{ $application->job->title ?? '-' }}</td>
                            <td>{{ $application->student->name ?? '-' }}</td>
                            <td>
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
                                $statusClass = $statusClasses[strtolower($application->status)] ?? 'secondary';
                                $statusText = $statusText[strtolower($application->status)] ??
                                ucfirst($application->status);
                                @endphp
                                <span
                                    class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} rounded-pill">
                                    <i class="fas fa-circle me-1 small text-{{ $statusClass }}"></i>
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td>{{ $application->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="hstack gap-2">
                                    <!-- Tombol Lihat -->
                                    <a href="{{ route('applications.show', $application->id) }}"
                                       class="btn btn-light btn-icon btn-sm shadow-sm"
                                       title="Lihat Lamaran"
                                       data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="ri-eye-line fs-5" style="color: #0ac7dc;"></i> <!-- Biru terang -->
                                    </a>

                                    <!-- Tombol Edit -->
                                    <button class="btn btn-light btn-icon btn-sm shadow-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#statusModal"
                                            data-application-id="{{ $application->id }}"
                                            data-current-status="{{ $application->status }}"
                                            title="Ubah Status"
                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="ri-edit-2-line fs-5" style="color: #edae00;"></i> <!-- Kuning terang -->
                                    </button>
                                </div>
                            </td>

                           </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Ubah Status -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Ubah Status Lamaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="statusForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="application_id" id="modalApplicationId">
                    <div class="mb-3">
                        <label for="statusSelect" class="form-label">Pilih Status</label>
                        <select class="form-select" id="statusSelect" name="status">
                            <option value="pending">Menunggu</option>
                            <option value="reviewing">Ditinjau</option>
                            <option value="rejected">Ditolak</option>
                            <option value="interview">Wawancara</option>
                            <option value="accepted">Diterima</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Perbarui Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
    });

    document.addEventListener('DOMContentLoaded', function () {
        const statusModal = document.getElementById('statusModal');
        if (statusModal) {
            statusModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const applicationId = button.getAttribute('data-application-id');
                const currentStatus = button.getAttribute('data-current-status');

                const modalTitle = statusModal.querySelector('.modal-title');
                const modalApplicationId = statusModal.querySelector('#modalApplicationId');
                const statusSelect = statusModal.querySelector('#statusSelect');
                const form = statusModal.querySelector('#statusForm');

                modalTitle.textContent = `Ubah Status Lamaran #${applicationId}`;
                modalApplicationId.value = applicationId;
                statusSelect.value = currentStatus;

                form.action =
                    "{{ route('applications.updateStatus', ['application' => 'PLACEHOLDER']) }}"
                    .replace('PLACEHOLDER', applicationId);
            });
        }
    });

</script>
@endsection
