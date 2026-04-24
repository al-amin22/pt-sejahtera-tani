@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Detail Pengguna</h4>
            <small class="text-muted">Informasi akun yang tersimpan pada sistem</small>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Nama</label>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Email</label>
                            <div class="fw-semibold">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Peran</label>
                            <div><span class="badge bg-info text-dark">{{ ucfirst($user->role) }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Dibuat</label>
                            <div class="fw-semibold">{{ optional($user->created_at)->format('d M Y H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Diperbarui</label>
                            <div class="fw-semibold">{{ optional($user->updated_at)->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h6 class="fw-semibold">Catatan</h6>
                    <p class="text-muted mb-0">
                        Gunakan halaman ini untuk meninjau detail akun sebelum melakukan pembaruan akses atau peran.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
