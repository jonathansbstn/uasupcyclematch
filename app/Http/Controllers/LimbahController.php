<?php

namespace App\Http\Controllers;

// 1. WAJIB IMPORT SEMUA CLASS DI BAWAH INI AGAR ERROR VS CODE HILANG!
use Illuminate\Http\Request;
use App\Models\LimbahKain;
use App\Models\KoinTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LimbahController extends Controller 
{
    public function store(Request $request) 
    {
        // 2. Validasi input data dari form mockup
        $data = $request->validate([
            'judul'       => 'required|string|max:255',
            'jenis_bahan' => 'required|in:katun,denim,sutra,polyester',
            'berat_kg'    => 'required|numeric|min:0.1',
            'deskripsi'   => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        // 3. Gunakan DB Transaction agar jika salah satu gagal, database tidak corrupt
        DB::transaction(function () use ($data, $request) {
            
            // Simpan data limbah kain terhubung dengan user_id kontributor yang login
            $limbah = LimbahKain::create([
                'user_id'     => Auth::id(),
                'judul'       => $data['judul'],
                'jenis_bahan' => $data['jenis_bahan'],
                'berat_kg'    => $data['berat_kg'],
                'deskripsi'   => $data['deskripsi'] ?? null,
                'latitude'    => $data['latitude'] ?? null,
                'longitude'   => $data['longitude'] ?? null,
                'status'      => 'available',
            ]);

            // Tambah +2 koin ke record user kontributor
            $user = $request->user();
            $user->increment('koin', 2);

            // Catat riwayat ke tabel koin_transactions
            KoinTransaction::create([
                'user_id'    => $user->id,
                'amount'     => 2,
                'type'       => 'upload',
                'keterangan' => 'Upload limbah: ' . $limbah->judul,
            ]);
        });

        return back()->with('success', 'Limbah berhasil diposting! +2 koin ditambahkan ke wallet.');
    }

    public function tracker(Request $request) 
    {
        // Mengambil riwayat limbah khusus milik kontributor yang sedang login
        $limbahList = LimbahKain::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('contributor.tracker', compact('limbahList'));
    }
}