@extends('layouts.app')

@section('content')
<style>
    .card-shadow {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .table th {
        border-top: none;
        font-weight: 600;
    }

    .bg-china {
        background-color: #ffcccc;
    }

    .bg-jambi {
        background-color: #ccffcc;
    }

    .bg-aceh {
        background-color: #ccccff;
    }

    .flow-chart {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .flow-node {
        text-align: center;
        padding: 15px;
        border-radius: 8px;
        flex: 1;
        margin: 0 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .flow-arrow {
        font-size: 24px;
        color: #6c757d;
    }

    .saldo-positif {
        color: #198754;
        font-weight: bold;
    }

    .saldo-negatif {
        color: #dc3545;
        font-weight: bold;
    }

    .filter-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="mb-0">
                <i class="fas fa-exchange-alt me-2"></i> Daftar Transaksi Bulanan
            </h4>
            <p class="text-muted">Sistem Pelacakan Alur Dana Perusahaan</p>
        </div>
    </div>

    <!-- Visualisasi Alur Dana -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-shadow border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Visualisasi Alur Dana</h5>
                </div>
                <div class="card-body">
                    <div class="flow-chart">
                        <div class="flow-node bg-china">
                            <h6>China</h6>
                            <div class="fw-bold">Rp {{ number_format($danaDariChina, 0, ',', '.') }}</div>
                            <small>Investor</small>
                        </div>
                        <div class="flow-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="flow-node bg-jambi">
                            <h6>Jambi</h6>
                            <div class="fw-bold">Rp {{ number_format($saldoJambi, 0, ',', '.') }}</div>
                            <small>Saldo Jambi</small>
                        </div>
                        <div class="flow-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="flow-node bg-aceh">
                            <h6>Aceh</h6>
                            <div class="fw-bold">Rp {{ number_format($saldoAceh, 0, ',', '.') }}</div>
                            <small>Saldo Aceh</small>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <small class="text-muted d-block">Pengeluaran di Jambi</small>
                                <span class="fw-bold text-danger">Rp {{ number_format($operasionalJambi, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <small class="text-muted d-block">Transfer Jambi ke Aceh</small>
                                <span class="fw-bold text-info">Rp {{ number_format($transferAceh, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <small class="text-muted d-block">Pengeluaran di Aceh</small>
                                <span class="fw-bold text-danger">Rp {{ number_format($operasionalAceh, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-section">
                <form method="GET" action="{{ route('transaksi.index') }}">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="tahunFilter" class="form-label">Tahun</label>
                            <select class="form-select" id="tahunFilter" name="tahun">
                                <option value="">Pilih Tahun</option>
                                @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="bulanFilter" class="form-label">Bulan</label>
                            <select class="form-select" id="bulanFilter" name="bulan">
                                <option value="">Semua Bulan</option>
                                @foreach(range(1, 12) as $month)
                                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="rekeningFilter" class="form-label">Rekening</label>
                            <select class="form-select" id="rekeningFilter" name="rekening_id">
                                <option value="">Semua Rekening</option>
                                @foreach($rekening as $r)
                                <option value="{{ $r->id }}" {{ $rekeningId == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100" id="terapkanFilter">
                                <i class="fas fa-filter me-2"></i>Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Card List -->
    <div class="col-auto">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransaksiModal">
            <i class="fas fa-plus me-2"></i> Tambah Transaksi
        </button>
    </div>
    <div class="card card-shadow border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <!-- <th>Rekening</th> -->
                            <th>Transaksi China-Jambi</th>
                            <th>Transaksi Jambi-Aceh</th>
                            <th>Transaksi Jambi-Lainnya</th>
                            <th>Penginput</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksiPerBulan as $bulan => $transaksiBulan)
                        <tr class="table-secondary">
                            <td colspan="8" class="text-center fw-bold">
                                {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }} -
                                Pemasukan: {{ number_format($saldoBulanan[$bulan]['pemasukan'] ?? 0, 0, ',', '.') }} -
                                Pengeluaran: {{ number_format($saldoBulanan[$bulan]['pengeluaran'] ?? 0, 0, ',', '.') }} -
                                Saldo Akhir: {{ number_format($saldoBulanan[$bulan]['saldo'] ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                        @foreach($transaksiBulan as $item)
                        @php
                        $isPemasukan = $item->total < 0;
                            $jenis=$isPemasukan ? 'Pemasukan' : 'Pengeluaran' ;
                            $badgeClass=$isPemasukan ? 'bg-success' : 'bg-danger' ;
                            $icon=$isPemasukan ? 'fa-arrow-down' : 'fa-arrow-up' ;
                            $textClass=$isPemasukan ? 'saldo-positif' : 'saldo-negatif' ;
                            $nominal=$isPemasukan ? abs($item->total) : $item->total;

                            // Ambil informasi rekening dari detail transaksi pertama
                            $dariRekening = $item->details->first()->dariRekening->nama ?? '-';
                            $keRekening = $item->details->first()->keRekening->nama ?? 'Operasional';

                            $badgeColors = [
                            'China' => 'bg-china text-dark',
                            'Jambi' => 'bg-jambi text-dark',
                            'Aceh' => 'bg-aceh text-dark'
                            ];

                            $dariColor = $badgeColors[$dariRekening] ?? 'bg-secondary';
                            $keColor = $badgeColors[$keRekening] ?? 'bg-secondary';
                            @endphp

                            <tr>
                                <td>{{ $item->tanggal_transaksi ? \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        <i class="fas {{ $icon }} me-1"></i>
                                        {{ $jenis }}
                                    </span>
                                </td>
                                <td class="{{ $textClass }}">
                                    {{ $isPemasukan ? '+' : '-' }}Rp {{ number_format($nominal, 0, ',', '.') }}
                                </td>
                                <!-- <td>
                                    <span class="badge {{ $dariColor }}">{{ $dariRekening }}</span> →
                                    <span class="badge {{ $keColor }}">{{ $keRekening }}</span>
                                </td> -->
                                <td>
                                    @php
                                    $chinaToJambi = $item->details
                                    ->where('dari_rekening_id', 1)
                                    ->where('ke_rekening_id', 2)
                                    ->sum('subtotal');
                                    @endphp

                                    @if($chinaToJambi > 0)
                                    <span class="text-primary fw-bold">
                                        Rp {{ number_format($chinaToJambi, 0, ',', '.') }}
                                    </span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @php
                                    $jambiToAceh = $item->details
                                    ->where('dari_rekening_id', 2)
                                    ->where('ke_rekening_id', 3)
                                    ->sum('subtotal');
                                    @endphp

                                    @if($jambiToAceh > 0)
                                    <span class="text-primary fw-bold">
                                        Rp {{ number_format($jambiToAceh, 0, ',', '.') }}
                                    </span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @php
                                    $jambiToLainnya = $item->details
                                    ->where('dari_rekening_id', 2)
                                    ->where('ke_rekening_id', 4)
                                    ->sum('subtotal');
                                    @endphp

                                    @if($jambiToLainnya > 0)
                                    <span class="text-primary fw-bold">
                                        Rp {{ number_format($jambiToLainnya, 0, ',', '.') }}
                                    </span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $item->user->name ?? 'Admin' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('transaksi.show', $item->id) }}" class="btn btn-info" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-{{ $item->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Saldo Bulanan -->
                            <tr>
                                <td></td>
                            </tr>
                            @endforeach
                    </tbody>
                    <!-- Total Keseluruhan -->
                    <tfoot class="table-primary">
                        <tr>
                            <th colspan="2">Total Keseluruhan</th>
                            <th colspan="3">
                                <span class="badge bg-success me-2">
                                    <i class="fas fa-arrow-down"></i>
                                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                </span>
                                <span class="badge bg-danger">
                                    <i class="fas fa-arrow-up"></i>
                                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                                </span>
                            </th>
                            <th>Saldo Akhir</th>
                            <th class="{{ $saldoAkhir >= 0 ? 'saldo-positif' : 'saldo-negatif' }} fw-bold">
                                <i class="fas fa-wallet me-1"></i>
                                {{ $saldoAkhir >= 0 ? '+' : '' }}Rp {{ number_format(abs($saldoAkhir), 0, ',', '.') }}
                                <br>
                                <small>
                                    {{ $saldoAkhir >= 0 ? 'Sisa uang' : 'Saldo Kurang' }}
                                </small>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Legenda -->
            <!-- <div class="mt-4 p-3 bg-light rounded">
                <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Legenda Sistem Saldo:</h6>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <span class="badge bg-success me-2"><i class="fas fa-arrow-down"></i></span>
                        <span>Pemasukan</span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="badge bg-danger me-2"><i class="fas fa-arrow-up"></i></span>
                        <span>Pengeluaran</span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="saldo-positif me-2"><i class="fas fa-wallet"></i></span>
                        <span>Saldo Sisa</span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="saldo-negatif me-2"><i class="fas fa-exclamation-triangle"></i></span>
                        <span>Saldo Kurang</span>
                    </div>
                </div>
                <div class="row mt-2">
                    @foreach($rekening as $r)
                    <div class="col-md-3 mb-2">
                        @php
                        $badgeColors = [
                        'China' => 'bg-china text-dark',
                        'Jambi' => 'bg-jambi text-dark',
                        'Aceh' => 'bg-aceh text-dark'
                        ];
                        $colorClass = $badgeColors[$r->nama] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $colorClass }} me-2">{{ $r->nama }}</span>
                        <span>Rekening {{ $r->nama }}</span>
                    </div>
                    @endforeach
                    <div class="col-md-3 mb-2">
                        <span class="badge bg-secondary me-2">Operasional</span>
                        <span>Pengeluaran Operasional</span>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>

<div class="modal fade" id="addTransaksiModal" tabindex="-1" aria-labelledby="addTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransaksiModalLabel">
                        <i class="fas fa-plus me-2"></i> Tambah Transaksi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">

                    <!-- Tanggal & Keterangan -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control" name="tanggal_transaksi" required>
                        </div>
                        <div class="col-md-8">
                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                            <input type="text" class="form-control" name="keterangan" placeholder="Contoh: Pembelian barang dari pemasok">
                        </div>
                    </div>

                    <!-- Detail Transaksi -->
                    <h6 class="fw-bold mb-2">Detail Transaksi</h6>

                    {{-- Scroll area khusus tabel --}}
                    <div class="overflow-auto vh-50">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="detailTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>Dari Rekening</th>
                                        <th>Ke Rekening</th>
                                        <th>Keterangan</th>
                                        <th>Referensi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="detailBody">
                                    <tr>
                                        <td><input type="text" name="detail[0][nama_barang]" class="form-control" required></td>
                                        <td>
                                            <select name="detail[0][jenis]" class="form-select" required>
                                                <option value="pemasukan">Pemasukan</option>
                                                <option value="pengeluaran">Pengeluaran</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="detail[0][jumlah]" step="0.01" class="form-control jumlah" required></td>
                                        <td><input type="text" name="detail[0][satuan]" class="form-control"></td>
                                        <td><input type="number" step="0.01" name="detail[0][harga]" class="form-control harga" required></td>
                                        <td><input type="text" class="form-control subtotal" readonly></td>

                                        <td>
                                            <select name="detail[0][dari_rekening_id]" class="form-select" required>
                                                @foreach($rekening as $rek)
                                                <option value="{{ $rek->id }}">{{ $rek->nama ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="detail[0][ke_rekening_id]" class="form-select" required>
                                                @foreach($rekening as $rek)
                                                <option value="{{ $rek->id }}">{{ $rek->nama ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="detail[0][keterangan]" class="form-control"></td>
                                        <td><input type="file" name="detail[0][referensi]" class="form-control"></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success btn-sm mt-2" id="addRow">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </button>

                    <!-- Total -->
                    <div class="mt-3 text-end">
                        <h5>Total: <span id="grandTotal">Rp. 0</span></h5>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>


@foreach($transaksi as $item)
<!-- Edit Modal -->
<div class="modal fade" id="editModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('transaksi.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel-{{ $item->id }}">
                        <i class="fas fa-edit me-2"></i> Edit Transaksi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">

                    <!-- Tanggal & Keterangan -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control" name="tanggal_transaksi"
                                value="{{ $item->tanggal_transaksi ? \Carbon\Carbon::parse($item->tanggal_transaksi)->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-8">
                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                            <input type="text" class="form-control" name="keterangan"
                                value="{{ $item->keterangan }}" placeholder="Contoh: Pembelian barang dari pemasok">
                        </div>
                    </div>

                    <!-- Detail Transaksi -->
                    <h6 class="fw-bold mb-2">Detail Transaksi</h6>

                    {{-- Scroll area khusus tabel --}}
                    <div class="overflow-auto vh-50">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="editDetailTable-{{ $item->id }}">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>Dari Rekening</th>
                                        <th>Ke Rekening</th>
                                        <th>Keterangan</th>
                                        <th>Referensi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="editDetailBody-{{ $item->id }}">
                                    @foreach($item->detailTransaksi as $index => $detail)
                                    <tr data-id="{{ $detail->id }}">
                                        <td>
                                            <input type="hidden" name="detail[{{ $index }}][id]" value="{{ $detail->id }}">
                                            <input type="text" name="detail[{{ $index }}][nama_barang]" class="form-control" value="{{ $detail->nama_barang }}" required>
                                        </td>
                                        <td>
                                            <select name="detail[{{ $index }}][jenis]" class="form-select" required>
                                                <option value="pemasukan" {{ $detail->jenis == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                                <option value="pengeluaran" {{ $detail->jenis == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                            </select>
                                        </td>
                                        <td><input type="number" step="0.01" name="detail[{{ $index }}][jumlah]" class="form-control jumlah" value="{{ $detail->jumlah }}" required></td>
                                        <td><input type="text" name="detail[{{ $index }}][satuan]" class="form-control" value="{{ $detail->satuan }}"></td>
                                        <td><input type="number" step="0.01" name="detail[{{ $index }}][harga]" class="form-control harga" value="{{ $detail->harga }}" required></td>
                                        <td><input type="text" class="form-control subtotal" value="{{ number_format($detail->subtotal, 0, ',', '.') }}" readonly></td>


                                        <td>
                                            <select name="detail[{{ $index }}][dari_rekening_id]" class="form-select" required>
                                                @foreach($rekening as $rek)
                                                <option value="{{ $rek->id }}" {{ $detail->dari_rekening_id == $rek->id ? 'selected' : '' }}>{{ $rek->nama ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="detail[{{ $index }}][ke_rekening_id]" class="form-select" required>
                                                @foreach($rekening as $rek)
                                                <option value="{{ $rek->id }}" {{ $detail->ke_rekening_id == $rek->id ? 'selected' : '' }}>{{ $rek->nama }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="detail[{{ $index }}][keterangan]" class="form-control" value="{{ $detail->keterangan }}"></td>
                                        <td>
                                            @if($detail->referensi)
                                            <div class="d-flex align-items-center">
                                                <a href="{{ asset($detail->referensi) }}" target="_blank" class="me-2">
                                                    <i class="fas fa-file-alt"></i> Lihat
                                                </a>
                                                <input type="file" name="detail[{{ $index }}][referensi]" class="form-control form-control-sm">
                                            </div>
                                            @else
                                            <input type="file" name="detail[{{ $index }}][referensi]" class="form-control">
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success btn-sm mt-2 addEditRow" data-target="{{ $item->id }}">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </button>

                    <!-- Total -->
                    <div class="mt-3 text-end">
                        <h5>Total: <span id="editGrandTotal-{{ $item->id }}">Rp {{ number_format($item->total, 0, ',', '.') }}</span></h5>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel-{{ $item->id }}">
                        <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
                    <ul>
                        <li><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d-m-Y') }}</li>
                        <li><strong>Keterangan:</strong> {{ $item->keterangan ?? '-' }}</li>
                        <li><strong>Total:</strong> Rp {{ number_format($item->total, 0, ',', '.') }}</li>
                    </ul>
                    <p class="text-danger mb-0"><i class="fas fa-info-circle"></i> Data yang dihapus tidak bisa dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    // Tambah row di edit modal
    $(document).on('click', '.addEditRow', function() {
        let targetId = $(this).data('target');
        let rowCount = $('#editDetailBody-' + targetId + ' tr').length;

        let newRow = `
        <tr>
            <td><input type="text" name="detail[${rowCount}][nama_barang]" class="form-control" required></td>
            <td>
                <select name="detail[${rowCount}][jenis]" class="form-select jenis" required>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </td>
            <td><input type="number" step="0.01" name="detail[${rowCount}][jumlah]" class="form-control jumlah" required></td>
            <td><input type="text" name="detail[${rowCount}][satuan]" class="form-control"></td>
            <td><input type="number" step="0.01" name="detail[${rowCount}][harga]" class="form-control harga" required></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td>
                <select name="detail[${rowCount}][dari_rekening_id]" class="form-select" required>
                    @foreach($rekening as $rek)
                        <option value="{{ $rek->id }}">{{ $rek->nama ?? '-' }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="detail[${rowCount}][ke_rekening_id]" class="form-select" required>
                    @foreach($rekening as $rek)
                        <option value="{{ $rek->id }}">{{ $rek->nama ?? '-' }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="detail[${rowCount}][keterangan]" class="form-control"></td>
            <td><input type="file" name="detail[${rowCount}][referensi]" class="form-control"></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeRow">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
        $('#editDetailBody-' + targetId).append(newRow);
    });

    // Hitung subtotal per row (edit modal)
    $(document).on('input change', '.jumlah, .harga, .jenis', function() {
        let row = $(this).closest('tr');
        let jumlah = parseFloat(row.find('.jumlah').val()) || 0;
        let harga = parseFloat(row.find('.harga').val()) || 0;
        let jenis = row.find('.jenis').val();
        let subtotal = jumlah * harga;

        // tampilkan angka absolut di kolom subtotal
        row.find('.subtotal').val(subtotal.toLocaleString('id-ID'));

        // update grand total
        let modalId = $(this).closest('.modal').attr('id').replace('editModal-', '');
        calculateEditTotal(modalId);
    });

    // Hitung grand total di edit modal
    function calculateEditTotal(modalId) {
        let total = 0;
        $('#editDetailBody-' + modalId + ' tr').each(function() {
            let jumlah = parseFloat($(this).find('.jumlah').val()) || 0;
            let harga = parseFloat($(this).find('.harga').val()) || 0;
            let jenis = $(this).find('.jenis').val();
            let subtotal = jumlah * harga;

            if (jenis === 'pemasukan') {
                total -= subtotal; // kurangi jika pemasukan
            } else {
                total += subtotal; // tambah jika pengeluaranAF
            }
        });
        $('#editGrandTotal-' + modalId).text("Rp " + total.toLocaleString('id-ID'));
    }

    // Hapus row di edit modal
    // Hapus row di edit modal
    $(document).on('click', '.removeRow', function() {
        let row = $(this).closest('tr');
        let modal = $(this).closest('.modal');

        // cek apakah row punya data-id (row lama dari DB)
        let deletedId = row.data('id');
        if (deletedId) {
            // tambahkan hidden input agar server tahu ID mana yang dihapus
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_detail_ids[]',
                value: deletedId
            }).appendTo(modal.find('form'));
        }

        // hapus row dari DOM
        row.remove();

        // hitung ulang total
        hitungTotal(modal);
    });


    // ======================= Tambah Transaksi =======================
    $(document).ready(function() {
        // Saat modal tambah dibuka
        $('#addTransaksiModal').on('show.bs.modal', function() {
            let form = $(this).find('form')[0];
            form.reset();
            $('#detailBody').empty();
            addRow('#detailBody');
            $('#grandTotal').text("Rp 0");
        });

        // Saat modal edit ditampilkan → hitung total
        $('.editModal').on('shown.bs.modal', function() {
            hitungTotal($(this));
        });

        // Fungsi tambah row
        function addRow(targetBody) {
            let index = $(targetBody).find('tr').length;
            let newRow = `
        <tr>
            <td><input type="text" name="detail[${index}][nama_barang]" class="form-control" required></td>
            <td>
                <select name="detail[${index}][jenis]" class="form-select jenis" required>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </td>
            <td><input type="number" step="0.01" name="detail[${index}][jumlah]" class="form-control jumlah" required></td>
            <td><input type="text" name="detail[${index}][satuan]" class="form-control"></td>
            <td><input type="number" step="0.01" name="detail[${index}][harga]" class="form-control harga" required></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td>
                <select name="detail[${index}][dari_rekening_id]" class="form-select" required>
                    @foreach($rekening as $rek)
                        <option value="{{ $rek->id }}">{{ $rek->nama ?? '-' }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="detail[${index}][ke_rekening_id]" class="form-select" required>
                    @foreach($rekening as $rek)
                        <option value="{{ $rek->id }}">{{ $rek->nama ?? '-' }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="detail[${index}][keterangan]" class="form-control"></td>
            <td><input type="file" name="detail[${index}][referensi]" class="form-control"></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeRow">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
            $(targetBody).append(newRow);
        }

        // Tambah baris di modal tambah
        $(document).on('click', '.addRow', function() {
            addRow('#detailBody');
        });

        // Tambah baris di modal edit
        $(document).on('click', '.addEditRow', function() {
            let id = $(this).data('target');
            addRow('#editDetailBody-' + id);
        });

        // Hapus baris
        $(document).on('click', '.removeRow', function() {
            let modal = $(this).closest('.modal');
            $(this).closest('tr').remove();
            hitungTotal(modal);
        });

        // Hitung ulang saat jumlah/harga/jenis berubah
        $(document).on('input change', '.jumlah, .harga, .jenis', function() {
            let modal = $(this).closest('.modal');
            hitungTotal(modal);
        });

        // Fungsi hitung total
        function hitungTotal(modal) {
            let grandTotal = 0;
            modal.find('tbody tr').each(function() {
                let jumlah = parseFloat($(this).find('.jumlah').val()) || 0;
                let harga = parseFloat($(this).find('.harga').val()) || 0;
                let jenis = $(this).find('.jenis').val();
                let subtotal = jumlah * harga;

                $(this).find('.subtotal').val(subtotal.toLocaleString("id-ID"));

                if (jenis === 'pemasukan') {
                    grandTotal -= subtotal;
                } else {
                    grandTotal += subtotal;
                }
            });
            modal.find('#grandTotal').text("Rp " + grandTotal.toLocaleString("id-ID"));
        }
        // ================= Tambah row di modal TAMBAH =================
        function addRowTambah() {
            let index = $('#detailBody tr').length;
            let newRow = `
        <tr>
            <td><input type="text" name="detail[${index}][nama_barang]" class="form-control" required></td>
            <td>
                <select name="detail[${index}][jenis]" class="form-select jenis" required>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </td>
            <td><input type="number" step="0.01" name="detail[${index}][jumlah]" class="form-control jumlah" required></td>
            <td><input type="text" name="detail[${index}][satuan]" class="form-control"></td>
            <td><input type="number" step="0.01" name="detail[${index}][harga]" class="form-control harga" required></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td>
                <select name="detail[${index}][dari_rekening_id]" class="form-select" required>
                    @foreach($rekening as $rek)
                        <option value="{{ $rek->id }}">{{ $rek->nama ?? '-' }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="detail[${index}][ke_rekening_id]" class="form-select" required>
                    @foreach($rekening as $rek)
                        <option value="{{ $rek->id }}">{{ $rek->nama ?? '-' }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="detail[${index}][keterangan]" class="form-control"></td>
            <td><input type="file" name="detail[${index}][referensi]" class="form-control"></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeRow">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
            $('#detailBody').append(newRow);
        }

        // tombol tambah baris di modal Tambah
        $(document).on('click', '#addRow', function(e) {
            e.preventDefault();
            addRowTambah();
        });


    });
</script>
<script>
    // Script untuk filter
    document.getElementById('terapkanFilter').addEventListener('click', function() {
        const tahun = document.getElementById('tahunFilter').value;
        const bulan = document.getElementById('bulanFilter').value;
        const rekening = document.getElementById('rekeningFilter').value;

        // Di sini akan ada logika untuk memfilter data
        // Untuk contoh, kita hanya menampilkan alert
        alert(`Filter diterapkan: Tahun ${tahun}, Bulan ${bulan}, Rekening ${rekening}`);

        // Dalam implementasi nyata, ini akan melakukan:
        // 1. AJAX request ke server dengan parameter filter
        // 2. Memperbarui tabel dengan data yang difilter
    });
</script>
@endpush

@push('styles')
<style>
    .table-info {
        background-color: #e3f2fd !important;
        border-left: 4px solid #2196F3;
    }

    .table-primary {
        background-color: #bbdefb !important;
        border-left: 4px solid #1976D2;
    }

    .fw-bold {
        font-weight: 700 !important;
    }

    .fw-semibold {
        font-weight: 600 !important;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(33, 150, 243, 0.04);
    }

    .text-success {
        color: #2e7d32 !important;
    }

    .text-danger {
        color: #c62828 !important;
    }

    .bg-light {
        background-color: #f5f5f5 !important;
    }

    .btn-group-sm>.btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush
@endsection