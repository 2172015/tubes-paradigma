<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;
use Carbon\Carbon;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. PROMO NEW YEAR 1.1 (Sudah Lewat / Expired)
        // Bagus untuk tes validasi "Promo Kadaluarsa"
        Promo::create([
            'code' => 'NEWYEAR1.1',
            'discount_percent' => 50,
            'start_date' => Carbon::create(2026, 1, 1),
            'end_date' => Carbon::create(2026, 1, 3),
            'is_active' => 1,
        ]);

        // 2. PROMO 2.2 (Akan Datang)
        // Bagus untuk tes validasi "Promo Belum Dimulai"
        Promo::create([
            'code' => 'LOVE2.2',
            'discount_percent' => 22,
            'start_date' => Carbon::create(2026, 2, 1),
            'end_date' => Carbon::create(2026, 2, 3),
            'is_active' => 1,
        ]);

        // 3. PROMO SPESIAL HARI INI (Aktif & Valid)
        // Gunakan ini saat DEMO presentasi agar berhasil checkout
        Promo::create([
            'code' => 'PIXELATE2026',
            'discount_percent' => 10, // Diskon 10%
            'start_date' => Carbon::now()->subDays(1), // Mulai kemarin
            'end_date' => Carbon::now()->addDays(7),   // Berakhir minggu depan
            'is_active' => 1,
        ]);

        // 4. PROMO LEBARAN (Masa Depan)
        Promo::create([
            'code' => 'BERKAHRAMADAN',
            'discount_percent' => 30,
            'start_date' => Carbon::create(2026, 3, 10),
            'end_date' => Carbon::create(2026, 4, 15),
            'is_active' => 1,
        ]);

        // 5. PROMO KEMERDEKAAN 17 AGUSTUS
        Promo::create([
            'code' => 'MERDEKA45',
            'discount_percent' => 17, // 17 Agustus
            'start_date' => Carbon::create(2026, 8, 1),
            'end_date' => Carbon::create(2026, 8, 31),
            'is_active' => 1,
        ]);

        // 6. PROMO HARBOLNAS 12.12 (Diskon Besar)
        Promo::create([
            'code' => 'HARBOLNAS12.12',
            'discount_percent' => 90, // Diskon Gila-gilaan
            'start_date' => Carbon::create(2026, 12, 10),
            'end_date' => Carbon::create(2026, 12, 15),
            'is_active' => 1,
        ]);

        // 7. PROMO NON-AKTIF (Admin menonaktifkan manual)
        // Bagus untuk tes validasi "Promo Tidak Aktif"
        Promo::create([
            'code' => 'GAGALDISKON',
            'discount_percent' => 50,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(30),
            'is_active' => 0, // Sengaja dimatikan
        ]);
    }
}