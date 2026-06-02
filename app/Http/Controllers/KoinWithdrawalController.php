<?php

namespace App\Http\Controllers;

use App\Models\KoinWithdrawal;
use App\Models\KoinTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KoinWithdrawalController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'jumlah_koin'    => 'required|integer|min:4',
            'metode'         => 'required|in:bank,ewallet',
            'nama_platform'  => 'required|string|max:100',   // nama bank atau nama ewallet
            'nomor_rekening' => 'required|string|max:50',
            'nama_penerima'  => 'required|string|max:100',
        ], [
            'jumlah_koin.min'    => 'Minimal pencairan adalah 4 koin (Rp10.000).',
            'jumlah_koin.required' => 'Jumlah koin wajib diisi.',
            'metode.required'    => 'Pilih metode pencairan.',
            'nama_platform.required' => 'Nama bank / e-wallet wajib diisi.',
            'nomor_rekening.required' => 'Nomor rekening / nomor HP wajib diisi.',
            'nama_penerima.required'  => 'Nama penerima wajib diisi.',
        ]);

        // Validasi saldo setelah validation lainnya
        if ($user->koin < $data['jumlah_koin']) {
            return back()
                ->withErrors(['jumlah_koin' => 'Saldo koin tidak mencukupi. Koin Anda: ' . $user->koin])
                ->withInput()
                ->withFragment('wallet');
        }

        DB::transaction(function () use ($user, $data) {
            $nominalRupiah = $data['jumlah_koin'] * 2500;

            // Kurangi koin dari akun user
            $user->koin -= $data['jumlah_koin'];
            $user->save();

            // Catat transaksi koin
            KoinTransaction::create([
                'user_id'    => $user->id,
                'amount'     => -$data['jumlah_koin'],
                'keterangan' => 'Pencairan ' . $data['jumlah_koin'] . ' koin ke ' .
                                $data['nama_platform'] . ' (' . $data['nomor_rekening'] . ')',
            ]);

            // Simpan permintaan withdrawal
            KoinWithdrawal::create([
                'user_id'        => $user->id,
                'jumlah_koin'    => $data['jumlah_koin'],
                'nominal_rupiah' => $nominalRupiah,
                'metode'         => $data['metode'],
                'nama_bank'      => $data['nama_platform'],
                'nomor_rekening' => $data['nomor_rekening'],
                'nama_penerima'  => $data['nama_penerima'],
                'status'         => 'pending',
            ]);

            Log::channel('single')->info('💰 PERMINTAAN PENCAIRAN KOIN', [
                'user'           => $user->name . ' (ID: ' . $user->id . ')',
                'jumlah_koin'    => $data['jumlah_koin'],
                'nominal_rupiah' => 'Rp' . number_format($nominalRupiah, 0, ',', '.'),
                'metode'         => $data['metode'],
                'platform'       => $data['nama_platform'],
                'nomor_rekening' => $data['nomor_rekening'],
                'nama_penerima'  => $data['nama_penerima'],
            ]);
        });

        return back()
            ->with('success', '✅ Pencairan ' . $data['jumlah_koin'] . ' koin (Rp' . number_format($data['jumlah_koin'] * 2500, 0, ',', '.') . ') berhasil diajukan! Admin akan memproses dalam 1×24 jam.')
            ->withFragment('wallet');
    }

    // Admin: list semua withdrawal
    public function adminIndex()
    {
        $withdrawals = KoinWithdrawal::with('user')
            ->latest()
            ->paginate(20);

        $stats = [
            'pending'  => KoinWithdrawal::where('status', 'pending')->count(),
            'approved' => KoinWithdrawal::where('status', 'approved')->count(),
            'rejected' => KoinWithdrawal::where('status', 'rejected')->count(),
            'total_rp' => KoinWithdrawal::where('status', 'approved')->sum('nominal_rupiah'),
        ];

        return view('admin.withdrawals', compact('withdrawals', 'stats'));
    }

    // Admin: approve
    public function approve(KoinWithdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan pending yang bisa disetujui.');
        }

        $withdrawal->update([
            'status'       => 'approved',
            'processed_at' => now(),
            'catatan_admin' => request('catatan_admin'),
        ]);

        return back()->with('success', 'Pencairan Rp' . number_format($withdrawal->nominal_rupiah, 0, ',', '.') . ' untuk ' . $withdrawal->user->name . ' telah disetujui.');
    }

    // Admin: reject (kembalikan koin)
    public function reject(KoinWithdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan pending yang bisa ditolak.');
        }

        DB::transaction(function () use ($withdrawal) {
            // Kembalikan koin ke user
            $withdrawal->user->increment('koin', $withdrawal->jumlah_koin);

            // Catat pengembalian koin
            KoinTransaction::create([
                'user_id'    => $withdrawal->user_id,
                'amount'     => $withdrawal->jumlah_koin,
                'keterangan' => 'Pengembalian koin — pencairan ditolak admin' .
                                (request('catatan_admin') ? ': ' . request('catatan_admin') : ''),
            ]);

            $withdrawal->update([
                'status'        => 'rejected',
                'processed_at'  => now(),
                'catatan_admin' => request('catatan_admin'),
            ]);
        });

        return back()->with('success', 'Pencairan ditolak dan ' . $withdrawal->jumlah_koin . ' koin dikembalikan ke ' . $withdrawal->user->name . '.');
    }
}
