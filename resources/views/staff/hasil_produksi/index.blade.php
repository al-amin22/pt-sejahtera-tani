@extends('staff.layouts.app')

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

    @if($absensi)
    <!-- Ringkasan Produksi -->
    <div class="row mb-4" id="production-section">
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
                                        {{ number_format($total, 2) }} Kg
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
    </div>

    <!-- Daftar Karyawan Hadir -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Karyawan Hadir</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($karyawanHadir as $kh)
                <div class="col-md-4 mb-2">
                    <div class="d-flex align-items-center p-2 border rounded">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <strong>{{ $kh->karyawan->nama }}</strong>
                            <div class="text-muted small">{{ $kh->karyawan->jobdesk }}</div>
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

    <!-- Detail Produksi -->
    <div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Detail Hasil Produksi</h5>
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
    </div>

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
                                <option value="Hasil Tumbukan">Hasil Tumbukan</option>
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