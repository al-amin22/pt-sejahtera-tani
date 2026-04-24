@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Arus Kas</h4>
            <small class="text-muted">Ringkasan arus dana perusahaan</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addArusKasModal">
            <i class="fas fa-plus me-1"></i> Tambah Data
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body row g-3">
            <div class="col-md-4">
                <label class="form-label">Filter Bulan</label>
                <select class="form-select" id="filterBulan">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter Tahun</label>
                <select class="form-select" id="filterTahun">
                    <option value="">Semua Tahun</option>
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter Jenis</label>
                <select class="form-select" id="filterJenis">
                    <option value="">Semua Jenis</option>
                    <option value="operasi">Operasi</option>
                    <option value="investasi">Investasi</option>
                    <option value="pendanaan">Pendanaan</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle datatable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Transaksi</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arus_kas as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($item->jenis) }}</span></td>
                            <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td>#{{ $item->transaksi_id }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary btn-edit"
                                    data-id="{{ $item->id }}"
                                    data-tanggal="{{ $item->tanggal }}"
                                    data-jenis="{{ $item->jenis }}"
                                    data-jumlah="{{ $item->jumlah }}"
                                    data-transaksi_id="{{ $item->transaksi_id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editArusKasModal">
                                    Edit
                                </button>
                                <form action="{{ route('arus_kas.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addArusKasModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('arus_kas.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Arus Kas</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select class="form-select" name="jenis" required>
                        <option value="operasi">Operasi</option>
                        <option value="investasi">Investasi</option>
                        <option value="pendanaan">Pendanaan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" step="0.01" class="form-control" name="jumlah" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Transaksi</label>
                    <select class="form-select" name="transaksi_id" required>
                        @foreach($transaksis as $transaksi)
                            <option value="{{ $transaksi->id }}">#{{ $transaksi->id }} - {{ $transaksi->tanggal_transaksi }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editArusKasModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="editArusKasForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Arus Kas</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select class="form-select" id="edit_jenis" name="jenis" required>
                        <option value="operasi">Operasi</option>
                        <option value="investasi">Investasi</option>
                        <option value="pendanaan">Pendanaan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" step="0.01" class="form-control" id="edit_jumlah" name="jumlah" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Transaksi</label>
                    <select class="form-select" id="edit_transaksi_id" name="transaksi_id" required>
                        @foreach($transaksis as $transaksi)
                            <option value="{{ $transaksi->id }}">#{{ $transaksi->id }} - {{ $transaksi->tanggal_transaksi }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.btn-edit').forEach(function(button) {
    button.addEventListener('click', function() {
        document.getElementById('editArusKasForm').action = '/admin/arus-kas/' + this.dataset.id;
        document.getElementById('edit_tanggal').value = this.dataset.tanggal;
        document.getElementById('edit_jenis').value = this.dataset.jenis;
        document.getElementById('edit_jumlah').value = this.dataset.jumlah;
        document.getElementById('edit_transaksi_id').value = this.dataset.transaksi_id;
    });
});
</script>
@endpush
@endsection
