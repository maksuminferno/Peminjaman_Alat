<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Peminjaman Alat')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/borrower-dashboard.css') }}">
    @yield('styles')
</head>
<body>
    <!-- Main Content with wider layout -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (optional, can be toggled) -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('peminjam.dashboard') ? 'active' : '' }}" href="{{ route('peminjam.dashboard') }}">
                                <i class="fas fa-tachometer-alt text-orange me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('peminjam.tools') ? 'active' : '' }}" href="{{ route('peminjam.tools') }}">
                                <i class="fas fa-boxes text-orange me-2"></i>Alat Tersedia
                            </a>
                        </li>
                     
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('peminjam.pengembalian') ? 'active' : '' }}" href="{{ route('peminjam.pengembalian') }}">
                                <i class="fas fa-calendar-check text-orange me-2"></i>Pengembalian Alat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('peminjam.history') ? 'active' : '' }}" href="{{ route('peminjam.history') }}">
                                <i class="fas fa-history text-orange me-2"></i>Riwayat Peminjaman
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="main-content col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navigation Bar inside main content -->
               <nav class="navbar navbar-expand navbar-light bg-light mb-4 rounded navbar-custom">

                    <div class="container-fluid p-0">
                        <a class="navbar-brand" href="{{ route('peminjam.dashboard') }}">
                            <i class="fas fa-tools me-2"></i>Sistem Peminjaman Alat
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                            <!-- User Dropdown -->
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                       <img 
  src="https://ui-avatars.com/api/?name=User&background=f97316&color=fff" alt="Profile" class="rounded-circle me-2" width="32" height="32">
                                        <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Peminjam' }}</span>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <div class="dropdown-header">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=3498db&color=fff" alt="Profile" class="rounded-circle me-2" width="32" height="32">
                                                    <div>
                                                        <div class="fw-bold">{{ Auth::user()->name ?? 'Peminjam' }}</div>
                                                        <small class="text-muted">Peminjam</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('peminjam.profile') }}"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                                        <li><a class="dropdown-item" href="{{ route('peminjam.settings') }}"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ url('/logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Keluar</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

          
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/borrower-dashboard.js') }}"></script>
    <script>
    $(document).ready(function() {
        // Semua tabel yang punya class .datatable akan otomatis jadi DataTable
        $('.datatable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                zeroRecords: "Tidak ditemukan hasil",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "›",
                    previous: "‹"
                }
            }
        });
    });
    </script>
    @yield('scripts')


</body>
</html>