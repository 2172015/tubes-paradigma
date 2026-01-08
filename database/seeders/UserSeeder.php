<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun ADMIN (Owner)
        User::updateOrCreate(
            ['email' => 'admin@admin.com'], // Cek email ini dulu
            [
                'name' => 'Owner Toko',
                'password' => Hash::make('password'), // Password default: 'password'
                'role' => 'admin',
                'phone_number' => '081234567890',
                'address' => 'Kantor Pusat Online POS, Jakarta',
            ]
        );

        // 2. Akun CUSTOMER (Pelanggan Contoh)
        User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Budi Pelanggan',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone_number' => '089876543210',
                'address' => 'Jl. Mawar No. 10, Bandung',
            ]
        );
        
        // // 3. (Opsional) Buat 5 customer acak tambahan pakai Factory
        // User::factory(5)->create([
        //     'role' => 'customer'
        // ]);
    }
}