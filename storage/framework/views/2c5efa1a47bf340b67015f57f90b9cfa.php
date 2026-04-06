<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Sistem Peminjaman Alat'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/borrower-dashboard.css')); ?>">
    <?php echo $__env->yieldContent('styles'); ?>
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
                            <a class="nav-link <?php echo e(Request::routeIs('peminjam.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('peminjam.dashboard')); ?>">
                                <i class="fas fa-tachometer-alt text-orange me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('peminjam.tools') ? 'active' : ''); ?>" href="<?php echo e(route('peminjam.tools')); ?>">
                                <i class="fas fa-boxes text-orange me-2"></i>Alat Tersedia
                            </a>
                        </li>
                     
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('peminjam.pengembalian') ? 'active' : ''); ?>" href="<?php echo e(route('peminjam.pengembalian')); ?>">
                                <i class="fas fa-calendar-check text-orange me-2"></i>Pengembalian Alat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('peminjam.history') ? 'active' : ''); ?>" href="<?php echo e(route('peminjam.history')); ?>">
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
                        <a class="navbar-brand" href="<?php echo e(route('peminjam.dashboard')); ?>">
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
                                        <span class="d-none d-md-inline"><?php echo e(Auth::user()->name ?? 'Peminjam'); ?></span>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <div class="dropdown-header">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name ?? 'User')); ?>&background=3498db&color=fff" alt="Profile" class="rounded-circle me-2" width="32" height="32">
                                                    <div>
                                                        <div class="fw-bold"><?php echo e(Auth::user()->name ?? 'Peminjam'); ?></div>
                                                        <small class="text-muted">Peminjam</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('peminjam.profile')); ?>"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('peminjam.settings')); ?>"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="<?php echo e(url('/logout')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Keluar</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

          
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="<?php echo e(asset('js/borrower-dashboard.js')); ?>"></script>
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
    <?php echo $__env->yieldContent('scripts'); ?>


</body>
</html><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/peminjam/layout.blade.php ENDPATH**/ ?>