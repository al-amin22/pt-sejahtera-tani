<!-- resources/views/coa/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-book me-2"></i> Chart of Accounts</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCoaModal">
                <i class="fas fa-plus me-2"></i> Tambah COA
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
                            <th class="text-nowrap">Nama</th>
                            <th class="text-nowrap">Jenis</th>
                            <th class="text-nowrap">Saldo Awal</th>
                            <th class="text-nowrap">Tgl</th>
                            <th class="text-nowrap text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coa as $item)
                        <tr>
                            <td data-label="Kode" class="text-nowrap">{{ $item->kode }}</td>
                            <td data-label="Nama">{{ $item->nama }}</td>
                            <td data-label="Jenis" class="text-nowrap">{{ ucfirst($item->jenis) }}</td>
                            <td data-label="Saldo Awal" class="text-nowrap">{{ number_format($item->saldo_awal, 2) }}</td>
                            <td data-label="Tgl" class="text-nowrap">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td data-label="Aksi" class="text-nowrap text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <button class="btn btn-sm btn-outline-primary edit-btn"
                                        data-id="{{ $item->id }}"
                                        data-kode="{{ $item->kode }}"
                                        data-nama="{{ $item->nama }}"
                                        data-tipe="{{ $item->tipe }}">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-none d-sm-inline">Edit</span>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-sm-inline">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add COA Modal -->
<div class="modal fade" id="addCoaModal" tabindex="-1" aria-labelledby="addCoaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('coa.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCoaModalLabel">Tambah COA Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode COA <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode" name="kode" required>
                        <small class="text-muted">Contoh: 1000, 1100, 2000, dst.</small>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama COA <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis" name="jenis" required>
                            <option value="aset">Aset</option>
                            <option value="kewajiban">Kewajiban</option>
                            <option value="ekuitas">Ekuitas</option>
                            <option value="pendapatan">Pendapatan</option>
                            <option value="beban">Beban</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label for="saldo_awal" class="form-label">
                            Saldo Awal <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="saldo_awal" name="saldo_awal" required>
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

<!-- Edit COA Modal -->
<div class="modal fade" id="editCoaModal" tabindex="-1" aria-labelledby="editCoaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCoaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editCoaModalLabel">Edit COA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_kode" class="form-label">Kode COA</label>
                        <input type="text" class="form-control" id="edit_kode" name="kode" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama COA</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis" name="jenis" required>
                            <option value="aset">Aset</option>
                            <option value="kewajiban">Kewajiban</option>
                            <option value="ekuitas">Ekuitas</option>
                            <option value="pendapatan">Pendapatan</option>
                            <option value="beban">Beban</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_saldo_awal" class="form-label">Saldo Awal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_saldo_awal" name="saldo_awal" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCoaModal" tabindex="-1" aria-labelledby="deleteCoaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteCoaForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCoaModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus COA ini? Data yang terkait mungkin akan terpengaruh.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle edit button click
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var kode = $(this).data('kode');
            var nama = $(this).data('nama');
            var tipe = $(this).data('tipe');

            $('#edit_kode').val(kode);
            $('#edit_nama').val(nama);
            $('#edit_tipe').val(tipe);

            $('#editCoaForm').attr('action', '/coa/' + id);
            $('#editCoaModal').modal('show');
        });

        // Handle delete button click
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            $('#deleteCoaForm').attr('action', '/coa/' + id);
            $('#deleteCoaModal').modal('show');
        });
    });
</script>

<script>
    // Fungsi format ke rupiah
    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix === undefined ? rupiah : (rupiah ? prefix + ' ' + rupiah : '');
    }

    const saldoAwal = document.getElementById('saldo_awal');

    // Format saat ketik
    saldoAwal.addEventListener('keyup', function(e) {
        this.value = formatRupiah(this.value, 'Rp');
    });

    // Bersihkan format sebelum submit form
    saldoAwal.form.addEventListener('submit', function() {
        saldoAwal.value = saldoAwal.value.replace(/[^0-9]/g, '');
    });
</script>

<script>
    // Fungsi format ke rupiah
    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix === undefined ? rupiah : (rupiah ? prefix + ' ' + rupiah : '');
    }

    const editSaldoAwal = document.getElementById('edit_saldo_awal');

    // Format saat ketik
    editSaldoAwal.addEventListener('keyup', function(e) {
        this.value = formatRupiah(this.value, 'Rp');
    });

    // Bersihkan format sebelum submit form
    editSaldoAwal.form.addEventListener('submit', function() {
        editSaldoAwal.value = editSaldoAwal.value.replace(/[^0-9]/g, '');
    });
</script>
@endpush
@endsection
