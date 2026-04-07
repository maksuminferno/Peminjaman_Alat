<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\DetailPeminjaman;
use App\Models\Alat;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class PetugasController extends Controller
{
    public function __construct()
    {
        // Petugas role check is handled by middleware at route level
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

    /**
     * Calculate late return fine
     */
    private function hitungDendaKeterlambatan($tanggalRencana, $tanggalKembali)
    {
        $tanggalRencana = Carbon::parse($tanggalRencana);
        $tanggalKembali = Carbon::parse($tanggalKembali);
        
        // If returned on time or early, no fine
        if ($tanggalKembali->lte($tanggalRencana)) {
            return 0;
        }
        
        // Calculate days late
        $hariTerlambat = $tanggalRencana->diffInDays($tanggalKembali);
        
        // Calculate fine
        $tarifPerHari = config('denda.tarif_keterlambatan_per_hari', 10000);
        return $hariTerlambat * $tarifPerHari;
    }

    /**
     * Calculate damage fine
     */
    private function hitungDendaKerusakan($kondisiAlat, $jumlahDikembalikan, $detailPeminjamanList)
    {
        $totalDendaKerusakan = 0;
        $tarifKerusakan = config('denda.tarif_kerusakan_default', 50000);
        
        if (is_array($kondisiAlat)) {
            foreach ($kondisiAlat as $index => $kondisi) {
                if ($kondisi === 'rusak') {
                    $idAlat = request()->id_alat[$index] ?? null;
                    if ($idAlat) {
                        $detail = $detailPeminjamanList->firstWhere('id_alat', $idAlat);
                        $jumlah = request()->jumlah_dikembalikan[$index] ?? 1;
                        
                        // If there are multiple damaged items, multiply by quantity
                        $totalDendaKerusakan += ($tarifKerusakan * $jumlah);
                    }
                }
            }
        } elseif ($kondisiAlat === 'rusak') {
            // If overall condition is damaged without specific breakdown
            $totalDendaKerusakan = $tarifKerusakan;
        }
        
        return $totalDendaKerusakan;
    }

    public function dashboard()
    {
        // Hitung jumlah pengajuan peminjaman (status: menunggu persetujuan)
        $totalPengajuan = Peminjaman::where('status', 'menunggu persetujuan')->count();

        // Hitung jumlah peminjaman aktif (status: dipinjam)
        $totalAktif = Peminjaman::where('status', 'dipinjam')->count();

        // Hitung jumlah keterlambatan (status: terlambat)
        $totalKeterlambatan = Peminjaman::where('status', 'terlambat')->count();

        // Hitung total pengembalian
        $totalPengembalian = Peminjaman::where('status', 'dikembalikan')->count();

        // Hitung total denda dengan breakdown
        $totalDenda = Pengembalian::sum('denda');
        $totalDendaKeterlambatan = Pengembalian::sum('denda_keterlambatan');
        $totalDendaKerusakan = Pengembalian::sum('denda_kerusakan');

        // Ambil 5 peminjaman terbaru untuk ditampilkan di dashboard
        $recentPeminjaman = Peminjaman::with('user', 'detailPeminjaman.alat')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('petugas.dashboard', compact('totalPengajuan', 'totalAktif', 'totalKeterlambatan', 'totalPengembalian', 'totalDenda', 'totalDendaKeterlambatan', 'totalDendaKerusakan', 'recentPeminjaman'));
    }

    public function peminjaman()
    {
        $peminjaman = Peminjaman::with('user', 'detailPeminjaman.alat')
            ->where('status', 'menunggu persetujuan')
            ->get();
        
        $peminjamanAktif = Peminjaman::with(['user', 'detailPeminjaman.alat'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->get();
        
        return view('petugas.peminjaman', compact('peminjaman', 'peminjamanAktif'));
    }

    public function getPeminjamanDetails($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.alat')->findOrFail($id);
        
        $details = [];
        foreach ($peminjaman->detailPeminjaman as $detail) {
            // Get all available items with stock >= requested amount and good condition
            $availableItems = Alat::where('stok', '>=', $detail->jumlah)
                ->where('kondisi', 'baik')
                ->orderBy('kode_barang', 'asc')
                ->get(['id_alat', 'nama_alat', 'kode_barang', 'lokasi', 'stok']);
            
            $details[] = [
                'id_detail' => $detail->id_detail,
                'id_alat' => $detail->id_alat,
                'nama_alat' => $detail->alat->nama_alat,
                'jumlah' => $detail->jumlah,
                'kode_barang' => $detail->kode_barang,
                'availableItems' => $availableItems
            ];
        }
        
        return response()->json([
            'success' => true,
            'details' => $details
        ]);
    }

    public function approvePeminjaman($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Check if status is 'menunggu persetujuan'
            if ($peminjaman->status !== 'menunggu persetujuan') {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Status peminjaman bukan menunggu persetujuan'], 400);
                }
                return redirect()->back()->with('error', 'Status peminjaman bukan menunggu persetujuan');
            }

            // Update status to 'dipinjam' (already borrowed)
            // Use id_user (numeric ID) instead of auth()->id() which returns username
            $peminjaman->update([
                'status' => 'dipinjam',
                'disetujui_oleh' => auth()->user()->id_user, // Save the approving officer's numeric ID
            ]);

            // Reduce stock for each borrowed item
            foreach ($peminjaman->detailPeminjaman as $detail) {
                // Reduce stock
                $alat = $detail->alat;
                $alat->decrement('stok', $detail->jumlah);
            }

            // Log activity
            $this->logActivity("Menyetujui peminjaman {$peminjaman->user->nama} untuk alat: " . 
                implode(', ', $peminjaman->detailPeminjaman->map(function($d) { return $d->alat->nama_alat; })->toArray()));

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Peminjaman berhasil disetujui', 'id_peminjaman' => $id]);
            }

            return redirect()->back()->with('success', 'Peminjaman berhasil disetujui');
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menyetujui peminjaman: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal menyetujui peminjaman: ' . $e->getMessage());
        }
    }

    public function rejectPeminjaman($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Validate rejection reason
            $request->validate([
                'alasan_ditolak' => 'required|string|max:1000',
            ]);

            // Check if status is 'menunggu persetujuan'
            if ($peminjaman->status !== 'menunggu persetujuan') {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Status peminjaman bukan menunggu persetujuan'], 400);
                }
                return redirect()->back()->with('error', 'Status peminjaman bukan menunggu persetujuan');
            }

            // Update status to 'ditolak' with reason
            $peminjaman->update([
                'status' => 'ditolak',
                'alasan_ditolak' => $request->alasan_ditolak,
            ]);

            // Log activity
            $this->logActivity("Menolak peminjaman {$peminjaman->user->nama} untuk alat: " . 
                implode(', ', $peminjaman->detailPeminjaman->map(function($d) { return $d->alat->nama_alat; })->toArray()) . 
                ". Alasan: {$request->alasan_ditolak}");

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Peminjaman berhasil ditolak', 'id_peminjaman' => $id]);
            }

            return redirect()->back()->with('success', 'Peminjaman berhasil ditolak');
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menolak peminjaman: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal menolak peminjaman: ' . $e->getMessage());
        }
    }

    public function pengembalian()
    {
        // Get active borrowings (status: dipinjam or terlambat)
        $peminjamanAktif = Peminjaman::with(['user', 'detailPeminjaman.alat'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->get();

        // Get return history
        $pengembalianList = Pengembalian::with(['peminjaman.user'])
            ->orderBy('tanggal_kembali', 'desc')
            ->get();

        return view('petugas.pengembalian', compact('peminjamanAktif', 'pengembalianList'));
    }

    public function storePengembalian(Request $request)
    {
        $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'kondisi_alat' => 'required|in:baik,rusak',
            'jumlah_dikembalikan' => 'required|integer|min:1',
            'deskripsi_kerusakan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->id_peminjaman);

        $kondisiAlat = $request->kondisi_alat;
        $jumlahDikembalikan = $request->jumlah_dikembalikan;
        $deskripsiKerusakan = $request->deskripsi_kerusakan;

        // Determine overall condition
        $overallCondition = 'baik';

        if (is_array($kondisiAlat)) {
            foreach ($kondisiAlat as $kondisi) {
                if ($kondisi === 'rusak') {
                    $overallCondition = 'rusak';
                    break;
                }
            }
        } elseif ($kondisiAlat === 'rusak') {
            $overallCondition = 'rusak';
        }

        DB::beginTransaction();
        try {
            // Calculate fines
            $tanggalKembali = Carbon::now();
            
            // Calculate late fine
            $dendaKeterlambatan = $this->hitungDendaKeterlambatan(
                $peminjaman->tanggal_kembali_rencana,
                $tanggalKembali
            );
            
            // Calculate damage fine
            $dendaKerusakan = $this->hitungDendaKerusakan(
                $kondisiAlat,
                $jumlahDikembalikan,
                $detailPeminjamanList
            );
            
            // Total fine
            $totalDenda = $dendaKeterlambatan + $dendaKerusakan;
            
            // Create return record with breakdown
            $pengembalian = Pengembalian::create([
                'tanggal_kembali' => $tanggalKembali,
                'denda' => $totalDenda,
                'denda_keterlambatan' => $dendaKeterlambatan,
                'denda_kerusakan' => $dendaKerusakan,
                'kondisi_alat' => $overallCondition,
                'deskripsi_kerusakan' => $overallCondition === 'rusak' ? $deskripsiKerusakan : null,
                'id_peminjaman' => $peminjaman->id_peminjaman,
            ]);

            // Update borrowing status
            $peminjaman->update(['status' => 'dikembalikan']);

            // Process each returned tool
            $detailPeminjamanList = $peminjaman->detailPeminjaman;

            if (is_array($jumlahDikembalikan)) {
                foreach ($jumlahDikembalikan as $index => $jumlah) {
                    $kondisi = is_array($kondisiAlat) ? ($kondisiAlat[$index] ?? 'baik') : $kondisiAlat;
                    $idAlat = $request->id_alat[$index] ?? null;

                    if (!$idAlat || $jumlah <= 0) {
                        continue;
                    }

                    $detail = $detailPeminjamanList->firstWhere('id_alat', $idAlat);

                    if (!$detail) {
                        continue;
                    }

                    // Update stock based on condition
                    $alat = $detail->alat;

                    // If the tool is damaged, don't add it back to available stock
                    if ($kondisi === 'baik') {
                        $alat->update(['stok' => $alat->stok + $jumlah]);
                    }

                    // Update the overall condition of the equipment
                    if ($kondisi === 'rusak') {
                        $alat->update(['kondisi' => 'rusak']);
                    }
                }
            }

            // Log activity dengan detail denda
            $dendaInfo = '';
            if ($totalDenda > 0) {
                $dendaParts = [];
                if ($dendaKeterlambatan > 0) {
                    $hariTerlambat = Carbon::parse($peminjaman->tanggal_kembali_rencana)->diffInDays($tanggalKembali);
                    $dendaParts[] = "Keterlambatan {$hariTerlambat} hari = Rp " . number_format($dendaKeterlambatan, 0, ',', '.');
                }
                if ($dendaKerusakan > 0) {
                    $dendaParts[] = "Kerusakan = Rp " . number_format($dendaKerusakan, 0, ',', '.');
                }
                $dendaInfo = ' (Denda: ' . implode(', ', $dendaParts) . ')';
            }
            
            $this->logActivity("Memproses pengembalian peminjaman {$peminjaman->user->nama} dengan kondisi {$overallCondition}{$dendaInfo}");

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengembalian berhasil diproses!',
                    'id_peminjaman' => $peminjaman->id_peminjaman
                ]);
            }

            return redirect()->route('petugas.pengembalian')->with('success', 'Pengembalian berhasil diproses!');
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memproses pengembalian: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Gagal memproses pengembalian: ' . $e->getMessage()]);
        }
    }

    public function laporan()
    {
        // Get statistics
        $totalPeminjaman = Peminjaman::count();
        $totalDipinjam = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();
        $totalDitolak = Peminjaman::where('status', 'ditolak')->count();
        $totalDenda = Pengembalian::sum('denda');
        $totalDendaKeterlambatan = Pengembalian::sum('denda_keterlambatan');
        $totalDendaKerusakan = Pengembalian::sum('denda_kerusakan');

        // Get recent borrowings
        $recentPeminjaman = Peminjaman::with(['user', 'detailPeminjaman.alat'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent returns
        $recentPengembalian = Pengembalian::with(['peminjaman.user'])
            ->orderBy('tanggal_kembali', 'desc')
            ->limit(10)
            ->get();

        // Get monthly statistics (last 6 months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $count = Peminjaman::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $monthlyStats[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        return view('petugas.laporan', compact(
            'totalPeminjaman',
            'totalDipinjam',
            'totalDikembalikan',
            'totalDitolak',
            'totalDenda',
            'totalDendaKeterlambatan',
            'totalDendaKerusakan',
            'recentPeminjaman',
            'recentPengembalian',
            'monthlyStats'
        ));
    }

    public function exportPengembalian()
    {
        // Get all pengembalian data with relations
        $pengembalianList = Pengembalian::with(['peminjaman.user', 'peminjaman.detailPeminjaman.alat'])
            ->orderBy('tanggal_kembali', 'desc')
            ->get();

        // Create CSV content
        $csvData = "ID Pengembalian,ID Peminjaman,Nama Peminjam,Nama Alat,Tanggal Pinjam,Tanggal Kembali Rencana,Tanggal Kembali,Denda Total,Denda Keterlambatan,Denda Kerusakan,Kondisi Alat,Status Denda\n";

        foreach ($pengembalianList as $pengembalian) {
            $namaPeminjam = $pengembalian->peminjaman->user->nama ?? 'N/A';

            // Get all alat names
            $alatNames = [];
            foreach ($pengembalian->peminjaman->detailPeminjaman as $detail) {
                $alatNames[] = $detail->alat->nama_alat;
            }
            $alatList = implode(', ', $alatNames);

            $tanggalPinjam = \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam)->format('d M Y');
            $tanggalKembaliRencana = \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_kembali_rencana)->format('d M Y');
            $tanggalKembali = \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d M Y');
            $dendaTotal = 'Rp ' . number_format($pengembalian->denda, 0, ',', '.');
            $dendaKeterlambatan = 'Rp ' . number_format($pengembalian->denda_keterlambatan ?? 0, 0, ',', '.');
            $dendaKerusakan = 'Rp ' . number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.');
            $kondisi = ucfirst($pengembalian->kondisi_alat);
            $statusDenda = $pengembalian->denda > 0 ? 'Ada Denda' : 'Tidak Ada Denda';

            // Escape double quotes for CSV
            $namaPeminjam = '"' . str_replace('"', '""', $namaPeminjam) . '"';
            $alatList = '"' . str_replace('"', '""', $alatList) . '"';
            $statusDenda = '"' . str_replace('"', '""', $statusDenda) . '"';

            $csvData .= sprintf(
                "PGB%03d,PJN%03d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $pengembalian->id_pengembalian,
                $pengembalian->peminjaman->id_peminjaman,
                $namaPeminjam,
                $alatList,
                $tanggalPinjam,
                $tanggalKembaliRencana,
                $tanggalKembali,
                $dendaTotal,
                $dendaKeterlambatan,
                $dendaKerusakan,
                $kondisi,
                $statusDenda
            );
        }

        // Create response with CSV content
        $response = response($csvData, 200);
        $response->header('Content-Type', 'text/csv; charset=UTF-8');
        $response->header('Content-Disposition', 'attachment; filename="Laporan_Pengembalian_' . date('Y-m-d') . '.csv"');
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');

        return $response;
    }

    public function deletePengembalian($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        // Hapus file foto jika ada
        if ($pengembalian->bukti_foto) {
            $fotoPath = public_path($pengembalian->bukti_foto);
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }

        // Update status peminjaman kembali menjadi 'dipinjam'
        $peminjaman = $pengembalian->peminjaman;
        $peminjaman->update(['status' => 'dipinjam']);

        // Hapus data pengembalian
        $pengembalian->delete();

        // Log activity
        $this->logActivity("Menghapus data pengembalian peminjaman {$peminjaman->user->nama}");

        return redirect()->route('petugas.pengembalian')->with('success', 'Data pengembalian berhasil dihapus!');
    }

    public function confirmReturned($id)
    {
        try {
            $request = \Illuminate\Http\Request::capture();
            $data = json_decode($request->getContent(), true);

            $pengembalian = Pengembalian::findOrFail($id);

            // Hapus file foto jika ada
            if ($pengembalian->bukti_foto) {
                $fotoPath = public_path($pengembalian->bukti_foto);
                if (file_exists($fotoPath)) {
                    unlink($fotoPath);
                }
            }

            // If tool is damaged, log the damage info
            if (isset($data['kondisi']) && $data['kondisi'] === 'rusak') {
                $this->logActivity("Konfirmasi pengembalian ID: {$id} - Alat RUSAK, Persentase: {$data['persen_kerusakan']}%, Denda: Rp " . number_format($data['denda_kerusakan'] ?? 0));
            } else {
                $this->logActivity("Konfirmasi pengembalian ID: {$id} - Alat dalam kondisi BAIK");
            }

            // Hapus data pengembalian
            $pengembalian->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pengembalian berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPengembalianDetails($id)
    {
        try {
            $pengembalian = Pengembalian::with('peminjaman.detailPeminjaman.alat')->findOrFail($id);

            $details = [];
            foreach ($pengembalian->peminjaman->detailPeminjaman as $detail) {
                $details[] = [
                    'id_detail' => $detail->id_detail,
                    'id_alat' => $detail->id_alat,
                    'nama_alat' => $detail->alat->nama_alat,
                ];
            }

            return response()->json([
                'success' => true,
                'details' => $details
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Check if peminjaman can be deleted
        // Allow deleting active borrowings (dipinjam/terlambat) - will auto-return
        // Also allow deleting pending/rejected borrowings
        $canDelete = in_array($peminjaman->status, ['menunggu persetujuan', 'ditolak', 'dipinjam', 'terlambat']);
        
        if (!$canDelete) {
            return redirect()->route('petugas.peminjaman')->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat dihapus!');
        }

        // If peminjaman is active (dipinjam/terlambat), restore stock first
        if (in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            // Restore stock for all borrowed items
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $alat = $detail->alat;
                if ($alat) {
                    $alat->update(['stok' => $alat->stok + $detail->jumlah]);
                }
            }

            // Delete detail peminjaman
            $peminjaman->detailPeminjaman()->delete();

            // Delete peminjaman
            $peminjaman->delete();

            $this->logActivity("Menghapus peminjaman aktif ID: PJN" . str_pad($id, 3, '0', STR_PAD_LEFT) . " - Stok dikembalikan");
        } else {
            // For pending/rejected, just delete details and peminjaman
            $peminjaman->detailPeminjaman()->delete();
            $peminjaman->delete();

            $this->logActivity("Menghapus peminjaman ID: PJN" . str_pad($id, 3, '0', STR_PAD_LEFT));
        }

        return redirect()->route('petugas.peminjaman')->with('success', 'Peminjaman berhasil dihapus!');
    }
}