@extends('staff.layouts.app')
<style>
    .card-dashboard {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header-main {
        background: linear-gradient(120deg, #2ecc71, #27ae60);
        padding: 15px 20px;
        border-bottom: none;
    }

    .stat-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #28a745;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }

    .production-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .production-table thead th {
        background-color: #e9ecef;
        border-top: 1px solid #dee2e6;
        font-weight: 600;
    }

    .production-table tbody tr {
        transition: all 0.2s;
    }

    .production-table tbody tr:hover {
        background-color: rgba(40, 167, 69, 0.05);
    }

    .empty-state {
        padding: 40px 0;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }
</style>
@section('content')
<div class="container">
    <!-- Filter tanggal -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Pilih Tanggal Produksi</h5>
        </div>

        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Tanggal Produksi</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Tampilkan
                    </button>
                </div>
                @if($absensi)
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickInputModal">
                        <i class="fas fa-bolt me-1"></i> Input Cepat
                    </button>
                    {{--
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addProductionModal">
                        <i class="fas fa-plus me-1"></i> Input Manual
                    </button>
                    --}}
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Daftar Karyawan Hadir -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Karyawan Hadir</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($karyawanHadir as $kh)
                <div class="col-md-3 col-sm-6 mb-2"> <!-- lebih rapat -->
                    <div class="d-flex align-items-center p-1 border rounded small"> <!-- padding kecil + font kecil -->
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <strong style="font-size: 0.9rem;">{{ $kh->karyawan->nama }}</strong><br>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ $kh->karyawan->jobdesk ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-3">
                    <i class="fas fa-users-slash fa-2x text-muted mb-2"></i>
                    <p class="text-muted">Tidak ada karyawan hadir</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
    <div class="card card-dashboard shadow-sm mb-4">
        <div class="card-header card-header-main text-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="fas fa-industry me-2"></i>Dashboard Produksi</h5>
                <p class="mb-0 mt-1 small opacity-75">Data produksi tanggal {{ $tanggal }}</p>
            </div>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#tambahStokModal">
                <i class="fas fa-plus me-1"></i> Tambah Stok
            </button>
        </div>
        <div class="card-body">
            <!-- Ringkasan Bahan Baku -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-box me-2 text-success"></i>Ringkasan Bahan Baku</h6>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Jernang Bulat Mentah</p>
                                <h4 class="mb-0">{{ number_format($jumlahStokKeseluruhan, 2) }} kg</h4>
                            </div>
                            <div class="bg-success bg-opacity-10 p-2 rounded">
                                <i class="fas fa-boxes text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Jernang Bulat Mentah Terpakai</p>
                                <h4 class="mb-0">{{ number_format($stokTerpakai, 2) }} kg</h4>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-2 rounded">
                                <i class="fas fa-tools text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Sisa Stok</p>
                                <h4 class="mb-0">{{ number_format($jumlahStokTersisa, 2) }} kg</h4>
                            </div>
                            <div class="bg-info bg-opacity-10 p-2 rounded">
                                <i class="fas fa-database text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--rinkasan bahan sudah dijemur -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-seedling me-2 text-success"></i>Ringkasan Bahan Setelah Dijemur</h6>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Bahan Setelah Dijemur</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-success bg-opacity-10 p-2 rounded">
                                <i class="fas fa-leaf text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Bahan Setelah Dijemur Terpakai</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-2 rounded">
                                <i class="fas fa-tools text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Sisa Stok Setelah Dijemur</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-info bg-opacity-10 p-2 rounded">
                                <i class="fas fa-database text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ringkasan bahan ketika penumbukan -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-hammer me-2 text-success"></i>Total Bahan Setelah Penumbukan 0 kg</h6>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Hasil Tepung Penumbukan ke-1</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-success bg-opacity-10 p-2 rounded">
                                <i class="fas fa-hammer text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Hasil Tepung Penumbukan ke-2</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-2 rounded">
                                <i class="fas fa-tools text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Hasil Tepung Penumbukan ke-3</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-info bg-opacity-10 p-2 rounded">
                                <i class="fas fa-database text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Hasil Tepung Penumbukan ke-4</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-secondary bg-opacity-10 p-2 rounded">
                                <i class="fas fa-box text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ringkasan sisa bahan setelah penumbukan -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-recycle me-2 text-success"></i>Sisa Bahan Setelah Penumbukan 0 kg</h6>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Sisa Tepung Setelah Penumbukan ke-1</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-success bg-opacity-10 p-2 rounded">
                                <i class="fas fa-hammer text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Sisa Tepung Setelah Penumbukan ke-2</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-2 rounded">
                                <i class="fas fa-tools text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Sisa Tepung Setelah Penumbukan ke-3</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-info bg-opacity-10 p-2 rounded">
                                <i class="fas fa-database text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Sisa Tepung Setelah Penumbukan ke-4</p>
                                <h4 class="mb-0">0 kg</h4>
                            </div>
                            <div class="bg-secondary bg-opacity-10 p-2 rounded">
                                <i class="fas fa-box text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar untuk Visualisasi Stok -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="bg-light p-3 rounded">
                        <p class="mb-1 small text-muted">Persentase Stok Terpakai</p>
                        <div class="progress" style="height: 12px;">
                            @php
                            $percentage = $jumlahStokKeseluruhan > 0 ? ($stokTerpakai / $jumlahStokKeseluruhan) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $percentage }}%;"
                                aria-valuenow="{{ $percentage }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                                {{ number_format($percentage, 1) }}%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small>0%</small>
                            <small>100%</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Hasil Produksi -->
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0"><i class="fas fa-box-open me-2 text-secondary"></i>Detail Hasil Produksi</h6>
                        <!-- <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addProductionModal">
                            <i class="fas fa-plus me-1"></i> Tambah Produksi
                        </button> -->
                    </div>

                    <div class="table-responsive">
                        <table class="table production-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Hasil</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasilProduksi as $hp)
                                <tr>
                                    <td>
                                        <i class="fas fa-cube me-2 text-primary"></i>
                                        {{ $hp->jenis_hasil }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fs-6">
                                            {{ number_format($hp->jumlah, 2) }}
                                        </span>
                                    </td>
                                    <td>{{ $hp->satuan }}</td>
                                    <td>
                                        <span class="text-muted">{{ $hp->keterangan ?? '-' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <p class="text-muted mb-0">Tidak ada data hasil produksi</p>
                                            <small class="text-muted">Klik "Tambah Produksi" untuk menambahkan data</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">Terakhir diperbarui: {{ date('d M Y H:i') }}</small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">© {{ date('Y') }} Sistem Produksi</small>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Modal Tambah Stok -->
    <div class="modal fade" id="tambahStokModal" tabindex="-1" aria-labelledby="tambahStokModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('staff.hasil_produksi.tambahStok') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="tambahStokModalLabel">
                            <i class="fas fa-box me-2"></i> Tambah Stok Jernang Bulat
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Nama barang fix -->
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <!-- Tampilkan saja ke user -->
                            <input type="text" class="form-control" value="Jernang" disabled>

                            <!-- Input tersembunyi untuk dikirim ke controller -->
                            <input type="hidden" name="nama_barang" value="Jernang">
                        </div>

                        <!-- Input stok -->
                        <div class="mb-3">
                            <label for="stok" class="form-label">Jumlah Stok Masuk (kg)</label>
                            <input type="number" name="stok" id="stok" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @if($absensi)
    <!-- Ringkasan Produksi -->
    <!-- <div class="row mb-4" id="production-section">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h6 class="mb-0"><i class="fas fa-boxes me-2"></i>Ringkasan Produksi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($totalProduksiPerJenis as $jenis => $total)
                        <div class="col-md-3 col-lg-3">
                            <div class="card shadow-sm border-success h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title mb-2">{{ $jenis }}</h6>
                                    <p class="fs-5 fw-bold text-success mb-0">
                                        {{ number_format($total, 2) }} {{$jenis === 'Dedak basah' ? 'Karung' : 'Kg'}}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center mb-0">
                                Belum ada data produksi
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <div class="mt-4 text-center">
                        <p class="mb-0">
                            <strong>Karyawan Hadir:</strong> {{ $jumlahKaryawan }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div> -->

    <!-- Detail Produksi -->
    {{--<div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Detail Hasil Produksi pada tanggal {{ $tanggal }}</h5>
    <div>
        <!-- <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addProductionModal">
                    <i class="fas fa-plus me-1"></i> Tambah
                </button> -->
    </div>
</div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Jenis Hasil</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <!-- <th>Aksi</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($hasilProduksi as $hp)
                <tr>
                    <td>{{ $hp->jenis_hasil }}</td>
                    <td>{{ number_format($hp->jumlah, 2) }}</td>
                    <td>{{ $hp->satuan }}</td>
                    <td>{{ $hp->keterangan ?? '-' }}</td>
                    <!-- <td>
                                    <button class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger ms-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Tidak ada data hasil produksi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>--}}


<!-- Modal Input Cepat -->
<div class="modal fade" id="quickInputModal" tabindex="-1" aria-labelledby="quickInputModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="quickInputModalLabel">
                    <i class="fas fa-bolt me-2"></i>Input Cepat Produksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('staff.hasil_produksi.quickStore') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="absensi_id" value="{{ $absensi->id }}">
                    <p class="text-muted">Data akan diterapkan untuk semua karyawan yang hadir pada tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</p>

                    <div class="mb-3">
                        <label for="jenis_hasil" class="form-label">Jenis Hasil</label>
                        <select class="form-select" id="jenis_hasil" name="jenis_hasil" required>
                            <option value="">-- Pilih Jenis Hasil --</option>
                            <option value="Jumlah Jernang Bulat Mentah yang Dijemur">Jumlah Jernang Bulat Mentah yang Dijemur</option>
                            <option value="Hasil Jernang Bulat Mentah yang Dijemur">Hasil jernang Bulat Mentah Setelah Dijemur</option>
                            <option value="Jumlah yangh Ditumbuk">Jumlah yang Ditumbuk ke-1</option>
                            <option value="Hasil Penumbukan ke-1">Hasil Penumbukan ke-1</option>
                            <option value="Sisa Penumbukan ke-1">Sisa Penumbukan ke-1</option>
                            <option value="Jumlah yang Ditumbuk ke-2">Jumlah yang Ditumbuk ke-2</option>
                            <option value="Hasil Penumbukan ke-2">Hasil Penumbukan ke-2</option>
                            <option value="Sisa Penumbukan ke-2">Sisa Penumbukan ke-2</option>
                            <option value="Jumlah yang Ditumbuk ke-3">Jumlah yang Ditumbuk ke-3</option>
                            <option value="Hasil Penumbukan ke-3">Hasil Penumbukan ke-3</option>
                            <option value="Sisa Penumbukan ke-3">Sisa Penumbukan ke-3</option>
                            <option value="Jumlah yang Ditumbuk ke-4">Jumlah yang Ditumbuk ke-4</option>
                            <option value="Hasil Penumbukan ke-4">Hasil Penumbukan ke-4</option>
                            <option value="Sisa Penumbukan ke-4">Sisa Penumbukan ke-4</option>
                            <option value="Dedak kering kasar">Hasil Dedak kering kasar</option>
                            <option value="Dedak kering halus">Hasil Dedak kering halus</option>
                            <option value="Dedak basah">Hasil Dedak basah</option>
                            <option value="Hasil bola-bola">Hasil bola-bola</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" step="0.01" class="form-control" id="jumlah" name="jumlah" required min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="satuan" class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="satuan" name="satuan" required value="Kg">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"
                        onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini? Data tidak dapat diubah lagi setelah disimpan.')">
                        <i class="fas fa-bolt me-1"></i> Simpan untuk Semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Input Manual -->
<div class="modal fade" id="addProductionModal" tabindex="-1" aria-labelledby="addProductionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addProductionModalLabel">
                    <i class="fas fa-plus me-2"></i>Tambah Data Produksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('staff.hasil_produksi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="absensi_karyawan_id" class="form-label">Pilih Karyawan</label>
                        <select class="form-select" id="absensi_karyawan_id" name="absensi_karyawan_id" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawanHadir as $kh)
                            <option value="{{ $kh->id }}">{{ $kh->karyawan->nama }} ({{ $kh->karyawan->jobdesk }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jenis_hasil" class="form-label">Jenis Hasil</label>
                        <select class="form-select" id="jenis_hasil" name="jenis_hasil" required>
                            <option value="">-- Pilih Jenis Hasil --</option>
                            <option value="Dedak kering kasar">Dedak kering kasar</option>
                            <option value="Dedak kering halus">Dedak kering halus</option>
                            <option value="Dedak basah">Dedak basah</option>
                            <option value="Hasil bola-bola">Hasil bola-bola</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" step="0.01" class="form-control" id="jumlah" name="jumlah" required min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="satuan" class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="satuan" name="satuan" required value="Kg">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@else
<!-- Tidak ada data absensi -->
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">Tidak Ada Data Absensi</h4>
        <p class="text-muted">Tidak ditemukan data absensi untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</p>
    </div>
</div>
@endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll to section if needed
        @if(session('scrollTo'))
        document.getElementById('{{ session('
            scrollTo ') }}').scrollIntoView({
            behavior: 'smooth'
        });
        @endif

        // Auto-focus on first input in modal when shown
        const modals = ['quickInputModal', 'addProductionModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('shown.bs.modal', function() {
                    const firstInput = modal.querySelector('input, select, textarea');
                    if (firstInput) {
                        firstInput.focus();
                    }
                });
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#quickInputModal form');
        const submitBtn = form.querySelector('button[type="submit"]');

        submitBtn.addEventListener('click', function(e) {
            e.preventDefault(); // cegah langsung submit

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data tidak dapat diubah lagi setelah disimpan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

@endsection
