<!-- resources/views/jurnal/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-file-invoice me-2"></i> Daftar Jurnal</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJurnalModal">
                <i class="fas fa-plus me-2"></i> Tambah Jurnal
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">Tanggal</th>
                            <th>Keterangan</th>
                            <th width="10%">Transaksi</th>
                            <th width="15%">User</th>
                            <th width="15%">Total Debit</th>
                            <th width="15%">Total Kredit</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jurnal as $item)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>#{{ $item->transaksi_id }}</td>
                            <td>{{ $item->user->nama }}</td>
                            <td class="text-end">{{ number_format($item->detailJurnal->sum('debit'), 2) }}</td>
                            <td class="text-end">{{ number_format($item->detailJurnal->sum('kredit'), 2) }}</td>
                            <td>
                                <a href="{{ route('jurnal.show', $item->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    data-id="{{ $item->id }}"
                                    data-tanggal="{{ $item->tanggal }}"
                                    data-keterangan="{{ $item->keterangan }}"
                                    data-transaksi_id="{{ $item->transaksi_id }}"
                                    data-user_id="{{ $item->user_id }}">
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

<!-- Add Jurnal Modal -->
<div class="modal fade" id="addJurnalModal" tabindex="-1" aria-labelledby="addJurnalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('jurnal.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addJurnalModalLabel">Tambah Jurnal Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="transaksi_id" class="form-label">Transaksi <span class="text-danger">*</span></label>
                            <select class="form-select" id="transaksi_id" name="transaksi_id" required>
                                @foreach($transaksis as $transaksi)
                                <option value="{{ $transaksi->id }}">#{{ $transaksi->id }} - {{ ucfirst($transaksi->jenis) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr>
                    <h5>Detail Jurnal</h5>
                    <div id="detailJurnalContainer">
                        <div class="detail-jurnal-item mb-3 border p-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="form-label">COA <span class="text-danger">*</span></label>
                                    <select class="form-select coa-select" name="coa_id[]" required>
                                        @foreach($coas as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->kode }} - {{ $coa->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Debit</label>
                                    <input type="number" step="0.01" class="form-control debit-input" name="debit[]" value="0" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kredit</label>
                                    <input type="number" step="0.01" class="form-control kredit-input" name="kredit[]" value="0" min="0">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-detail" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" id="addDetailJurnal">
                        <i class="fas fa-plus me-1"></i> Tambah Detail
                    </button>
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
        // Add new detail jurnal row
        $('#addDetailJurnal').click(function() {
            var newRow = $('.detail-jurnal-item').first().clone();
            newRow.find('input').val('0');
            newRow.find('.remove-detail').prop('disabled', false);
            $('#detailJurnalContainer').append(newRow);
        });

        // Remove detail jurnal row
        $(document).on('click', '.remove-detail', function() {
            if ($('.detail-jurnal-item').length > 1) {
                $(this).closest('.detail-jurnal-item').remove();
            }
        });

        // Ensure either debit or credit is filled
        $(document).on('change', '.debit-input, .kredit-input', function() {
            var row = $(this).closest('.detail-jurnal-item');
            var debit = row.find('.debit-input').val();
            var kredit = row.find('.kredit-input').val();

            if (parseFloat(debit) > 0 && parseFloat(kredit) > 0) {
                row.find('.kredit-input').val('0');
            }
        });
    });
</script>
@endpush
@endsection
