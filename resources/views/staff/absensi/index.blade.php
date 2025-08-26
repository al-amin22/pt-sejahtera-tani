@extends('staff.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-calendar-check me-2"></i> Daftar Absensi</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAbsensiModal">
                <i class="fas fa-plus me-2"></i> Tambah Absensi
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Tabel Data Absensi -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="absensiTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Video</th>
                            <th scope="col">Foto</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensis as $absensi)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                @if($absensi->video)
                                <a href="{{ asset($absensi->video) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-video me-1"></i> Lihat Video
                                </a>
                                @else
                                <span class="text-muted">Tidak ada video</span>
                                @endif
                            </td>
                            <td>
                                @if($absensi->foto)
                                <a href="{{ asset($absensi->foto) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-image me-1"></i> Lihat Foto
                                </a>
                                @else
                                <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('staff.absensi.show', $absensi->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $absensi->id }}" data-bs-toggle="modal" data-bs-target="#editAbsensiModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('staff.absensi.destroy', $absensi->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Absensi -->
<div class="modal fade" id="addAbsensiModal" tabindex="-1" aria-labelledby="addAbsensiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAbsensiModalLabel">Tambah Data Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('staff.absensi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="video" class="form-label">Video Pekerjaan Max 1 Menit</label>
                        <input type="file" class="form-control" id="video" name="video" accept="video/mp4,video/mov,video/avi">
                        <div class="form-text">Unggah video absensi (opsional)</div>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto (jpg, jpeg, png)</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                        <div class="form-text">Unggah foto absensi (opsional)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Absensi -->
<div class="modal fade" id="editAbsensiModal" tabindex="-1" aria-labelledby="editAbsensiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAbsensiModalLabel">Edit Data Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('staff.absensi.update', $absensi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_tanggal" class="form-label">Tanggal Tidak Dapat Di Ubah</label>
                        <input type="date" value="{{ old('tanggal', $absensiGetTanggal->first()->tanggal ?? '') }}" class="form-control" id="edit_tanggal" name="tanggal" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_video" class="form-label">Video</label>
                        <input type="file" class="form-control" id="edit_video" name="video" accept="video/mp4,video/mov,video/avi">
                        <div class="form-text" id="current_video">Unggah video baru untuk mengganti yang lama</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="edit_foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                        <div class="form-text" id="current_foto">Unggah foto baru untuk mengganti yang lama</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Lihat Absensi -->
<div class="modal fade" id="viewAbsensiModal" tabindex="-1" aria-labelledby="viewAbsensiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAbsensiModalLabel">Detail Data Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Tanggal:</div>
                    <div class="col-md-8" id="view_tanggal"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Video:</div>
                    <div class="col-md-8" id="view_video"></div>
                </div>
                <div class="row">
                    <div class="col-md-4 fw-bold">Foto:</div>
                    <div class="col-md-8" id="view_foto"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handler untuk tombol edit
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var url = "{{ route('staff.absensi.show', ':id') }}".replace(':id', id);

            $.get(url, function(data) {
                $('#editForm').attr('action', "{{ route('staff.absensi.update', ':id') }}".replace(':id', id));
                $('#edit_tanggal').val(data.tanggal);

                if (data.video) {
                    $('#current_video').html('Video saat ini: <a href="' + data.video + '" target="_blank">Lihat Video</a>');
                } else {
                    $('#current_video').html('Belum ada video yang diunggah');
                }

                if (data.foto) {
                    $('#current_foto').html('Foto saat ini: <a href="' + data.foto + '" target="_blank">Lihat Foto</a>');
                } else {
                    $('#current_foto').html('Belum ada foto yang diunggah');
                }
            });
        });

        // Handler untuk tombol lihat
        $('.view-btn').click(function() {
            var id = $(this).data('id');
            var url = "{{ route('staff.absensi.show', ':id') }}".replace(':id', id);

            $.get(url, function(data) {
                $('#view_tanggal').text(new Date(data.tanggal).toLocaleDateString('id-ID'));

                if (data.video) {
                    $('#view_video').html('<a href="' + data.video + '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-video me-1"></i> Lihat Video</a>');
                } else {
                    $('#view_video').html('<span class="text-muted">Tidak ada video</span>');
                }

                if (data.foto) {
                    $('#view_foto').html('<a href="' + data.foto + '" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-image me-1"></i> Lihat Foto</a>');
                } else {
                    $('#view_foto').html('<span class="text-muted">Tidak ada foto</span>');
                }
            });
        });

        // Konfirmasi hapus
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus data absensi ini?')) {
                $(this).parent().submit();
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Responsive table */
    .table-responsive {
        overflow-x: auto;
    }

    /* Button spacing */
    .btn-sm {
        margin: 0 2px;
    }

    /* Mobile optimization */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .table th,
        .table td {
            padding: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush
