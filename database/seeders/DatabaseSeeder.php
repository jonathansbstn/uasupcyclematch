<?php

namespace Database\Seeders;

use App\Models\Textile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Jalankan: php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        // ── Buat akun Admin ───────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin UpcycleMatch',
            'email'    => 'admin@upcycle.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ── Buat akun Contributor (Masyarakat pemberi limbah) ─────────────────
        $budi = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'contributor',
        ]);

        // ── Buat akun Upcycler (UMKM Penjahit) ───────────────────────────────
        $taylor = User::create([
            'name'     => 'Taylor Jahit Kreatif',
            'email'    => 'taylor@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'upcycler',
        ]);

        // ── Buat Dummy Data Textiles ──────────────────────────────────────────
        // Koordinat asli berbagai kota di Indonesia

        // 1. Limbah Denim di Jakarta Selatan - MASIH AVAILABLE
        Textile::create([
            'user_id'     => $budi->id,
            'title'       => 'Celana Jeans Lama & Potongan Denim',
            'fabric_type' => 'denim',
            'description' => 'Kumpulan 5 celana jeans lama sudah tidak terpakai, kondisi masih bagus hanya agak pudar. Cocok dijadikan tas, dompet, atau aksesori.',
            'weight'      => 3.50,
            'address'     => 'Jl. Kebayoran Lama No. 12, Jakarta Selatan',
            'latitude'    => -6.2607,
            'longitude'   => 106.8017,
            'status'      => 'available',
            'claimed_by'  => null,
        ]);

        // 2. Limbah Katun di Bandung - SUDAH DIKLAIM
        Textile::create([
            'user_id'     => $budi->id,
            'title'       => 'Sisa Kain Perca Katun Garmen',
            'fabric_type' => 'katun',
            'description' => 'Sisa produksi garmen rumahan, berbagai warna dan motif. Berat total sekitar 5 kg.',
            'weight'      => 5.00,
            'address'     => 'Jl. Cibaduyut No. 8, Bandung',
            'latitude'    => -6.9175,
            'longitude'   => 107.6191,
            'status'      => 'claimed',
            'claimed_by'  => $taylor->id,
        ]);

        // 3. Limbah Sutra di Yogyakarta - DALAM PROSES PRODUKSI
        Textile::create([
            'user_id'     => $budi->id,
            'title'       => 'Kain Sutra Batik Sisa Pembatik',
            'fabric_type' => 'sutra',
            'description' => 'Sisa kain sutra dari pembatik tradisional Yogyakarta. Motif batik kawung dan parang.',
            'weight'      => 1.80,
            'address'     => 'Jl. Malioboro No. 54, Yogyakarta',
            'latitude'    => -7.7956,
            'longitude'   => 110.3695,
            'status'      => 'processing',
            'claimed_by'  => $taylor->id,
        ]);

        // 4. Limbah Polyester di Surabaya - AVAILABLE
        Textile::create([
            'user_id'     => $budi->id,
            'title'       => 'Pakaian Bekas Polyester Campuran',
            'fabric_type' => 'polyester',
            'description' => 'Kumpulan baju olahraga dan jaket polyester tidak terpakai.',
            'weight'      => 4.20,
            'address'     => 'Jl. Raya Darmo No. 15, Surabaya',
            'latitude'    => -7.2575,
            'longitude'   => 112.7521,
            'status'      => 'available',
            'claimed_by'  => null,
        ]);

        // 5. Limbah Denim di Medan - COMPLETED
        Textile::create([
            'user_id'      => $budi->id,
            'title'        => 'Jeans & Kemeja Denim Bekas',
            'fabric_type'  => 'denim',
            'description'  => 'Kemeja denim dan jeans bekas kondisi layak pakai.',
            'weight'       => 6.00,
            'address'      => 'Jl. Pemuda No. 22, Medan',
            'latitude'     => 3.5952,
            'longitude'    => 98.6722,
            'status'       => 'completed',
            'claimed_by'   => $taylor->id,
            'product_image'=> null,
        ]);

        $this->command->info('✅ Seeder berhasil! Data berikut telah dibuat:');
        $this->command->info('   👤 Admin    : admin@upcycle.com / password');
        $this->command->info('   👤 Contributor: budi@gmail.com / password');
        $this->command->info('   👤 Upcycler : taylor@gmail.com / password');
        $this->command->info('   📦 5 data limbah tekstil dengan berbagai status');
    }
}