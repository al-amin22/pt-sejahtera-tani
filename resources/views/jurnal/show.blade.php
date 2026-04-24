@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Detail Jurnal</h4>
            <small class="text-muted">Ringkasan jurnal dan detail akun</small>
        </div>
        <a href="{{ route('jurnal.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body row g-3">
            <div class="col-md-4">
                <label class="text-muted small">Tanggal Jurnal</label>
                <div class="fw-semibold">{{ \Carbon\Carbon::parse($jurnal->tanggal_jurnal)->format('d/m/Y') }}</div>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">Referensi</label>
                <div class="fw-semibold">{{ $jurnal->referensi ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">Keterangan</label>
                <div class="fw-semibold">{{ $jurnal->keterangan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Detail Akun</div>
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>COA</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnal->detailJurnal as $detail)
                        <tr>
                            <td>{{ $detail->coa->kode ?? '-' }} - {{ $detail->coa->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($detail->debit, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->kredit, 0, ',', '.') }}</td>
                            <td>{{ $detail->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada detail jurnal</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
