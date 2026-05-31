<?php

namespace App\Http\Controllers;

use App\Models\Textile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExplorationMapController extends Controller
{
    /** Halaman peta upcycler */
    public function index()
    {
        $available = Textile::where('status', 'available')
            ->with('owner')
            ->latest()
            ->get();

        return view('upcycler.exploration-map', compact('available'));
    }

    /** API: ambil semua data limbah untuk marker peta (dipakai upcycler & contributor) */
    public function wasteMap(Request $request)
    {
        $textiles = Textile::with('owner')
            ->select('id','title','fabric_type','weight','address','latitude','longitude','status','user_id','claimed_by','created_at','image')
            ->latest()
            ->get()
            ->map(function ($t) {
                return [
                    'id'        => $t->id,
                    'title'     => $t->title,
                    'fabric'    => $t->fabric_type,
                    'weight'    => (float) $t->weight,
                    'address'   => $t->address,
                    'latitude'  => (float) $t->latitude,
                    'longitude' => (float) $t->longitude,
                    'status'    => $t->status,
                    'owner'     => $t->owner?->name ?? '—',
                    'whatsapp'  => $t->owner?->whatsapp ?? null,
                    'created'   => $t->created_at?->diffForHumans(),
                    'mine'      => Auth::id() === $t->user_id,
                    'claimed_by_me' => Auth::id() === $t->claimed_by,
                    'image'     => $t->image ? asset('storage/'.$t->image) : null,
                ];
            });

        return response()->json(['success' => true, 'data' => $textiles]);
    }

    /** API: klaim limbah oleh upcycler */
    public function claim(Request $request)
    {
        $request->validate(['textile_id' => 'required|integer|exists:textiles,id']);

        $textile = Textile::with('owner')->findOrFail($request->textile_id);

        if ($textile->status !== 'available') {
            return response()->json(['success' => false, 'message' => 'Limbah ini sudah tidak tersedia.']);
        }

        $textile->update([
            'status'     => 'claimed',
            'claimed_by' => Auth::id(),
            'claimed_at' => now(),
        ]);

        $owner = $textile->owner;
        $wa    = $owner?->whatsapp;
        $waUrl = $wa ? 'https://wa.me/62' . ltrim($wa, '0') . '?text=' . urlencode(
            "Halo {$owner->name}, saya dari UpcycleMatch ingin mengklaim limbah kain \"{$textile->title}\". Kapan bisa dijemput?"
        ) : null;

        return response()->json([
            'success'  => true,
            'message'  => 'Limbah berhasil diklaim!',
            'whatsapp' => [
                'name'    => $owner?->name ?? '—',
                'number'  => $wa ?? 'Tidak tersedia',
                'address' => $textile->address ?? 'Lihat di peta',
                'url'     => $waUrl,
            ],
        ]);
    }

    /** Halaman peta contributor (lihat status limbah sendiri) */
    public function contributorMap()
    {
        return view('contributor.peta-map');
    }
}
