<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Pengembalian;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Carbon\Carbon;


class PeminjamanController extends Controller
{
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

    public function index()
    {
        $user = Auth::user();

        // Get statistics for the dashboard
        $totalPeminjaman = Peminjaman::where('id_user', $user->id_user)->count();
        $belumDikembalikan = Peminjaman::where('id_user', $user->id_user)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();
        $sudahDikembalikan = Peminjaman::where('id_user', $user->id_user)
            ->where('status', 'dikembalikan')
            ->count();
        $terlambat = Peminjaman::where('id_user', $user->id_user)
            ->where('status', 'terlambat')
            ->count();

        // Calculate total denda from pengembalian with breakdown
        $totalDenda = Pengembalian::join('peminjaman', 'pengembalian.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->where('peminjaman.id_user', $user->id_user)
            ->sum('pengembalian.denda');
        
        $totalDendaKeterlambatan = Pengembalian::join('peminjaman', 'pengembalian.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->where('peminjaman.id_user', $user->id_user)
            ->sum('pengembalian.denda_keterlambatan');
        
        $totalDendaKerusakan = Pengembalian::join('peminjaman', 'pengembalian.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->where('peminjaman.id_user', $user->id_user)
            ->sum('pengembalian.denda_kerusakan');

        return view('peminjam.dashboard', compact('user', 'totalPeminjaman', 'belumDikembalikan', 'sudahDikembalikan', 'terlambat', 'totalDenda', 'totalDendaKeterlambatan', 'totalDendaKerusakan'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('peminjam.profile', compact('user'));
    }

    public function settings()
    {
        return view('peminjam.settings');
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        
        // Get peminjaman with pengembalian relation
        $query = Peminjaman::with(['detailPeminjaman.alat', 'pengembalian'])
            ->where('id_user', $user->id_user)
            ->orderBy('updated_at', 'asc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pinjam', $request->tanggal);
        }

        // Filter by equipment name
        if ($request->filled('cari')) {
            $query->whereHas('detailPeminjaman.alat', function($q) use ($request) {
                $q->where('nama_alat', 'like', '%' . $request->cari . '%');
            });
        }

        $peminjamanList = $query->paginate(10);

        return view('peminjam.history', compact('peminjamanList'));
    }

    public function tools()
    {
        // Get all tools with stock > 0
        $alatList = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->get();
        
        // Group by nama_alat manually
        $groupedAlat = $alatList->groupBy('nama_alat');
        
        $kategoris = Kategori::all();

        return view('peminjam.tools', compact('groupedAlat', 'kategoris'));
    }

    public function borrow(Request $request, $id = null)
    {
        if ($id) {
            // If ID is provided, show borrow form for specific tool
            $alat = Alat::with('kategori')->findOrFail($id);
            return view('peminjam.borrow', compact('alat'));
        } else {
            // Redirect to tools page if no specific tool is selected
            return redirect()->route('peminjam.tools');
        }
    }

    public function showBorrowForm(Request $request, $id = null)
    {
        // Check if multiple alat IDs are passed via query parameter
        if ($request->has('alat')) {
            $alatIds = explode(',', $request->alat);
            $alatList = Alat::with('kategori')->whereIn('id_alat', $alatIds)->get();
            
            // Group by nama_alat
            $groupedAlatList = $alatList->groupBy('nama_alat');
            
            // Get available items for each grouped tool
            foreach ($groupedAlatList as $namaAlat => $tools) {
                $alatTersedia = Alat::where('nama_alat', $namaAlat)
                    ->with('kategori')
                    ->orderBy('kode_barang', 'asc')
                    ->get(['id_alat', 'nama_alat', 'kode_barang', 'lokasi', 'stok', 'kondisi', 'id_kategori']);

                $firstTool = $alatTersedia->first();

                // Store firstTool data explicitly for the view
                $groupedAlatList[$namaAlat]->alatTersedia = $alatTersedia;
                $groupedAlatList[$namaAlat]->firstTool = $firstTool;
            }
            
            return view('peminjam.ajukan_peminjaman', compact('groupedAlatList'));
        }

        if ($id) {
            // If ID is provided, show borrow form for specific tool
            $alat = Alat::with('kategori')->findOrFail($id);
            
            // Get available items for this tool name (all items with same name)
            $alat->alatTersedia = Alat::where('nama_alat', $alat->nama_alat)
                ->orderBy('kode_barang', 'asc')
                ->get(['id_alat', 'nama_alat', 'kode_barang', 'lokasi', 'stok', 'kondisi']);
            
            return view('peminjam.ajukan_peminjaman', compact('alat'));
        } else {
            // Show form with equipment selection if no specific tool is selected
            $alatList = Alat::with('kategori')->where('stok', '>', 0)->get();
            return view('peminjam.ajukan_peminjaman', compact('alatList'));
        }
    }

    public function storeBorrow(Request $request)
    {
        // Handle both single alat (old format) and multiple alat (new format)
        if ($request->has('alat')) {
            // Check if it's single or multiple alat
            $alatIds = array_keys($request->input('alat', []));
            
            if (count($alatIds) > 1 || is_array($request->input('kode_barang'))) {
                // Multiple alat format
                return $this->storeBorrowMultiple($request);
            }
            
            // Single alat in new format (from grouped tools)
            // Convert to old format for validation
            $idAlat = $alatIds[0];
            $request->merge([
                'id_alat' => $idAlat,
                'jumlah' => $request->alat[$idAlat],
            ]);
        }

        $request->validate([
            'id_alat' => 'required|exists:alat,id_alat',
            'kode_barang' => 'required|string|exists:alat,kode_barang',
            'jumlah' => 'required|integer|min:1',
            'tanggal_kembali_rencana' => 'required|date|after:today',
            'alasan' => 'required|string|max:1000',
        ]);

        // Validate that the kode_barang belongs to the id_alat
        $alat = Alat::where('id_alat', $request->id_alat)
            ->where('kode_barang', $request->kode_barang)
            ->first();

        if (!$alat) {
            return redirect()->back()->withErrors(['error' => "Kode barang '{$request->kode_barang}' tidak cocok dengan alat yang dipilih"]);
        }

        if ($alat->stok < $request->jumlah) {
            return redirect()->back()->withErrors(['jumlah' => "Jumlah alat {$alat->nama_alat} ({$request->kode_barang}) yang tersedia tidak mencukupi"]);
        }

        DB::beginTransaction();
        try {
            // Use today's date automatically for tanggal_pinjam
            $tanggalPinjam = \Carbon\Carbon::today();
            $tanggalKembaliRencana = \Carbon\Carbon::parse($request->tanggal_kembali_rencana);

            // Create the borrowing record with status "menunggu persetujuan"
            $peminjaman = Peminjaman::create([
                'tanggal_pinjam' => $tanggalPinjam->toDateString(),
                'tanggal_kembali_rencana' => $tanggalKembaliRencana->toDateString(),
                'status' => 'menunggu persetujuan', // New status as per requirements
                'id_user' => Auth::user()->id_user, // Gunakan ID numerik langsung
            ]);

            // Create the detail borrowing record
            DetailPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_alat' => $request->id_alat,
                'kode_barang' => $request->kode_barang,
                'jumlah' => $request->jumlah,
            ]);

            // Log activity
            LogAktivitas::create([
                'id_user' => Auth::user()->id_user,
                'aktivitas' => "Mengajukan peminjaman alat: {$alat->nama_alat} (Jumlah: {$request->jumlah}) - Menunggu persetujuan",
                'waktu' => now(),
            ]);

            DB::commit();

            return redirect()->route('peminjam.pengembalian')->with('success', 'Peminjaman berhasil diajukan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses peminjaman: ' . $e->getMessage()]);
        }
    }

    public function storeBorrowMultiple(Request $request)
    {
        $request->validate([
            'alat' => 'required|array|min:1',
            'alat.*' => 'required|integer|min:1',
            'alat_id' => 'required|array',
            'alat_id.*' => 'required|exists:alat,id_alat',
            'kode_barang' => 'required|array',
            'kode_barang.*' => 'required|string|exists:alat,kode_barang',
            'tanggal_kembali_rencana' => 'required|date|after:today',
            'alasan' => 'required|string|max:1000',
        ]);

        // DEBUG: Log submitted data
        \Log::debug('storeBorrowMultiple - Request Data:', [
            'alat' => $request->alat,
            'alat_id' => $request->alat_id,
            'kode_barang' => $request->kode_barang,
            'all_request_data' => $request->all(),
        ]);

        // Validate stock for each tool and ensure kode_barang matches id_alat
        foreach ($request->alat as $index => $jumlah) {
            // Get the actual id_alat from the alat_id array
            $idAlat = $request->alat_id[$index] ?? null;
            
            if (!$idAlat) {
                return redirect()->back()->withErrors(['error' => "Baris ke-" . ($index + 1) . ": Alat tidak dipilih"]);
            }

            // Get the kode_barang for this index
            $kodeBarang = $request->kode_barang[$index] ?? null;

            \Log::debug('Validation attempt:', [
                'index' => $index,
                'id_alat' => $idAlat,
                'kode_barang' => $kodeBarang,
                'jumlah' => $jumlah,
            ]);

            // Validate that the kode_barang belongs to the id_alat
            $alat = Alat::where('id_alat', $idAlat)
                ->where('kode_barang', $kodeBarang)
                ->first();

            if (!$alat) {
                $foundByKode = Alat::where('kode_barang', $kodeBarang)->first();
                \Log::debug('Mismatch found:', [
                    'submitted_id_alat' => $idAlat,
                    'found_id_alat_by_kode' => $foundByKode ? $foundByKode->id_alat : null,
                    'kode_barang' => $kodeBarang,
                    'jumlah_requested' => $jumlah,
                ]);

                return redirect()->back()->withErrors(['error' => "Kode barang '{$kodeBarang}' tidak cocok dengan alat yang dipilih (ID: {$idAlat})"]);
            }

            if ($alat->stok < $jumlah) {
                return redirect()->back()->withErrors(['alat' => "Stok {$alat->nama_alat} ({$kodeBarang}) tidak mencukupi (tersedia: {$alat->stok})"]);
            }
        }

        DB::beginTransaction();
        try {
            // Use today's date automatically for tanggal_pinjam
            $tanggalPinjam = \Carbon\Carbon::today();
            $tanggalKembaliRencana = \Carbon\Carbon::parse($request->tanggal_kembali_rencana);

            // Create ONE borrowing record for all tools
            $peminjaman = Peminjaman::create([
                'tanggal_pinjam' => $tanggalPinjam->toDateString(),
                'tanggal_kembali_rencana' => $tanggalKembaliRencana->toDateString(),
                'status' => 'menunggu persetujuan',
                'id_user' => Auth::user()->id_user,
                'alasan' => $request->alasan,
            ]);

            // Create detail borrowing record for EACH tool with kode_barang
            foreach ($request->alat as $index => $jumlah) {
                // Get the actual id_alat from alat_id array
                $idAlat = $request->alat_id[$index] ?? null;
                $kodeBarang = $request->kode_barang[$index] ?? null;

                // Find the exact alat record that matches both id_alat and kode_barang
                $alat = Alat::where('id_alat', $idAlat)
                    ->where('kode_barang', $kodeBarang)
                    ->firstOrFail();

                // Create detail for this specific alat
                DetailPeminjaman::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_alat' => $alat->id_alat,
                    'kode_barang' => $kodeBarang,
                    'jumlah' => $jumlah,
                ]);
            }

            // Log activity - get all tool names
            $alatNames = [];
            foreach ($request->alat as $idAlat => $jumlah) {
                // Handle kode_barang that could be string or array
                $kodeBarang = null;
                if (is_array($request->kode_barang)) {
                    $kodeBarang = $request->kode_barang[$idAlat] ?? null;
                } else {
                    $kodeBarang = $request->kode_barang;
                }

                $alat = Alat::where('id_alat', $idAlat)
                    ->where('kode_barang', $kodeBarang)
                    ->first();
                
                if ($alat) {
                    $alatNames[] = "{$alat->nama_alat} - {$kodeBarang} ({$jumlah})";
                }
            }
            
            LogAktivitas::create([
                'id_user' => Auth::user()->id_user,
                'aktivitas' => "Mengajukan peminjaman alat: " . implode(', ', $alatNames) . " - Menunggu persetujuan",
                'waktu' => now(),
            ]);

            DB::commit();

            $alatCount = count($request->alat);
            $message = $alatCount > 1
                ? "Peminjaman berhasil diajukan! ({$alatCount} alat)"
                : 'Peminjaman berhasil diajukan!';

            return redirect()->route('peminjam.pengembalian')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses peminjaman: ' . $e->getMessage()]);
        }
    }

    public function pengembalian()
    {
        $user = Auth::user();
        $peminjamanAktif = Peminjaman::with(['detailPeminjaman.alat', 'pengembalian'])
            ->where('id_user', $user->id_user)
            ->whereIn('status', ['dipinjam', 'terlambat', 'menunggu persetujuan'])
            ->get();

        return view('peminjam.return', compact('peminjamanAktif'));
    }

    public function storeReturn(Request $request)
    {
        // Log semua request yang masuk untuk debugging
        \Log::info('storeReturn called with data:', $request->all());
        \Log::info('Request headers:', ['ajax' => $request->ajax(), 'expectsJson' => $request->expectsJson()]);
        
        try {
            $request->validate([
                'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
                'kondisi_alat' => 'required', // Accept array format
                'kondisi_alat.*' => 'required|in:baik,rusak', // Validate each item
                'jumlah_dikembalikan' => 'required', // Accept array format
                'jumlah_dikembalikan.*' => 'required|integer|min:1', // Validate each item
                'id_alat' => 'required|array',
                'id_alat.*' => 'required|exists:alat,id_alat',
                'deskripsi_kerusakan' => 'nullable|array',
                'deskripsi_kerusakan.*' => 'nullable|string',
                'bukti_foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120', // Max 5MB
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', ['errors' => $e->errors()]);
            
            if ($request->expectsJson() || $request->ajax()) {
                // Flatten error messages manually
                $errorMessages = [];
                foreach ($e->errors() as $fieldErrors) {
                    $errorMessages = array_merge($errorMessages, $fieldErrors);
                }
                
                return response()->json([
                    'error' => 'Validasi gagal: ' . implode(', ', $errorMessages)
                ], 422);
            }
            
            throw $e;
        }

        $peminjamanId = $request->id_peminjaman;

        if (!$peminjamanId) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Tidak ada peminjaman yang ditentukan'], 400);
            }
            return redirect()->back()->withErrors(['error' => 'Tidak ada peminjaman yang ditentukan']);
        }

        $user = Auth::user();
        \Log::info('User info:', ['user_id' => $user->id_user, 'user_role' => $user->role ?? 'unknown']);
        
        $peminjaman = Peminjaman::findOrFail($peminjamanId);
        \Log::info('Peminjaman found:', ['id' => $peminjaman->id_peminjaman, 'user_id' => $peminjaman->id_user, 'status' => $peminjaman->status]);

        if ($peminjaman->id_user != $user->id_user) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Anda tidak berhak mengembalikan peminjaman ini'], 403);
            }
            return redirect()->back()->withErrors(['error' => 'Anda tidak berhak mengembalikan peminjaman ini']);
        }

        if ($peminjaman->status !== 'dipinjam' && $peminjaman->status !== 'terlambat') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Peminjaman ini tidak dalam status aktif'], 400);
            }
            return redirect()->back()->withErrors(['error' => 'Peminjaman ini tidak dalam status aktif']);
        }

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

        // Handle file upload
        $buktiFotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            \Log::info('File uploaded:', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'error' => $file->getError()
            ]);

            // Generate unique filename
            $filename = 'bukti_pengembalian_' . $peminjamanId . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store in public directory
            $destinationPath = public_path('uploads/bukti_pengembalian');
            \Log::info('Destination path:', ['path' => $destinationPath]);

            // Create directory if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
                \Log::info('Created directory:', ['path' => $destinationPath]);
            }

            // Move file
            $file->move($destinationPath, $filename);
            $buktiFotoPath = 'uploads/bukti_pengembalian/' . $filename;

            \Log::info('Foto uploaded successfully: ' . $buktiFotoPath);
        } else {
            \Log::info('No file uploaded');
        }

        DB::beginTransaction();
        try {
            // Calculate fines
            $tanggalKembali = Carbon::now();
            $detailPeminjamanList = $peminjaman->detailPeminjaman;
            
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
                'deskripsi_kerusakan' => $overallCondition === 'rusak' ? (is_array($deskripsiKerusakan) ? json_encode($deskripsiKerusakan) : $deskripsiKerusakan) : null,
                'bukti_foto' => $buktiFotoPath,
                'id_peminjaman' => $peminjaman->id_peminjaman,
            ]);

            \Log::info('Pengembalian created successfully:', [
                'id' => $pengembalian->id_pengembalian ?? 'new',
                'bukti_foto' => $buktiFotoPath
            ]);

            // Update borrowing status
            $peminjaman->update(['status' => 'dikembalikan']);
            \Log::info('Peminjaman status updated to dikembalikan');

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

                    // Update the overall condition of the equipment in the alat table
                    if ($kondisi === 'rusak') {
                        $alat->update(['kondisi' => 'rusak']);
                    }
                }
            }

            // Log activity dengan detail denda
            $alatNames = [];
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $alatNames[] = $detail->alat->nama_alat;
            }
            
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

            LogAktivitas::create([
                'id_user' => Auth::user()->id_user,
                'aktivitas' => "Mengembalikan alat: " . implode(', ', $alatNames) . " - Kondisi: {$overallCondition}{$dendaInfo}",
                'waktu' => now(),
            ]);

            DB::commit();

            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengembalian berhasil diproses!',
                    'bukti_foto' => $buktiFotoPath
                ]);
            }

            return redirect()->route('peminjam.history')->with('success', 'Pengembalian berhasil diproses!');
        } catch (\Exception $e) {
            DB::rollback();
            
            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memproses pengembalian: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses pengembalian: ' . $e->getMessage()]);
        }
    }

    public function struk($id)
    {
        $user = Auth::user();
        $peminjaman = Peminjaman::with(['detailPeminjaman.alat', 'pengembalian'])
            ->where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        return view('peminjam.struk', compact('peminjaman'));
    }
}