@extends('layouts.app')

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
                                <!-- Tombol Lihat -->
                                <button class="btn btn-sm btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewAbsensiModal{{ $absensi->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editAbsensiModal{{ $absensi->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('absensi.destroy', $absensi->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')">
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
            <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data">
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
<div class="modal fade" id="editAbsensiModal{{ $absensi->id }}" tabindex="-1" aria-labelledby="editAbsensiModalLabel{{ $absensi->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('absensi.update', $absensi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal{{ $absensi->id }}" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal{{ $absensi->id }}" name="tanggal" value="{{ $absensi->tanggal }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="video{{ $absensi->id }}" class="form-label">Video</label>
                        <input type="file" class="form-control" id="video{{ $absensi->id }}" name="video" accept="video/mp4,video/mov,video/avi">
                        @if($absensi->video)
                        <div class="form-text">Video saat ini: <a href="{{ asset($absensi->video) }}" target="_blank">Lihat Video</a></div>
                        @else
                        <div class="form-text">Belum ada video yang diunggah</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="foto{{ $absensi->id }}" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto{{ $absensi->id }}" name="foto" accept="image/jpeg,image/png,image/jpg">
                        @if($absensi->foto)
                        <div class="form-text">Foto saat ini: <a href="{{ asset($absensi->foto) }}" target="_blank">Lihat Foto</a></div>
                        @else
                        <div class="form-text">Belum ada foto yang diunggah</div>
                        @endif
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
<div class="modal fade" id="viewAbsensiModal{{ $absensi->id }}" tabindex="-1" aria-labelledby="viewAbsensiModalLabel{{ $absensi->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Data Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Tanggal:</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Video:</div>
                    <div class="col-md-8">
                        @if($absensi->video)
                        <a href="{{ asset($absensi->video) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-video"></i> Lihat Video
                        </a>
                        @else
                        <span class="text-muted">Tidak ada video</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 fw-bold">Foto:</div>
                    <div class="col-md-8">
                        @if($absensi->foto)
                        <a href="{{ asset($absensi->foto) }}" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-image"></i> Lihat Foto
                        </a>
                        @else
                        <span class="text-muted">Tidak ada foto</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
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