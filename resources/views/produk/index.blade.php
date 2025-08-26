<!-- resources/views/produk/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-boxes me-2"></i> Daftar Barang</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProdukModal">
                <i class="fas fa-plus me-2"></i> Tambah Barang
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">Kode</th>
                            <th class="text-nowrap">Sumber Barang</th>
                            <th class="text-nowrap">No Hp</th>
                            <th class="text-nowrap">Nama Barang</th>
                            <th class="text-nowrap">Harga</th>
                            <th class="text-nowrap">Satuan</th>
                            <th class="text-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produk as $item)
                        <tr>
                            <td>{{ $item->kode ?? '-' }}</td>
                            <td>{{ $item->pemasok->nama ?? '-' }}</td>
                            <td>{{ $item->pemasok->kontak ?? '-' }}</td>
                            <td>{{ $item->nama ?? '-'}}</td>
                            <td>Rp.{{ number_format($item->harga, 2) }}</td>
                            <td>{{ $item->satuan ?? '-' }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $item->id }}">
                                    Edit
                                </button>

                                <!-- Tombol Hapus -->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal-{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('produk.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Barang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="kode" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="kode" name="kode" value="{{ $item->kode }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $item->nama }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="pemasok_id" class="form-label">Pemasok <span class="text-danger">*</span></label>
                                                <select class="form-select" id="pemasok_id" name="pemasok_id" required>
                                                    <option value="">Pilih Pemasok</option>
                                                    @foreach ($pemasok as $itemPemasok)
                                                    <option value="{{ $itemPemasok->id }}" {{ $item->pemasok_id == $itemPemasok->id ? 'selected' : '' }}>
                                                        {{ $itemPemasok->nama }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" class="form-control" id="harga" name="harga" value="{{ $item->harga }}" required min="0">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="satuan" name="satuan" value="{{ $item->satuan }}" required min="0" placeholder="Kg/Ton/pcs/dll">
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
                        <!-- Modal Hapus -->
                        <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('produk.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hapus Barang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Yakin ingin menghapus <strong>{{ $item->nama }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Produk Modal -->
<div class="modal fade" id="addProdukModal" tabindex="-1" aria-labelledby="addProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addProdukModalLabel">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode" name="kode" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="pemasok_id" class="form-label">Pemasok <span class="text-danger">*</span></label>
                        <select class="form-select" id="pemasok_id" name="pemasok_id" required>
                            <option value="">Pilih Pemasok</option>
                            @foreach ($pemasok as $itemPemasok)
                            <option value="{{ $itemPemasok->id }}" {{ old('pemasok_id') == $itemPemasok->id ? 'selected' : '' }}>
                                {{ $itemPemasok->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label">Harga<span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="harga" name="harga" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="satuan" name="satuan" required min="0" placeholder="Kg/Ton/pcs/dll">
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

@endsection