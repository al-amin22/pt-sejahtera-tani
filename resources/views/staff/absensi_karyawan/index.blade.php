@extends('staff.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-user-check me-2"></i> Daftar Absensi Karyawan</h2>
        </div>
        <div class="col-auto">
            <form method="GET" class="d-flex">
                <input type="date" class="form-control me-2" name="tanggal" value="{{ $selectedDate }}" required>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i> Tampilkan
                </button>
            </form>
        </div>
    </div>
    <!-- Form Absensi Karyawan -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Absensi Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.absensi_karyawan.storeOrUpdate') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $selectedDate }}">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="absensiTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Nama Karyawan</th>
                                <th width="15%">Status</th>
                                <th width="20%">Jam Masuk</th>
                                <th width="20%">Jam Keluar</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($karyawans as $index => $karyawan)
                            @php
                            $absensiData = $absensiKaryawans[$karyawan->id] ?? null;
                            $disabled = $absensiData ? 'disabled' : '';
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $karyawan->nama }}
                                    <input type="hidden" name="karyawan[{{ $karyawan->id }}][karyawan_id]" value="{{ $karyawan->id }}">
                                </td>
                                <td>
                                    <select class="form-select form-select-sm status-select"
                                        name="karyawan[{{ $karyawan->id }}][status]"
                                        {{ $disabled }}>
                                        <option value="">Pilih Status</option>
                                        <option value="hadir" {{ $absensiData && $absensiData->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="tidak hadir" {{ $absensiData && $absensiData->status == 'tidak hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="time"
                                        class="form-control form-control-sm"
                                        name="karyawan[{{ $karyawan->id }}][jam_masuk]"
                                        value="{{ $absensiData ? ($absensiData->jam_masuk ? \Carbon\Carbon::parse($absensiData->jam_masuk)->format('H:i') : '') : '' }}"
                                        {{ $disabled }}>
                                </td>
                                <td>
                                    <input type="time"
                                        class="form-control form-control-sm"
                                        name="karyawan[{{ $karyawan->id }}][jam_keluar]"
                                        value="{{ $absensiData ? ($absensiData->jam_keluar ? \Carbon\Carbon::parse($absensiData->jam_keluar)->format('H:i') : '') : '' }}"
                                        {{ $disabled }}>
                                </td>
                                <td class="text-center">
                                    @if($absensiData)
                                    <small class="text-success d-block mb-1 fw-bold">
                                        ✅ Sudah diinput, tidak bisa diubah
                                    </small>
                                    @else
                                    <small class="text-muted">Belum diinput</small>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i> Simpan Semua Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Konfirmasi hapus
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus data absensi karyawan ini?')) {
                $(this).parent().submit();
            }
        });

        // Auto-disable time inputs if status is "tidak hadir"
        $('.status-select').change(function() {
            var row = $(this).closest('tr');
            var timeInputs = row.find('input[type="time"]');

            if ($(this).val() === 'tidak hadir') {
                timeInputs.val('').prop('disabled', true);
            } else {
                timeInputs.prop('disabled', false);
            }
        });

        // Highlight row on focus
        $('input, select').focus(function() {
            $(this).closest('tr').addClass('highlight-row');
        }).blur(function() {
            $(this).closest('tr').removeClass('highlight-row');
        });

        // Trigger change on page load
        $('.status-select').trigger('change');
    });
</script>
@endpush

@push('styles')
<style>
    .table th {
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }

    .form-select-sm,
    .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        margin: 0 2px;
    }

    .highlight-row {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }

        .container-fluid {
            padding: 0 10px;
        }

        h2 {
            font-size: 1.5rem;
        }

        .d-flex {
            flex-direction: column;
        }

        .d-flex .me-2 {
            margin-right: 0 !important;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush