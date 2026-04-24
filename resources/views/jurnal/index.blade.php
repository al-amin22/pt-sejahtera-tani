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
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_jurnal)->format('d/m/Y') }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>{{ $item->referensi ?? '-' }}</td>
                            <td class="text-end">{{ number_format($item->detailJurnal->sum('debit'), 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($item->detailJurnal->sum('kredit'), 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('jurnal.show', $item->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    data-id="{{ $item->id }}"
                                    data-tanggal="{{ $item->tanggal_jurnal }}"
                                    data-referensi="{{ $item->referensi }}"
                                    data-keterangan="{{ $item->keterangan }}"
                                    >
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
                            <label for="tanggal_jurnal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_jurnal" name="tanggal_jurnal" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="referensi" class="form-label">Referensi</label>
                        <input type="text" class="form-control" id="referensi" name="referensi" placeholder="Nomor referensi jurnal">
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
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
    const journalBaseUrl = '/admin/jurnal';

    $(document).ready(function() {
        $('.edit-btn').on('click', function() {
            const id = $(this).data('id');
            $('#addJurnalModal form').attr('action', journalBaseUrl + '/' + id);
            $('#addJurnalModal input[name=_method]').remove();
        });
    });
</script>
@endpush
@endsection
