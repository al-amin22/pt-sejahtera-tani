<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            transition: all 0.3s;
            width: 250px;
        }

        .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }

        .list-group-item {
            border: none;
            padding: 1rem 1.25rem;
        }

        .list-group-item.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .badge {
            font-size: 0.85em;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }

            .sidebar.active {
                margin-left: 0;
            }
        }

        .page-content {
            width: calc(100% - 250px);
        }

        @media (max-width: 768px) {
            .page-content {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark text-white sidebar" id="sidebar">
            <div class="sidebar-heading text-center">
                <h4>Sistem Akuntansi</h4>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <a href="{{ route('transaksi.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-exchange-alt me-2"></i> Transaksi
                </a>
                <a href="{{ route('detail_transaksi.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-receipt me-2"></i> Detail Transaksi
                </a>
                <a href="{{ route('mata_uang.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-money-bill me-2"></i> Mata Uang
                </a>
                <a href="{{ route('karyawan.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-users me-2"></i> Karyawan
                </a>
                <a href="{{ route('absensi.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-calendar-check me-2"></i> Absensi
                </a>
                <a href="{{ route('absensi_karyawan.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-calendar-check me-2"></i> Absensi Karyawan
                </a>
                <a href="{{ route('rekening.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-university me-2"></i> Rekening
                </a>
                <a href="{{ route('hasil_produksi.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-boxes me-2"></i> Hasil Produksi
                </a>
                <!-- <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-users me-2"></i> Pengguna
                </a> -->
            </div>
        </div>

        <!-- Page Content -->
        <div class="page-content" id="page-content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-sm btn-dark d-md-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->nama }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 py-3">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Auto-hide sidebar on small screens
        function handleResize() {
            if (window.innerWidth < 768) {
                document.getElementById('sidebar').classList.remove('active');
            } else {
                document.getElementById('sidebar').classList.add('active');
            }
        }

        window.addEventListener('resize', handleResize);
        document.addEventListener('DOMContentLoaded', handleResize);

        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>