@extends('admin.layout')

@section('title', 'Kategori Alat - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-tags text-orange me-2"></i>Kategori Alat</h2>
                <p class="text-muted mb-0">Kelola kategori peralatan</p>
            </div>
        </div>
    </div>

    <!-- Add Category Button -->
   

    <!-- Categories Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Daftar Kategori</h5>
             <div class="mb-4">
        <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
            <i class="fas fa-plus text-white me-2"></i>Tambah Kategori
        </button>
    </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="kategoriTable">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoriList as $kategori)
                        <tr>
                            <td>{{ $kategori->nama_kategori }}</td>
                            <td>{{ $kategori->deskripsi ?: '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editKategori({{ $kategori->id_kategori }}, '{{ $kategori->nama_kategori }}')" data-bs-toggle="modal" data-bs-target="#editKategoriModal">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    <form action="{{ route('admin.deleteKategori', ['id' => $kategori->id_kategori]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center">Tidak ada data kategori</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addKategoriModal" tabindex="-1" aria-labelledby="addKategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKategoriModalLabel">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.storeKategori') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi kategori"></textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-orange">Simpan Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editKategoriForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi kategori"></textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-orange">Perbarui Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function editKategori(id, nama_kategori) {
        // Fetch the category details including description
        fetch(`/admin/kategori/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_nama_kategori').value = data.nama_kategori;
                document.getElementById('edit_deskripsi').value = data.deskripsi || '';
                document.getElementById('editKategoriForm').action = "{{ url('/admin/kategori') }}/" + id;
            })
            .catch(error => console.error('Error fetching category details:', error));
    }
</script>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($('#kategoriTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#kategoriTable')) {
                try {
                    $('#kategoriTable').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                        pageLength: 10,
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
                    console.log('✓ kategoriTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
@endsection