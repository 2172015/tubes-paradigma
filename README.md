# PIXELATE - Online POS System

Pixelate adalah sistem Point of Sale (POS) dan E-Commerce berbasis web yang dibangun menggunakan Framework Laravel. Aplikasi ini memungkinkan pelanggan untuk memesan produk secara online dan admin untuk mengelola produk, transaksi, serta melihat laporan penjualan.

## 🚀 Fitur Utama

- **Autentikasi & Otorisasi:** Login Multi-user (Admin & Customer) menggunakan Enums.
- **Manajemen Produk:** CRUD Produk, Upload Gambar, dan Soft Deletes (Arsip Produk).
- **Keranjang Belanja:** Menambah produk, update quantity, dan hapus item.
- **Transaksi & Checkout:** - Upload bukti pembayaran.
  - Validasi stok saat checkout.
  - Kode Invoice Unik (Anti-duplikat).
- **Status Pesanan:** Alur status (Pending -> Shipping -> Completed/Canceled).
- **Laporan (Admin):** Laporan pendapatan harian/bulanan dan produk terlaris.
- **Manajemen User & Promo:** Admin dapat mengelola user dan kode diskon.

## 🛠️ Persyaratan Sistem

Sebelum menjalankan proyek, pastikan komputer Anda telah terinstal:
- PHP >= 8.1
- Composer
- Database MySQL (XAMPP / Laragon)
- Web Browser

## 📦 Cara Instalasi & Menjalankan Project

Karena folder `vendor` tidak disertakan dalam file .zip ini, ikuti langkah berikut untuk menginstalnya kembali:

1. **Ekstrak File**
   Ekstrak file `.zip` proyek ke folder web server Anda (misal: `htdocs` atau folder kerja lainnya).

2. **Buka Terminal / Command Prompt**
   Arahkan terminal ke folder proyek tersebut.

3. **Install Dependencies**
   Jalankan perintah berikut untuk mengunduh library Laravel yang dibutuhkan:
   ```bash
   composer install