<?php
 
namespace App\Repositories\Contracts;
 
interface TextileRepositoryInterface
{
    /**
     * Ambil semua limbah yang berstatus 'available'.
     */
    public function getAllAvailable(): \Illuminate\Database\Eloquent\Collection;
 
    /**
     * Simpan data limbah baru ke database.
     */
    public function store(array $data): \App\Models\Textile;
 
    /**
     * Klaim limbah oleh upcycler: ubah status jadi 'claimed' dan isi claimed_by.
     */
    public function claimTextile(int $id, int $userId): bool;
 
    /**
     * Ambil semua limbah milik contributor tertentu.
     */
    public function getByContributor(int $userId): \Illuminate\Database\Eloquent\Collection;
 
    /**
     * Ambil semua limbah yang diklaim oleh upcycler tertentu.
     */
    public function getByUpcycler(int $userId): \Illuminate\Database\Eloquent\Collection;
 
    /**
     * Update status limbah (processing / completed).
     */
    public function updateStatus(int $id, string $status, ?string $productImage = null): bool;
 
    /**
     * Hitung total berat limbah yang berhasil diselamatkan (status completed).
     */
    public function getTotalSavedWeight(): float;
 
    /**
     * Ambil semua data limbah untuk admin.
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection;
}