<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Textile;
use App\Models\LimbahKain;
use App\Models\KoinTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LimbahController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'       => 'required|string|max:255',
            'jenis_bahan' => 'required|in:katun,denim,sutra,polyester,lainnya',
            'berat_kg'    => 'required|numeric|min:0.1|max:9999',
            'deskripsi'   => 'nullable|string|max:1000',
            'alamat'      => 'nullable|string|max:255',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        DB::transaction(function () use ($data, $request) {
            $user = $request->user();

            // Upload foto jika ada
            $imagePath = null;
            if ($request->hasFile('foto')) {
                $imagePath = $request->file('foto')->store('limbah', 'public');
            }

            // Simpan ke tabel TEXTILES (dibaca upcycler di peta)
            Textile::create([
                'user_id'     => $user->id,
                'title'       => $data['judul'],
                'fabric_type' => $data['jenis_bahan'],
                'description' => $data['deskripsi'] ?? null,
                'weight'      => $data['berat_kg'],
                'address'     => $data['alamat'] ?? null,
                'latitude'    => $data['latitude'] ?? null,
                'longitude'   => $data['longitude'] ?? null,
                'image'       => $imagePath,
                'status'      => 'available',
            ]);

            // Tambah +2 koin
            $user->increment('koin', 2);

            // Catat riwayat koin
            KoinTransaction::create([
                'user_id'    => $user->id,
                'amount'     => 2,
                'type'       => 'upload',
                'keterangan' => 'Upload limbah: ' . $data['judul'],
            ]);
        });

        return back()->with('success', 'Limbah berhasil diposting dan langsung muncul di peta upcycler! +2 koin ditambahkan.');
    }

    public function tracker(Request $request)
    {
        $limbahList = LimbahKain::where('user_id', Auth::id())->latest()->get();
        return view('contributor.tracker', compact('limbahList'));
    }
}