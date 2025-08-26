<!-- resources/views/arus_kas/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-money-bill-wave me-2"></i> Laporan Arus Kas</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addArusKasModal">
                <i class="fas fa-plus me-2"></i> Tambah Data
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="filterBulan" class="form-label">Filter Bulan</label>
                    <select class="form-select" id="filterBulan">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterTahun" class="form-label">Filter Tahun</label>
                    <select class="form-select" id="filterTahun">
                        <option value="">Semua Tahun</option>
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterJenis" class="form-label">Filter Jenis</label>
                    <select class="form-select" id="filterJenis">
                        <option value="">Semua Jenis</option>
                        <option value="operasi" {{ request('jenis') == 'operasi' ? 'selected' : '' }}>Operasi</option>
                        <option value="investasi" {{ request('jenis') == 'investasi' ? 'selected' : '' }}>Investasi</option>
                        <option value="pendanaan" {{ request('jenis') == 'pendanaan' ? 'selected' : '' }}>Pendanaan</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">Tanggal</th>
                            <th width="15%">Jenis</th>
                            <th width="15%">Jumlah</th>
                            <th width="15%">Transaksi</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arus_kas as $item)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                            <td>
                                <span class="badge
                                    @if($item->jenis == 'operasi') bg-primary
                                    @elseif($item->jenis == 'investasi') bg-info
                                    @else bg-warning text-dark
                                    @endif">
                                    {{ ucfirst($item->jenis) }}
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($item->jumlah, 2) }}</td>
                            <td>#{{ $item->transaksi_id }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    data-id="{{ $item->id }}"
                                    data-tanggal="{{ $item->tanggal }}"
                                    data-jenis="{{ $item->jenis }}"
                                    data-jumlah="{{ $item->jumlah }}"
                                    data-transaksi_id="{{ $item->transaksi_id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $item->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Arus Kas Modal -->
<div class="modal fade" id="addArusKasModal" tabindex="-1" aria-labelledby="addArusKasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('arus_kas.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addArusKasModalLabel">Tambah Data Arus Kas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis" name="jenis" required>
                            <option value="operasi">Operasi</option>
                            <option value="investasi">Investasi</option>
                            <option value="pendanaan">Pendanaan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="transaksi_id" class="form-label">Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select" id="transaksi_id" name="transaksi_id" required>
                            @foreach($transaksis as $transaksi)
                            <option value="{{ $transaksi->id }}">#{{ $transaksi->id }} - {{ ucfirst($transaksi->jenis) }}</option>
                            @endforeach
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Filter table
        function filterTable() {
            var bulan = $('#filterBulan').val();
            var tahun = $('#filterTahun').val();
            var jenis = $('#filterJenis').val();

            if (bulan || tahun || jenis) {
                var url = "{{ route('arus_kas.index') }}?";
                if (bulan) url += "bulan=" + bulan + "&";
                if (tahun) url += "tahun=" + tahun + "&";
                if (jenis) url += "jenis=" + jenis;

                window.location.href = url;
            } else {
                window.location.href = "{{ route('arus_kas.index') }}";
            }
        }

        $('#filterBulan, #filterTahun, #filterJenis').change(filterTable);
    });
</script>
@endpush
@endsection
