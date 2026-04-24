@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 rounded-4 text-white" style="background: linear-gradient(135deg, #0f172a, #1d4ed8);">
                <h2 class="mb-2"><i class="fas fa-chart-line me-2"></i>Dashboard Finance</h2>
                <p class="mb-0">Ringkasan keuangan perusahaan, alur transaksi, dan akses cepat ke laporan operasional.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">Fokus peran</div>
                    <h5 class="mt-2">Monitoring kas</h5>
                    <p class="mb-0">Melihat pergerakan dana dan memastikan transaksi tercatat konsisten.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">Proses</div>
                    <h5 class="mt-2">Validasi transaksi</h5>
                    <p class="mb-0">Mendukung review detail transaksi sebelum pelaporan atau ekspor PDF.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">Output</div>
                    <h5 class="mt-2">Laporan siap presentasi</h5>
                    <p class="mb-0">Dirancang untuk portfolio perusahaan dengan tampilan dashboard yang formal.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
