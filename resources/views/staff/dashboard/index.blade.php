@extends('staff.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 rounded-4 text-white" style="background: linear-gradient(135deg, #0f172a, #16a34a);">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="mb-2"><i class="fas fa-briefcase me-2"></i>Staff Dashboard</h3>
                        <p class="mb-0">Akses cepat ke transaksi, absensi, karyawan, dan hasil produksi.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('staff.transaksi.index') }}" class="btn btn-light btn-sm"><i class="fas fa-exchange-alt me-1"></i> Transaksi</a>
                        <a href="{{ route('staff.absensi.index') }}" class="btn btn-outline-light btn-sm"><i class="fas fa-calendar-check me-1"></i> Absensi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">Operasional</div>
                    <h5 class="mt-2">Transaksi</h5>
                    <p class="mb-0">Kelola input transaksi dan detail pendukung secara terstruktur.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">Absensi</div>
                    <h5 class="mt-2">Kehadiran</h5>
                    <p class="mb-0">Pantau absensi karyawan sebagai dasar perhitungan produksi.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">SDM</div>
                    <h5 class="mt-2">Karyawan</h5>
                    <p class="mb-0">Data karyawan dan jobdesk untuk mendukung pembagian kerja.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">Produksi</div>
                    <h5 class="mt-2">Hasil</h5>
                    <p class="mb-0">Input dan rekap hasil produksi harian berbasis absensi.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="mb-3">Panduan Operasional</h5>
                    <ol class="mb-0">
                        <li>Isi absensi harian karyawan terlebih dahulu.</li>
                        <li>Masukkan hasil produksi berdasarkan karyawan yang hadir.</li>
                        <li>Verifikasi transaksi dan detail pendukung.</li>
                        <li>Gunakan dashboard finance untuk validasi alur dana.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="mb-3">Akses Cepat</h5>
                    <div class="d-grid gap-2">
                        <a class="btn btn-outline-primary" href="{{ route('staff.karyawan.index') }}">Data Karyawan</a>
                        <a class="btn btn-outline-primary" href="{{ route('staff.absensi_karyawan.index') }}">Absensi Karyawan</a>
                        <a class="btn btn-outline-primary" href="{{ route('staff.hasil_produksi.index') }}">Hasil Produksi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
