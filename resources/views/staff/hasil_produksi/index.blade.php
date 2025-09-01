@extends('staff.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="fas fa-boxes me-2"></i> Daftar Hasil Produksi</h2>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHasilProduksiModal">
                <i class="fas fa-plus me-2"></i> Tambah Hasil Produksi
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Hasil</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasilProduksis as $item)
                        <tr>
                            <td>{{ $item->absensiKaryawan->absensi->tanggal ?? '-' }}</td>
                            <td>{{ $item->jenis_hasil ?? '-' }}</td>
                            <td>{{ $item->jumlah ?? '0' }}</td>
                            <td>{{ $item->satuan ?? '-' }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('staff.hasil_produksi.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel-{{ $item->id }}">Edit Hasil Produksi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="jenis_hasil" class="form-label">Jenis Hasil</label>
                                                <select class="form-control" id="jenis_hasil" name="jenis_hasil" required>
                                                    <option value="Dedak kering kasar" {{ $item->jenis_hasil == 'Dedak kering kasar' ? 'selected' : '' }}>Dedak kering kasar</option>
                                                    <option value="Dedak kering halus" {{ $item->jenis_hasil == 'Dedak kering halus' ? 'selected' : '' }}>Dedak kering halus</option>
                                                    <option value="Dedak basah" {{ $item->jenis_hasil == 'Dedak basah' ? 'selected' : '' }}>Dedak basah</option>
                                                    <option value="Hasil bola-bola" {{ $item->jenis_hasil == 'Hasil bola-bola' ? 'selected' : '' }}>Hasil bola-bola</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="jumlah" class="form-label">Jumlah</label>
                                                <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ $item->jumlah }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="satuan" class="form-label">Satuan</label>
                                                <input type="text" class="form-control" id="satuan" name="satuan" value="{{ $item->satuan }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="keterangan" class="form-label">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan">{{ $item->keterangan }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="absensi_karyawan_id" class="form-label">Pilih Tanggal</label>
                                                <select class="form-select" id="absensi_karyawan_id" name="absensi_karyawan_id" required>
                                                    <option value="" selected disabled>Pilih Absensi</option>
                                                    @foreach($absensis as $absensiKaryawan)
                                                    <option value="{{ $absensiKaryawan->id }}">
                                                        {{ \Carbon\Carbon::parse($absensiKaryawan->absensi->tanggal)->format('d/m/Y') }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <!-- <div class="mb-3">
                                                <label for="absensi_karyawan_id" class="form-label">Tanggal</label>
                                                <select class="form-select" id="absensi_karyawan_id" name="absensi_karyawan_id" required>
                                                    <option value="">Pilih Tanggal Hari Ini</option>
                                                    @foreach($absensiKaryawans as $absensiKaryawan)
                                                    <option value="{{ $absensiKaryawan->id }}" {{ $item->absensi_karyawan_id == $absensiKaryawan->id ? 'selected' : '' }}>
                                                        {{ $absensiKaryawan->absensi->tanggal }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div> -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('staff.hasil_produksi.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel-{{ $item->id }}">Hapus Hasil Produksi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus hasil produksi ini?</p>
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

<div class="modal fade" id="addHasilProduksiModal" tabindex="-1" aria-labelledby="addHasilProduksiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addHasilProduksiModalLabel">Tambah Hasil Produksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('staff.hasil_produksi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="jenis_hasil" class="form-label">Jenis Hasil</label>
                        <select class="form-select" id="jenis_hasil" name="jenis_hasil" required>
                            <option value="">-- Pilih Jenis Hasil --</option>
                            <option value="Dedak kering kasar">Dedak kering kasar</option>
                            <option value="Dedak kering halus">Dedak kering halus</option>
                            <option value="Dedak basah">Dedak basah</option>
                            <option value="Hasil bola-bola">Hasil bola-bola</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="absensi_karyawan_id" class="form-label">Pilih tanggal</label>
                        <select class="form-select" id="absensi_karyawan_id" name="absensi_karyawan_id" required>
                            <option value="" selected disabled>Pilih Absensi</option>
                            @foreach($absensis as $absensiKaryawan)
                            <option value="{{ $absensiKaryawan->id }}">
                                {{ \Carbon\Carbon::parse($absensiKaryawan->absensi->tanggal)->format('d/m/Y') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- <div class="mb-3">
                        <label for="absensi_karyawan_id" class="form-label">Tanggal</label>
                        <select class="form-select" id="absensi_karyawan_id" name="absensi_karyawan_id" required>
                            <option value="">Pilih Tanggal Hari Ini</option>
                            @foreach($absensiKaryawans as $absensiKaryawan)
                            <option value="{{ $absensiKaryawan->id }}">
                                {{ $absensiKaryawan->absensi->tanggal }}
                            </option>
                            @endforeach
                        </select>
                    </div> -->
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection