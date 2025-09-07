@extends('staff.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3 align-items-center">
        <div class="col">
            <h4 class="mb-0">
                <i class="fas fa-exchange-alt me-2"></i> Detail Transaksi
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Daftar Transaksi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Transaksi #{{ $transaksi->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('transaksi.export-pdf', $transaksi->id) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-2"></i> PDF
            </a>
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Transaction Summary Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Tanggal Transaksi</label>
                        <p class="mb-0">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Keterangan</label>
                        <p class="mb-0">{{ $transaksi->keterangan ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Dibuat Oleh</label>
                        <p class="mb-0">{{ $transaksi->user->name ?? 'Admin' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Total Transaksi</label>
                        <h5 class="mb-0 text-primary">
                            {{ $transaksi->total < 0 ? '+' : '-' }} Rp {{ number_format(abs($transaksi->total), 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Transaction Details Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-list-ul me-2"></i> Detail Item Transaksi
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                            <th>Rekening</th>
                            <th>Mata Uang</th>
                            <!-- <th>Keterangan</th> -->
                            <th>Referensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detailTransaksi as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->nama_barang }}</td>
                            <td>
                                <span class="badge {{ $detail->jenis == 'pemasukan' ? 'bg-success' : 'bg-danger text-white' }}">
                                    {{ $detail->jenis == 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-end">
                                {{ $detail->jenis == 'pemasukan' ? '+' : '-' }}
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                            <td>{{ $detail->dariRekening->nama }} -> {{ $detail->keRekening->nama }}</td>
                            <td>{{ $detail->mataUang->nama ?? '-' }}</td>
                            <!-- <td>{{ $detail->keterangan ?? '-' }}</td> -->
                            <td>
                                @if($detail->referensi)
                                <a href="{{ asset($detail->referensi) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-alt me-1"></i> Lihat
                                </a>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-end">
                                {{ $transaksi->total < 0 ? '+' : '-' }} Rp {{ number_format(abs($transaksi->total), 0, ',', '.') }}
                            </th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between mt-4">
    <!-- <div>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $transaksi->id }}">
            <i class="fas fa-trash me-2"></i> Hapus Transaksi
        </button>
    </div> -->
    <div>
        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i> Tutup
        </a>
    </div>
</div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal-{{ $transaksi->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
                <ul class="mb-3">
                    <li>Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</li>
                    <li>Total: Rp {{ number_format($transaksi->total, 0, ',', '.') }}</li>
                    <li>Jumlah Item: {{ $transaksi->detailTransaksi->count() }}</li>
                </ul>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <!-- <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i> Hapus
                    </button>
                </form> -->
            </div>
        </div>
    </div>
</div>
@endsection