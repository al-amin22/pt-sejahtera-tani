@extends('staff.absensi.index')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-calendar-check me-2"></i> Detail Absensi</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('staff.absensi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
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

    <!-- Card Detail Absensi -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Absensi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Tanggal:</div>
                        <div class="col-sm-8">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d F Y') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Hari:</div>
                        <div class="col-sm-8">{{ \Carbon\Carbon::parse($absensi->tanggal)->isoFormat('dddd') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Total Karyawan:</div>
                        <div class="col-sm-8">{{ $absensi->absensiKaryawan->count() }} orang</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 fw-bold">Status Kehadiran:</div>
                        <div class="col-sm-8">
                            @php
                            $hadir = $absensi->absensiKaryawan->where('status', 'hadir')->count();
                            $tidakHadir = $absensi->absensiKaryawan->where('status', 'tidak hadir')->count();
                            @endphp
                            <span class="badge bg-success me-1">Hadir: {{ $hadir }}</span>
                            <span class="badge bg-danger me-1">Tidak Hadir: {{ $tidakHadir }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Files -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-images me-2"></i>Berkas Absensi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Video:</div>
                        <div class="col-sm-8">
                            @if($absensi->video)
                            <div class="mb-2">
                                <video controls width="100%" class="rounded">
                                    <source src="{{ asset($absensi->video) }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutaran video.
                                </video>
                            </div>
                            <a href="{{ asset($absensi->video) }}" download class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Unduh Video
                            </a>
                            @else
                            <span class="text-muted">Tidak ada video</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 fw-bold">Foto:</div>
                        <div class="col-sm-8">
                            @if($absensi->foto)
                            <div class="mb-2">
                                <img src="{{ asset($absensi->foto) }}" alt="Foto Absensi" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                            <a href="{{ asset($absensi->foto) }}" download class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Unduh Foto
                            </a>
                            @else
                            <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Daftar Karyawan -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Daftar Kehadiran Karyawan</h5>
                </div>
                <div class="card-body">
                    @if($absensi->absensiKaryawan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Karyawan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Jam Masuk</th>
                                    <th scope="col">Jam Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absensi->absensiKaryawan as $index => $absen)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $absen->karyawan->nama ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge
                                                @if($absen->status == 'Hadir') bg-success
                                                @elseif($absen->status == 'Izin') bg-info
                                                @elseif($absen->status == 'Sakit') bg-warning
                                                @elseif($absen->status == 'Alpha') bg-danger
                                                @else bg-secondary
                                                @endif">
                                            {{ $absen->status }}
                                        </span>
                                    </td>
                                    <td>{{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') : '-' }}</td>
                                    <td>{{ $absen->jam_keluar ? \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i') : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>Belum ada data kehadiran karyawan untuk absensi ini.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    /* Mobile optimization */
    @media (max-width: 768px) {
        .row .col-md-6 {
            margin-bottom: 1.5rem;
        }

        .fw-bold {
            margin-bottom: 0.5rem;
        }

        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .d-md-flex {
            flex-direction: column;
        }
    }
</style>
@endpush