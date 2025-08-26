@extends('staff.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-user-check me-2"></i> Daftar Absensi Karyawan</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAbsensiKaryawanModal">
                <i class="fas fa-plus me-2"></i> Tambah Absensi Karyawan
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

    <!-- Tabel Data Absensi Karyawan -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="absensiKaryawanTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Absensi</th>
                            <th scope="col">Karyawan</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensiKaryawans as $absensiKaryawan)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $absensiKaryawan->absensi->tanggal ?? '-' }}</td>
                            <td>{{ $absensiKaryawan->karyawan->nama ?? '-' }}</td>
                            <td>
                                <span class="badge
                                    @if($absensiKaryawan->status == 'hadir') bg-success
                                    @elseif($absensiKaryawan->status == 'tidak hadir') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    {{ $absensiKaryawan->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $absensiKaryawan->id }}" data-bs-toggle="modal" data-bs-target="#editAbsensiKaryawanModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('staff.absensi_karyawan.destroy', $absensiKaryawan->id) }}" method="POST" class="d-inline">
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

<!-- Modal Tambah Absensi Karyawan -->
<div class="modal fade" id="addAbsensiKaryawanModal" tabindex="-1" aria-labelledby="addAbsensiKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAbsensiKaryawanModalLabel">Tambah Data Absensi Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('staff.absensi_karyawan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="absensi_id" class="form-label">Absensi</label>
                        <select class="form-select" id="absensi_id" name="absensi_id" required>
                            <option value="" selected disabled>Pilih Absensi</option>
                            @foreach($absensis as $absensi)
                            <option value="{{ $absensi->id }}">Tanggal {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="karyawan_id" class="form-label">Karyawan</label>
                        <select class="form-select" id="karyawan_id" name="karyawan_id" required>
                            <option value="" selected disabled>Pilih Karyawan</option>
                            @foreach($karyawans as $karyawan)
                            <option value="{{ $karyawan->id }}">Nama : {{ $karyawan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" selected disabled>Pilih Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="tidak hadir">Tidak Hadir</option>
                        </select>
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

<!-- Modal Edit Absensi Karyawan -->
<div class="modal fade" id="editAbsensiKaryawanModal" tabindex="-1" aria-labelledby="editAbsensiKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAbsensiKaryawanModalLabel">Edit Data Absensi Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_absensi_id" class="form-label">Absensi</label>
                        <select class="form-select" id="edit_absensi_id" name="absensi_id" required>
                            @foreach($absensis as $absensi)
                            <option value="{{ $absensi->id }}">Tanggal : {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_karyawan_id" class="form-label">Karyawan</label>
                        <select class="form-select" id="edit_karyawan_id" name="karyawan_id" required>
                            @foreach($karyawans as $karyawan)
                            <option value="{{ $karyawan->id }}">Nama : {{ $karyawan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="hadir">Hadir</option>
                            <option value="tidak hadir">Tidak Hadir</option>
                        </select>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handler untuk tombol edit
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var url = "{{ route('staff.absensi_karyawan.data', ':id') }}".replace(':id', id);

            $.get(url, function(data) {
                $('#editForm').attr('action', "{{ route('staff.absensi_karyawan.update', ':id') }}".replace(':id', id));
                $('#edit_absensi_id').val(data.absensi_id);
                $('#edit_karyawan_id').val(data.karyawan_id);
                $('#edit_status').val(data.status);
            });
        });

        // Konfirmasi hapus
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus data absensi karyawan ini?')) {
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

    /* Badge styling */
    .badge {
        font-size: 0.85em;
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

        h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush
