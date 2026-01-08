<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan folder tujuan di storage publik tersedia
        $destinationPath = storage_path('app/public/products');
        
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // 2. Daftar Data Dummy (Sesuaikan dengan nama file 1.jpg s/d 5.jpg)
        $products = [
            [
                'image_source' => '1.png', // Pastikan file ini ada di database/seeders/img/
                'name' => 'The Fat Lamb',
                'price' => 150000,
                'stock' => 999,
                'description' => 'Design vintage sangat cocok digunakan untuk bisnis F&B.',
            ],
            [
                'image_source' => '2.png',
                'name' => 'GUM',
                'price' => 75000,
                'stock' => 999,
                'description' => 'Design vintage shop dengan warna pink dan hijau yang menarik.',
            ],
            [
                'image_source' => '3.png',
                'name' => 'Drip Haus',
                'price' => 250000,
                'stock' => 999,
                'description' => 'Design tropical dengan warna biru sebagai outline text.',
            ],
            [
                'image_source' => '4.png',
                'name' => 'Urban Style',
                'price' => 45000,
                'stock' => 999,
                'description' => 'Design yang sangat menarik untuk style streetwear. Sangat cocok digunakan untuk fashion brand.',
            ],
            [
                'image_source' => '5.png',
                'name' => 'Urban Style 2',
                'price' => 120000,
                'stock' => 50,
                'description' => 'Design yang sangat menarik untuk urban style streetwear.',
            ],
        ];

        // 3. Loop untuk eksekusi
        foreach ($products as $item) {
            // Tentukan path sumber (Source) dan tujuan (Target)
            $sourceFile = database_path('seeders/img/' . $item['image_source']);
            $targetFile = 'products/' . $item['image_source']; // Ini path untuk database

            // Cek apakah file sumber ada
            if (File::exists($sourceFile)) {
                // Copy file dari folder seeder ke folder storage public
                // Kita gunakan copy PHP native agar lebih mudah kontrol path-nya
                File::copy($sourceFile, storage_path('app/public/' . $targetFile));
                
                // Simpan ke Database
                Product::create([
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'description' => $item['description'],
                    'image' => $targetFile, // Simpan path relatif: products/1.jpg
                ]);
            } else {
                $this->command->warn("File gambar {$item['image_source']} tidak ditemukan di folder database/seeders/img/");
            }
        }
    }
}