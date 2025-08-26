@extends('layouts.app')

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
            <a href="{{ route('transaksi.index') }}" class="btn btn-primary">
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
                            <th>Tanggal</th>
                            <th>Transaksi</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                            <th>Mata Uang</th>
                            <th>Keterangan</th>
                            <th>Dari Rekening</th>
                            <th>Ke Rekening</th>
                            <th>Referensi</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail_transaksi as $index => $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->tanggal_transaksi ? \Carbon\Carbon::parse($detail->tanggal_transaksi)->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($detail->transaksi)
                                <a href="{{ route('transaksi.show', $detail->transaksi->id) }}" class="text-primary">
                                    #{{ $detail->transaksi->id }}
                                </a>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $detail->nama_barang }}</td>
                            <td>
                                <span class="badge {{ $detail->jenis == 'pemasukan' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $detail->jenis == 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td>
                                {{ $detail->mataUang->kode ?? '-' }}
                            </td>
                            <td>{{ $detail->keterangan ?? '-' }}</td>
                            <td>{{ $detail->dariRekening->nama ?? '-' }}</td>
                            <td>{{ $detail->keRekening->nama ?? '-' }}</td>
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

                        <!-- Detail Modal -->
                        <div class="modal fade" id="detailModal-{{ $detail->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Transaksi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Nama Barang</label>
                                                    <p class="fw-semibold">{{ $detail->nama_barang }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Jenis</label>
                                                    <p>
                                                        <span class="badge {{ $detail->jenis == 'pemasukan' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                            {{ $detail->jenis == 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Jumlah</label>
                                                    <p class="fw-semibold">{{ number_format($detail->jumlah, 0, ',', '.') }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Harga</label>
                                                    <p class="fw-semibold">Rp {{ number_format($detail->harga, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Subtotal</label>
                                                    <p class="fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Mata Uang</label>
                                                    <p class="fw-semibold">{{ $detail->mataUang->kode ?? '-' }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Dari Rekening</label>
                                                    <p class="fw-semibold">{{ $detail->dariRekening->nama ?? '-' }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Ke Rekening</label>
                                                    <p class="fw-semibold">{{ $detail->keRekening->nama ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Keterangan</label>
                                            <p class="fw-semibold">{{ $detail->keterangan ?? '-' }}</p>
                                        </div>
                                        @if($detail->referensi)
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Referensi</label>
                                            <div>
                                                <a href="{{ asset($detail->referensi) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-file-alt me-1"></i> Lihat Dokumen
                                                </a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal-{{ $detail->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hapus Detail Transaksi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus detail transaksi ini?</p>
                                        <ul class="mb-3">
                                            <li>Barang: {{ $detail->nama_barang }}</li>
                                            <li>Jumlah: {{ number_format($detail->jumlah, 0, ',', '.') }}</li>
                                            <li>Subtotal: Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</li>
                                        </ul>
                                        <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('detail_transaksi.destroy', $detail->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="7" class="text-end">Total</th>
                            <th class="text-end">Rp {{ number_format($detail_transaksi->sum('subtotal'), 0, ',', '.') }}</th>
                            <th colspan="6"></th>
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
