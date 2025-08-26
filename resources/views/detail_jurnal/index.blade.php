<!-- resources/views/detail_jurnal/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-file-invoice-dollar me-2"></i> Detail Jurnal</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDetailJurnalModal">
                <i class="fas fa-plus me-2"></i> Tambah Detail
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">Jurnal</th>
                            <th>COA</th>
                            <th width="15%">Debit</th>
                            <th width="15%">Kredit</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail_jurnal as $item)
                        <tr>
                            <td>#{{ $item->jurnal_id }}</td>
                            <td>{{ $item->coa->kode }} - {{ $item->coa->nama }}</td>
                            <td class="text-end">{{ number_format($item->debit, 2) }}</td>
                            <td class="text-end">{{ number_format($item->kredit, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    data-id="{{ $item->id }}"
                                    data-jurnal_id="{{ $item->jurnal_id }}"
                                    data-coa_id="{{ $item->coa_id }}"
                                    data-debit="{{ $item->debit }}"
                                    data-kredit="{{ $item->kredit }}">
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

<!-- Add Detail Jurnal Modal -->
<div class="modal fade" id="addDetailJurnalModal" tabindex="-1" aria-labelledby="addDetailJurnalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('detail_jurnal.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addDetailJurnalModalLabel">Tambah Detail Jurnal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="jurnal_id" class="form-label">Jurnal <span class="text-danger">*</span></label>
                        <select class="form-select" id="jurnal_id" name="jurnal_id" required>
                            @foreach($jurnals as $jurnal)
                            <option value="{{ $jurnal->id }}">Jurnal #{{ $jurnal->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="coa_id" class="form-label">COA <span class="text-danger">*</span></label>
                        <select class="form-select" id="coa_id" name="coa_id" required>
                            @foreach($coas as $coa)
                            <option value="{{ $coa->id }}">{{ $coa->kode }} - {{ $coa->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="debit" class="form-label">Debit</label>
                            <input type="number" step="0.01" class="form-control" id="debit" name="debit" value="0" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kredit" class="form-label">Kredit</label>
                            <input type="number" step="0.01" class="form-control" id="kredit" name="kredit" value="0" min="0">
                        </div>
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

<!-- Edit and Delete Modals would be similar to COA example -->
@endsection
