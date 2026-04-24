@extends('staff.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-box me-2"></i>Detail Hasil Produksi</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><strong>Jenis Hasil:</strong> {{ $hasilProduksi->jenis_hasil }}</div>
                <div class="col-md-6"><strong>Jumlah:</strong> {{ $hasilProduksi->jumlah }} {{ $hasilProduksi->satuan }}</div>
                <div class="col-md-6"><strong>Karyawan:</strong> {{ $hasilProduksi->absensiKaryawan->karyawan->nama ?? '-' }}</div>
                <div class="col-md-6"><strong>Tanggal:</strong> {{ $hasilProduksi->absensi->tanggal ?? '-' }}</div>
                <div class="col-12"><strong>Keterangan:</strong> {{ $hasilProduksi->keterangan ?? '-' }}</div>
            </div>
            <div class="mt-4">
                <a href="{{ route('staff.hasil_produksi.index') }}" class="btn btn-outline-primary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
