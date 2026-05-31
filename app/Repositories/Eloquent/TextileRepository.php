<?php
 
namespace App\Repositories\Eloquent;
 
use App\Models\Textile;
use App\Repositories\Contracts\TextileRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
 
class TextileRepository implements TextileRepositoryInterface
{
    protected Textile $model;
 
    public function __construct(Textile $model)
    {
        $this->model = $model;
    }
 
    /**
     * Ambil semua limbah berstatus available beserta relasi pemilik.
     */
    public function getAllAvailable(): Collection
    {
        return $this->model
            ->with(['owner'])
            ->where('status', 'available')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest()
            ->get();
    }
 
    /**
     * Simpan postingan limbah baru dari contributor.
     */
    public function store(array $data): Textile
    {
        return $this->model->create($data);
    }
 
    /**
     * Klaim limbah: ubah status dan catat ID upcycler.
     * Return false jika sudah diklaim orang lain.
     */
    public function claimTextile(int $id, int $userId): bool
    {
        $textile = $this->model->findOrFail($id);
 
        // Cegah race condition: hanya klaim jika masih available
        if ($textile->status !== 'available') {
            return false;
        }
 
        return $textile->update([
            'status'     => 'claimed',
            'claimed_by' => $userId,
        ]);
    }
 
    /**
     * Ambil semua limbah milik contributor tertentu dengan tracking status.
     */
    public function getByContributor(int $userId): Collection
    {
        return $this->model
            ->with(['upcycler'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }
 
    /**
     * Ambil semua limbah yang sedang dikelola upcycler tertentu.
     */
    public function getByUpcycler(int $userId): Collection
    {
        return $this->model
            ->with(['owner'])
            ->where('claimed_by', $userId)
            ->latest()
            ->get();
    }
 
    /**
     * Update status limbah dan opsional simpan foto produk jadi.
     */
    public function updateStatus(int $id, string $status, ?string $productImage = null): bool
    {
        $textile = $this->model->findOrFail($id);
        $data    = ['status' => $status];
 
        if ($productImage) {
            $data['product_image'] = $productImage;
        }
 
        return $textile->update($data);
    }
 
    /**
     * Hitung total berat limbah yang sudah diselesaikan (status = completed).
     */
    public function getTotalSavedWeight(): float
    {
        return (float) $this->model
            ->whereIn('status', ['completed', 'processing', 'claimed'])
            ->sum('weight');
    }
 
    /**
     * Ambil semua data untuk panel admin.
     */
    public function getAll(): Collection
    {
        return $this->model
            ->with(['owner', 'upcycler'])
            ->latest()
            ->get();
    }
}