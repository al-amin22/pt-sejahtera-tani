@extends('staff.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3 align-items-center">
        <div class="col">
            <h4 class="mb-0">
                <i class="fas fa-list-alt me-2"></i> Daftar Detail Transaksi
            </h4>
        </div>
        <div class="col-auto">
            <a href="{{ route('staff.transaksi.index') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Tambah Transaksi
            </a>
        </div>
    </div>

    <!-- Card List -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari detail transaksi...">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="datatable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                            <th>Mata Uang</th>
                            <th>Referensi</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->nama_barang }}</td>
                            <td>
                                <span class="badge {{ $detail->jenis == 'pemasukan' ? 'bg-success' : 'bg-danger text-white' }}">
                                    {{ ucfirst($detail->jenis) }}
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-end">
                                {{ $detail->jenis == 'pemasukan' ? '+' : '-' }}
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                            <td>{{ $detail->mataUang->kode ?? '-' }}</td>
                            <td>
                                @if($detail->referensi)
                                <a href="{{ asset($detail->referensi) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-alt me-1"></i> Lihat
                                </a>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-info" title="Lihat Detail" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $detail->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $detail->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-end">
                                Rp {{ number_format($details->sum('subtotal'), 0, ',', '.') }}
                            </th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Search functionality
        $('#searchButton').click(function() {
            const searchTerm = $('#searchInput').val().toLowerCase();
            $('#datatable tbody tr').each(function() {
                const rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.includes(searchTerm));
            });
        });

        // Auto search on typing
        $('#searchInput').on('keyup', function() {
            $('#searchButton').click();
        });

        // Initialize DataTables
        $('#datatable').DataTable({
            responsive: true,
            ordering: true,
            searching: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>'
        });
    });
</script>
@endpush

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }

    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.6em;
    }

    .btn-group .btn {
        border-radius: 6px;
        margin: 0 2px;
    }

    .dataTables_wrapper {
        margin-top: 1rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .text-end {
        text-align: right !important;
    }
</style>
@endpush
