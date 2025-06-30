@extends('layouts.perusahaan')

@section('title', 'Edit Lowongan Kerja')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-black d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Edit Lowongan Pekerjaan: {{ $job->title }}</h5>
                    <a href="{{ route('jobs.index') }}" class="btn btn-sm text-white" style="background-color: #0b3558;">
                        Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi Kesalahan:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('jobs.update', $job->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Judul Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $job->title) }}" required>
                            </div>

                            {{-- category_id from categories --}}
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Kategori Pekerjaan <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->nama_category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="location" id="location" value="{{ old('location', $job->location) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="salary_min" class="form-label">Gaji Minimum (Rp)</label>
                                <input type="number" class="form-control" name="salary_min" id="salary_min" min="0" value="{{ old('salary_min', $job->salary_min) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="salary_max" class="form-label">Gaji Maksimum (Rp)</label>
                                <input type="number" class="form-control" name="salary_max" id="salary_max" min="0" value="{{ old('salary_max', $job->salary_max) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deadline" class="form-label">Batas Waktu Pendaftaran</label>
                            <input type="date" class="form-control" name="deadline" id="deadline"
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('deadline', $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('Y-m-d') : '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="6" required>{{ old('description', $job->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Kualifikasi / Persyaratan</label>
                            <textarea name="requirements" id="requirements" class="form-control" rows="5">{{ old('requirements', $job->requirements) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="gambar" class="form-label">Upload Gambar Pekerjaan (opsional)</label>

                            @if($job->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$job->gambar) }}" alt="Gambar Saat Ini"
                                    class="img-thumbnail" style="max-height: 200px;">
                                <div class="form-text">Gambar saat ini</div>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" {{ old('remove_image') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remove_image">Hapus gambar saat ini</label>
                            </div>
                            @endif

                            <input class="form-control" type="file" id="gambar" name="gambar" accept="image/png, image/jpeg">
                            <small class="text-muted">Maks. 2MB (PNG, JPG, JPEG)</small>
                            @error('gambar')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <small>Catatan: Setelah mengedit, lowongan ini akan kembali ke status "pending" dan memerlukan persetujuan admin.</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-sm text-white" style="background-color: #0b3558;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.tiny.cloud/1/4qe0mjui6u4vskf28moxop4rryys5p525f5rrkqzjcx3xit1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#description',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        menubar: false,
        height: 300,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
    
    tinymce.init({
        selector: '#requirements',
        plugins: 'anchor autolink charmap codesample emoticons link lists searchreplace visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        menubar: false,
        height: 200,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    document.getElementById('deadline').min = new Date().toISOString().split('T')[0];
</script>
@endsection
