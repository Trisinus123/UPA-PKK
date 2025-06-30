@extends('layouts.perusahaan')

@section('title', 'Tambah Lowongan Kerja')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-black d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Tambah Lowongan Pekerjaan</h5>
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Judul Pekerjaan <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        value="{{ old('title') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Kategori Pekerjaan <span
                                            class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control select2-category"
                                        required>
                                        <option value="" disabled selected>Nama kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->nama_category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="location" id="location"
                                    value="{{ old('location') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="salary_min" class="form-label">Gaji Minimum (Rp)</label>
                                    <input type="number" class="form-control" name="salary_min" id="salary_min"
                                        min="0" value="{{ old('salary_min') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="salary_max" class="form-label">Gaji Maksimum (Rp)</label>
                                    <input type="number" class="form-control" name="salary_max" id="salary_max"
                                        min="0" value="{{ old('salary_max') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deadline" class="form-label">Batas Waktu Pendaftaran</label>
                                <input type="date" class="form-control" name="deadline" id="deadline"
                                    value="{{ old('deadline') }}" min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Pekerjaan <span
                                        class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="requirements" class="form-label">Persyaratan</label>
                                <textarea name="requirements" id="requirements" class="form-control" rows="5">{{ old('requirements') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="gambar" class="form-label">Upload Gambar Pekerjaan (opsional)</label>
                                <input class="form-control" type="file" id="gambar" name="gambar"
                                    accept="image/png, image/jpeg">
                                <small class="text-muted">Maks. 2MB (PNG, JPG, JPEG)</small>
                                @error('gambar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Persyaratan Dokumen</label>
                                <div id="document-requirements-container">
                                    <div class="document-requirement mb-2">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <input type="text" name="document_requirements[0][name]"
                                                    class="form-control" placeholder="Nama Dokumen (e.g., CV)">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="document_requirements[0][description]"
                                                    class="form-control" placeholder="Deskripsi (optional)">
                                            </div>
                                            <div class="col-md-2">
                                                <select name="document_requirements[0][required]" class="form-select">
                                                    <option value="1">Required</option>
                                                    <option value="0">Optional</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger remove-document">×</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-document-requirement"
                                    class="btn btn-sm btn-secondary mt-2">
                                    Tambah
                                </button>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-sm text-white"
                                    style="background-color: #0b3558;">simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/4qe0mjui6u4vskf28moxop4rryys5p525f5rrkqzjcx3xit1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 with enhanced search for category
            $('.select2-category').select2({
                placeholder: "Cari kategori",
                allowClear: true,
                width: '100%',
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        return "Kategori tidak ditemukan. Coba kata kunci lain.";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    inputTooShort: function(args) {
                        return "Ketik minimal " + args.minimum + " karakter";
                    }
                },
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }

                    var term = params.term.toLowerCase();
                    var text = data.text.toLowerCase();

                    if (text.indexOf(term) > -1) {
                        return data;
                    }

                    return null;
                }
            });

            // Highlight if there's an error
            @if ($errors->has('category_id'))
                $('.select2-category').next('.select2-container').css('border', '1px solid #dc3545');
            @endif

            // TinyMCE for description
            tinymce.init({
                selector: '#description',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                menubar: false,
                height: 300,
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });

            // TinyMCE for requirements
            tinymce.init({
                selector: '#requirements',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                menubar: false,
                height: 300,
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });
        });

        // Document requirements dynamic fields
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('document-requirements-container');
            const addButton = document.getElementById('add-document-requirement');
            let counter = 1;

            addButton.addEventListener('click', function() {
                const newRequirement = document.createElement('div');
                newRequirement.className = 'document-requirement mb-2';
                newRequirement.innerHTML = `
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="document_requirements[${counter}][name]"
                                   class="form-control" placeholder="Nama Dokumen (e.g., CV)">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="document_requirements[${counter}][description]"
                                   class="form-control" placeholder="Deskripsi (optional)">
                        </div>
                        <div class="col-md-2">
                            <select name="document_requirements[${counter}][required]" class="form-select">
                                <option value="1">Required</option>
                                <option value="0">Optional</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-document">×</button>
                        </div>
                    </div>
                `;
                container.appendChild(newRequirement);
                counter++;
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-document')) {
                    e.target.closest('.document-requirement').remove();
                }
            });
        });
    </script>

    <style>
        /* Improved Select2 styling for better search experience */
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px 10px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #86b7fe;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default .select2-results__option--highlighted {
            background-color: #0b3558;
            color: white;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: #f8f9fa;
            color: #333;
        }

        .select2-container--open .select2-dropdown {
            border-color: #86b7fe;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .select2-search--dropdown {
            padding: 10px;
            background: #f8f9fa;
        }

        .select2-search__field {
            width: 100% !important;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        .select2-results__option {
            padding: 8px 12px;
        }

        .is-invalid+.select2-container--default .select2-selection--single {
            border-color: #dc3545;
        }
    </style>
@endsection
