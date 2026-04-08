<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Pengembalian;
use App\Models\LogAktivitas;

class AdminController extends Controller
{
    public function __construct()
    {
        // Admin role check is handled by middleware at route level
    }

    /**
     * Log user activity helper function
     */
    private function logActivity($aktivitas)
    {
        try {
            // Use id_user instead of Auth::id() which returns username
            LogAktivitas::create([
                'id_user' => Auth::user()->id_user,
                'aktivitas' => $aktivitas,
                'waktu' => now(),
            ]);
        } catch (\Exception $e) {
            // Fail silently to avoid breaking the application
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $totalUsers = User::where('role', 'peminjam')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalAlat = Alat::count();
        $totalPeminjaman = Peminjaman::count();
        $totalPengembalian = Pengembalian::count();

        return view('admin.dashboard', compact('totalUsers', 'totalPetugas', 'totalAlat', 'totalPeminjaman', 'totalPengembalian'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'no_telp' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,peminjam,petugas',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        $this->logActivity('Menambahkan user baru: ' . $user->nama . ' (' . $user->role . ')');

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id . ',id_user',
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',id_user',
            'no_telp' => 'nullable|string|max:20',
            'role' => 'required|in:admin,peminjam,petugas',
        ]);

        $user->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'role' => $request->role,
        ]);

        if ($request->password) {
            $request->validate(['password' => 'string|min:6']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $this->logActivity('Mengedit user: ' . $user->nama . ' (Role: ' . $request->role . ')');

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->nama;
        $user->delete();

        $this->logActivity('Menghapus user: ' . $userName);

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus!');
    }

    public function alat()
    {
        $alat = Alat::with('kategori')->get();
        $kategoriList = Kategori::all(); // Provide list of categories for the form
        return view('admin.alat', compact('alat', 'kategoriList'));
    }

    public function storeAlat(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'stok' => 'required|integer|min:1',
            'kode_barang' => 'nullable|string|max:255',
            'kode_barang_base' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.kondisi' => 'nullable|in:baik,diperbaiki,rusak',
        ]);

        $stok = $request->stok;
        $items = $request->items ?? [];

        // Helper function to generate sequential kode_barang
        $generateKodeBarang = function($baseKode, $index) {
            // Try to match pattern: text + separator + number
            if (preg_match('/^(.*?)(\d+)$/', $baseKode, $matches)) {
                $prefix = $matches[1];
                $startNum = intval($matches[2]);
                $numLength = strlen($matches[2]); // Preserve zero-padding

                $newNum = $startNum + $index;
                $paddedNum = str_pad($newNum, $numLength, '0', STR_PAD_LEFT);

                return $prefix . $paddedNum;
            }

            // If no number pattern, just append index
            return $baseKode . '-' . ($index + 1);
        };

        if ($stok > 1 && !empty($items)) {
            // Multiple items with auto-generated kode_barang
            $kodeBarangBase = $request->kode_barang_base;

            if (!$kodeBarangBase) {
                return redirect()->back()->withErrors(['kode_barang_base' => 'Kode barang dasar wajib diisi untuk stok lebih dari 1!']);
            }

            foreach ($items as $index => $item) {
                // Auto-generate sequential kode_barang
                $kodeBarang = $generateKodeBarang($kodeBarangBase, $index);

                $alat = Alat::create([
                    'nama_alat' => $request->nama_alat,
                    'kode_barang' => $kodeBarang,
                    'id_kategori' => $request->id_kategori,
                    'stok' => 1,
                    'lokasi' => $request->lokasi,
                    'deskripsi' => $request->deskripsi,
                    'kondisi' => $item['kondisi'] ?? 'baik',
                ]);
            }

            $this->logActivity('Menambahkan alat baru: ' . $request->nama_alat . ' (' . $stok . ' item dengan kode barang berurutan dari ' . $kodeBarangBase . ')');
        } else {
            // Single item or stock = 1
            $alat = Alat::create([
                'nama_alat' => $request->nama_alat,
                'kode_barang' => $request->kode_barang ?? null,
                'id_kategori' => $request->id_kategori,
                'stok' => $stok,
                'lokasi' => $request->lokasi,
                'deskripsi' => $request->deskripsi,
                'kondisi' => 'baik',
            ]);

            $logMessage = 'Menambahkan alat baru: ' . $request->nama_alat . ' (stok: ' . $stok . ')';
            if ($request->kode_barang) {
                $logMessage .= ' (Kode: ' . $request->kode_barang . ')';
            }
            $this->logActivity($logMessage);
        }

        return redirect()->route('admin.alat')->with('success', 'Alat berhasil ditambahkan!');
    }

    public function updateAlat(Request $request, $id)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kode_barang' => 'nullable|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'stok' => 'required|integer|min:0',
            'tambah_stok' => 'nullable|integer|min:0',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'kondisi' => 'required|in:baik,diperbaiki,rusak',
        ]);

        $alat = Alat::findOrFail($id);

        // Calculate new stock: current stock + additional stock
        $tambahStok = $request->tambah_stok ?? 0;
        $newStock = $request->stok + $tambahStok;

        $alat->update([
            'nama_alat' => $request->nama_alat,
            'kode_barang' => $request->kode_barang,
            'id_kategori' => $request->id_kategori,
            'stok' => $newStock,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'kondisi' => $request->kondisi,
        ]);

        $this->logActivity('Mengedit alat: ' . $request->nama_alat . ($tambahStok > 0 ? ' (Tambah stok: ' . $tambahStok . ')' : ''));

        return redirect()->route('admin.alat')->with('success', 'Alat berhasil diperbarui!' . ($tambahStok > 0 ? ' Stok ditambah ' . $tambahStok . '.' : ''));
    }

    public function deleteAlat($id)
    {
        $alat = Alat::findOrFail($id);
        $alatName = $alat->nama_alat;
        $alat->delete();

        $this->logActivity('Menghapus alat: ' . $alatName);

        return redirect()->route('admin.alat')->with('success', 'Alat berhasil dihapus!');
    }

    public function bulkDeleteAlat(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:alat,id_alat'
        ]);

        $ids = $request->input('ids');
        $deletedCount = 0;

        foreach ($ids as $id) {
            $alat = Alat::findOrFail($id);
            $this->logActivity('Menghapus alat: ' . $alat->nama_alat);
            $alat->delete();
            $deletedCount++;
        }

        return redirect()->route('admin.alat')->with('success', "Berhasil menghapus {$deletedCount} alat!");
    }

    public function editAlatDetails($id)
    {
        $alat = Alat::findOrFail($id);
        return response()->json([
            'id_alat' => $alat->id_alat,
            'nama_alat' => $alat->nama_alat,
            'id_kategori' => $alat->id_kategori,
            'stok' => $alat->stok,
            'kondisi' => $alat->kondisi,
            'kode_barang' => $alat->kode_barang,
            'deskripsi' => $alat->deskripsi,
            'lokasi' => $alat->lokasi
        ]);
    }

    public function getAlatByKategori($id_kategori)
    {
        $alat = Alat::where('id_kategori', $id_kategori)->get(['id_alat', 'nama_alat']);
        return response()->json($alat);
    }

    public function kategori()
    {
        $kategoriList = Kategori::all();
        return view('admin.kategori', compact('kategoriList'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        $this->logActivity('Menambahkan kategori baru: ' . $request->nama_kategori);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id . ',id_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        $this->logActivity('Mengedit kategori: ' . $request->nama_kategori);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function deleteKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategoriName = $kategori->nama_kategori;

        // Check if category is being used by any equipment
        if ($kategori->alat()->count() > 0) {
            return redirect()->route('admin.kategori')->with('error', 'Kategori tidak dapat dihapus karena sedang digunakan oleh alat!');
        }

        $kategori->delete();

        $this->logActivity('Menghapus kategori: ' . $kategoriName);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil dihapus!');
    }

    public function editKategoriDetails($id)
    {
        $kategori = Kategori::findOrFail($id);
        return response()->json([
            'id_kategori' => $kategori->id_kategori,
            'nama_kategori' => $kategori->nama_kategori,
            'deskripsi' => $kategori->deskripsi
        ]);
    }

    public function peminjaman()
    {
        $peminjaman = Peminjaman::with('user', 'petugas', 'detailPeminjaman.alat')->get();
        $users = User::all(); // Provide list of users for the form
        $alat = Alat::all(); // Provide list of equipment for the form
        return view('admin.peminjaman', compact('peminjaman', 'users', 'alat'));
    }

    public function storePeminjaman(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_user' => 'required|string|exists:users,username',
            'id_alat' => 'required|integer|exists:alat,id_alat',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:menunggu persetujuan,dipinjam,dikembalikan,terlambat',
        ]);

        $alat = Alat::findOrFail($request->id_alat);
        
        if ($alat->stok < $request->jumlah) {
            return redirect()->route('admin.peminjaman')->with('error', 'Jumlah alat yang tersedia tidak mencukupi');
        }

        DB::beginTransaction();
        try {
            // Cari user berdasarkan username untuk mendapatkan ID-nya
            $user = User::where('username', $request->id_user)->firstOrFail();
            
            // Pastikan kita menggunakan ID numerik yang benar
            $userId = (int)$user->id_user;

            // Create the borrowing record using the verified numeric ID
            $peminjaman = Peminjaman::create([
                'id_user' => $userId,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'status' => $request->status,
            ]);

            // Create the detail borrowing record
            DetailPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_alat' => $request->id_alat,
                'jumlah' => $request->jumlah,
            ]);

            // Reduce stock
            $alat->update(['stok' => $alat->stok - $request->jumlah]);

            $this->logActivity('Menambahkan peminjaman: ' . $user->nama . ' - ' . $alat->nama_alat . ' (' . $request->jumlah . ' item)');

            DB::commit();

            return redirect()->route('admin.peminjaman')->with('success', 'Peminjaman berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating peminjaman: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_found' => isset($user) ? $user->toArray() : null,
                'exception' => $e
            ]);
            return redirect()->route('admin.peminjaman')->with('error', 'Terjadi kesalahan saat menambahkan peminjaman: ' . $e->getMessage());
        }
    }

    public function updatePeminjaman(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'id_user' => 'required|string|exists:users,username',
            'id_alat' => 'required|integer|exists:alat,id_alat',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:menunggu persetujuan,dipinjam,dikembalikan,terlambat',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            // Cari user berdasarkan username untuk mendapatkan ID-nya
            $user = User::where('username', $request->id_user)->firstOrFail();
            
            // Pastikan kita menggunakan ID numerik yang benar
            $userId = (int)$user->id_user;
            
            // Update the borrowing record using the verified numeric ID
            $peminjaman->update([
                'id_user' => $userId,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'status' => $request->status,
            ]);

            // Update the detail borrowing record
            $detail = $peminjaman->detailPeminjaman()->first();
            if ($detail) {
                // Adjust stock: add back old amount and subtract new amount
                $oldAlat = $detail->alat;
                $oldAlat->update(['stok' => $oldAlat->stok + $detail->jumlah]);
                
                $detail->update([
                    'id_alat' => $request->id_alat,
                    'jumlah' => $request->jumlah,
                ]);

                // Subtract new amount from new alat
                $newAlat = Alat::findOrFail($request->id_alat);
                $newAlat->update(['stok' => $newAlat->stok - $request->jumlah]);
            } else {
                // If no detail exists, create one
                DetailPeminjaman::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_alat' => $request->id_alat,
                    'jumlah' => $request->jumlah,
                ]);

                // Reduce stock
                $alat = Alat::findOrFail($request->id_alat);
                $alat->update(['stok' => $alat->stok - $request->jumlah]);
            }

            DB::commit();

            $this->logActivity('Mengedit peminjaman ID: PJN' . str_pad($peminjaman->id_peminjaman, 3, '0', STR_PAD_LEFT));

            return redirect()->route('admin.peminjaman')->with('success', 'Peminjaman berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error updating peminjaman: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_found' => isset($user) ? $user->toArray() : null,
                'exception' => $e
            ]);
            return redirect()->route('admin.peminjaman')->with('error', 'Terjadi kesalahan saat memperbarui peminjaman: ' . $e->getMessage());
        }
    }

    public function deletePeminjaman($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Restore stock for all borrowed items
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $alat = $detail->alat;
                $alat->update(['stok' => $alat->stok + $detail->jumlah]);
            }

            // Delete detail records
            $peminjaman->detailPeminjaman()->delete();

            // Delete the borrowing record
            $peminjaman->delete();

            $this->logActivity('Menghapus peminjaman ID: PJN' . str_pad($id, 3, '0', STR_PAD_LEFT));

            DB::commit();

            return redirect()->route('admin.peminjaman')->with('success', 'Peminjaman berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error deleting peminjaman: ' . $e->getMessage(), [
                'peminjaman_id' => $id,
                'exception' => $e
            ]);
            return redirect()->route('admin.peminjaman')->with('error', 'Terjadi kesalahan saat menghapus peminjaman: ' . $e->getMessage());
        }
    }

    public function pengembalian()
    {
        $pengembalian = Pengembalian::with('peminjaman.user', 'peminjaman.detailPeminjaman.alat')->get();
        $peminjaman = Peminjaman::all(); // Provide list of peminjaman for the form
        return view('admin.pengembalian', compact('pengembalian', 'peminjaman'));
    }

    public function updatePengembalian(Request $request, $id)
    {
        $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'tanggal_kembali' => 'required|date',
            'denda' => 'required|numeric|min:0',
            'denda_keterlambatan' => 'nullable|numeric|min:0',
            'denda_kerusakan' => 'nullable|numeric|min:0',
            'kondisi_alat' => 'required|in:baik,rusak',
        ]);

        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->update([
            'id_peminjaman' => $request->id_peminjaman,
            'tanggal_kembali' => $request->tanggal_kembali,
            'denda' => $request->denda,
            'denda_keterlambatan' => $request->denda_keterlambatan ?? 0,
            'denda_kerusakan' => $request->denda_kerusakan ?? 0,
            'kondisi_alat' => $request->kondisi_alat,
        ]);

        $this->logActivity('Mengedit pengembalian ID: PGB' . str_pad($id, 3, '0', STR_PAD_LEFT) . ' (Kondisi: ' . $request->kondisi_alat . ')');

        return redirect()->route('admin.pengembalian')->with('success', 'Pengembalian berhasil diperbarui!');
    }

    public function deletePengembalian($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->delete();

        $this->logActivity('Menghapus pengembalian ID: PGB' . str_pad($id, 3, '0', STR_PAD_LEFT));

        return redirect()->route('admin.pengembalian')->with('success', 'Pengembalian berhasil dihapus!');
    }

    public function tolakPengembalian(Request $request, $id)
    {
        try {
            $pengembalian = Pengembalian::findOrFail($id);
            $peminjaman = $pengembalian->peminjaman;

            // Reset peminjaman status back to 'dipinjam'
            $peminjaman->update([
                'status' => 'dipinjam'
            ]);

            // Delete the pengembalian record
            $pengembalian->delete();

            $this->logActivity('Menolak pengembalian untuk peminjaman ID: ' . $peminjaman->id_peminjaman);

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian berhasil ditolak! Peminjam dapat melakukan pengembalian kembali.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal menolak pengembalian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifikasiPengembalian(Request $request, $id)
    {
        try {
            $request->validate([
                'action' => 'required|in:terima,tolak'
            ]);

            $pengembalian = Pengembalian::findOrFail($id);
            $peminjaman = $pengembalian->peminjaman;

            if ($request->action === 'terima') {
                // Accept the return - update status to dikembalikan
                $peminjaman->update([
                    'status' => 'dikembalikan'
                ]);

                // Update stock based on returned items
                $jumlahDikembalikan = $request->jumlah_dikembalikan ?? [];
                $kondisiAlat = $request->kondisi_alat ?? [];
                $detailPeminjamanList = $peminjaman->detailPeminjaman;
                $hasDamage = false;

                foreach ($detailPeminjamanList as $index => $detail) {
                    $jumlah = $jumlahDikembalikan[$index] ?? $detail->jumlah;
                    $kondisi = $kondisiAlat[$index] ?? 'baik';

                    $alat = $detail->alat;

                    // If the tool is in good condition, add it back to stock
                    if ($kondisi === 'baik') {
                        $alat->update(['stok' => $alat->stok + $jumlah]);
                    } else {
                        // If damaged, mark as rusak and don't add to stock
                        $alat->update(['kondisi' => 'rusak']);
                        $hasDamage = true;
                    }
                }

                // Update pengembalian record with actual condition and damage fine
                $dendaKerusakan = 0;
                if ($hasDamage) {
                    // Calculate damage fine: Rp 50,000 per damaged item
                    $tarifKerusakan = config('denda.tarif_kerusakan_default', 50000);
                    $jumlahRusak = 0;
                    
                    if (is_array($kondisiAlat)) {
                        foreach ($kondisiAlat as $idx => $kondisi) {
                            if ($kondisi === 'rusak') {
                                $jumlahRusak += $jumlahDikembalikan[$idx] ?? 1;
                            }
                        }
                    }
                    
                    $dendaKerusakan = $tarifKerusakan * $jumlahRusak;
                    
                    // Update pengembalian with damage fine
                    $pengembalian->update([
                        'kondisi_alat' => 'rusak',
                        'denda_kerusakan' => $dendaKerusakan,
                        'denda' => $pengembalian->denda_keterlambatan + $dendaKerusakan
                    ]);
                }

                $this->logActivity('Menerima pengembalian untuk peminjaman ID: ' . $peminjaman->id_peminjaman . 
                    ($hasDamage ? ' - Ada kerusakan, denda: Rp ' . number_format($dendaKerusakan, 0, ',', '.') : ''));

                return response()->json([
                    'success' => true,
                    'message' => 'Pengembalian berhasil diterima!' . ($hasDamage ? ' Denda kerusakan: Rp ' . number_format($dendaKerusakan, 0, ',', '.') : '')
                ]);
            } else {
                // Reject the return - reset to dipinjam
                $peminjaman->update([
                    'status' => 'dipinjam',
                    'alasan_ditolak' => $request->alasan_penolakan ?? 'Bukti foto tidak sesuai'
                ]);

                // Delete the pengembalian record
                $pengembalian->delete();

                $this->logActivity('Menolak pengembalian untuk peminjaman ID: ' . $peminjaman->id_peminjaman);

                return response()->json([
                    'success' => true,
                    'message' => 'Pengembalian berhasil ditolak! Peminjam dapat melakukan pengembalian kembali.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal memverifikasi pengembalian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logAktivitas()
    {
        $logAktivitas = LogAktivitas::with('user')->orderBy('waktu', 'desc')->get();

        return view('admin.log_aktivitas', compact('logAktivitas'));
    }

    public function getRiwayatPeminjaman($username)
    {
        try {
            // Find user by username
            $user = User::where('username', $username)->first();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found']);
            }

            // Get all borrowings for this user
            $peminjamanList = Peminjaman::with(['detailPeminjaman.alat', 'pengembalian'])
                ->where('id_user', $user->id_user)
                ->orderBy('created_at', 'desc')
                ->get();

            // Format the data for the modal
            $riwayat = [];
            foreach ($peminjamanList as $peminjaman) {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $riwayat[] = [
                        'id_peminjaman' => $peminjaman->id_peminjaman,
                        'alat' => $detail->alat->nama_alat,
                        'jumlah' => $detail->jumlah,
                        'tanggal_pinjam' => \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y'),
                        'tanggal_kembali_rencana' => \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d M Y'),
                        'tanggal_kembali_real' => $peminjaman->pengembalian ? \Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_kembali)->format('d M Y') : null,
                        'status' => $peminjaman->status,
                        'denda' => $peminjaman->pengembalian ? 'Rp ' . number_format($peminjaman->pengembalian->denda, 0, ',', '.') : 'Rp 0'
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'peminjaman' => $riwayat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}