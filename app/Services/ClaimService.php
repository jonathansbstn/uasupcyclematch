<?php

namespace App\Services;

use App\Models\Textile;
use App\Models\WasteClaim;
use Illuminate\Support\Facades\DB;

class ClaimService
{
    /**
     * Klaim limbah dengan DB Transaction dan pessimistic locking.
     * Return ['success' => bool, 'message' => string, 'textile' => Textile|null]
     */
    public function claim(int $textileId, int $upcyclerId): array
    {
        try {
            return DB::transaction(function () use ($textileId, $upcyclerId) {
                // Pessimistic lock — cegah race condition
                $textile = Textile::lockForUpdate()->find($textileId);

                if (!$textile) {
                    return ['success' => false, 'message' => 'Limbah tidak ditemukan.', 'textile' => null];
                }

                if ($textile->status !== 'available') {
                    return [
                        'success' => false,
                        'message' => 'Maaf, limbah ini sudah diklaim oleh pengguna lain.',
                        'textile' => null,
                    ];
                }

                $now = now();

                // Update status textile
                $textile->update([
                    'status'     => 'claimed',
                    'claimed_by' => $upcyclerId,
                    'claimed_at' => $now,
                ]);

                // Catat di tabel waste_claims
                WasteClaim::create([
                    'textile_id'  => $textileId,
                    'upcycler_id' => $upcyclerId,
                    'claimed_at'  => $now,
                ]);

                // Load relasi owner untuk WhatsApp shortcut
                $textile->load('owner');

                return ['success' => true, 'message' => 'Berhasil diklaim!', 'textile' => $textile];
            });
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage(), 'textile' => null];
        }
    }

    /**
     * Update status produksi: claimed → processing → completed
     */
    public function updateStatus(int $textileId, int $upcyclerId, string $newStatus): array
    {
        $textile = Textile::where('id', $textileId)
            ->where('claimed_by', $upcyclerId)
            ->first();

        if (!$textile) {
            return ['success' => false, 'message' => 'Data tidak ditemukan atau akses ditolak.'];
        }

        $allowedTransitions = [
            'claimed'    => 'processing',
            'processing' => 'completed',
        ];

        if (!isset($allowedTransitions[$textile->status]) || $allowedTransitions[$textile->status] !== $newStatus) {
            return ['success' => false, 'message' => 'Transisi status tidak valid.'];
        }

        $textile->update(['status' => $newStatus]);

        return ['success' => true, 'message' => 'Status berhasil diperbarui.', 'textile' => $textile];
    }
}
