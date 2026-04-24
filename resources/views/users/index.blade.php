@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Manajemen Pengguna</h4>
            <small class="text-muted">Kelola akun admin, staff, dan finance</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-secondary" href="{{ route('users.exportCsv') }}">
                <i class="fas fa-file-csv me-1"></i> Export CSV
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-1"></i> Tambah User
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($item->role) }}</span></td>
                            <td>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('users.show', $item->id) }}">
                                    Detail
                                </a>
                                <button
                                    class="btn btn-sm btn-outline-primary btn-edit"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-email="{{ $item->email }}"
                                    data-role="{{ $item->role }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    Edit
                                </button>
                                <form action="{{ route('users.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Tambah User</h5></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nama</label><input class="form-control" name="name" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
                <div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required minlength="6"></div>
                <div class="mb-3">
                    <label class="form-label">Peran</label>
                    <select class="form-select" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="finance">Finance</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header"><h5 class="modal-title">Edit User</h5></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nama</label><input class="form-control" id="edit_name" name="name" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input class="form-control" id="edit_email" type="email" name="email" required></div>
                <div class="mb-3">
                    <label class="form-label">Peran</label>
                    <select class="form-select" id="edit_role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="finance">Finance</option>
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Password Baru</label><input class="form-control" type="password" name="password" minlength="6"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.btn-edit').forEach(function(button) {
    button.addEventListener('click', function() {
        var id = this.dataset.id;
        document.getElementById('editForm').action = '/admin/users/' + id;
        document.getElementById('edit_name').value = this.dataset.name;
        document.getElementById('edit_email').value = this.dataset.email;
        document.getElementById('edit_role').value = this.dataset.role;
    });
});
</script>
@endpush
@endsection
