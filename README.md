# PIXELATE - Online POS System

Pixelate adalah sistem Point of Sale (POS) dan E-Commerce berbasis web yang dibangun menggunakan Framework Laravel. Aplikasi ini memungkinkan pelanggan untuk memesan produk secara online dan admin untuk mengelola produk, transaksi, serta melihat laporan penjualan.

## Fitur Utama

- **Autentikasi & Otorisasi:** Login Multi-user (Admin & Customer) menggunakan Enums.
- **Manajemen Produk:** CRUD Produk, Upload Gambar, dan Soft Deletes (Arsip Produk).
- **Keranjang Belanja:** Menambah produk, update quantity, dan hapus item.
- **Transaksi & Checkout:** - Upload bukti pembayaran.
  - Validasi stok saat checkout.
  - Kode Invoice Unik (Anti-duplikat).
- **Status Pesanan:** Alur status (Pending -> Shipping -> Completed/Canceled).
- **Laporan (Admin):** Laporan pendapatan harian/bulanan dan produk terlaris.
- **Manajemen User & Promo:** Admin dapat mengelola user dan kode diskon.

## Persyaratan Sistem

Sebelum menjalankan proyek, pastikan komputer Anda telah terinstal:
* **PHP** (Minimal versi 8.1)
* **Composer** (Package Manager untuk PHP)
* **Node.js & NPM** (Untuk kompilasi aset frontend)
* **Database Server** (MySQL via XAMPP)
* **Web Browser** (Chrome/Edge/Firefox)

## Cara Instalasi & Menjalankan Project

Ekstrak file `.zip` proyek ke folder yang anda inginkan.
Ikuti urutan langkah di bawah ini secara teliti agar aplikasi berjalan dengan lancar.

### 1. Install Composer
Jika komputer Anda belum memiliki **Composer**, silakan download dan install terlebih dahulu melalui link resmi: https://getcomposer.org/download/


### 2. Install Backend Dependencies
Karena folder `vendor` dihapus, Anda harus mengunduh ulang library Laravel. Buka terminal/CMD di folder proyek, lalu jalankan:
``` bash
composer install
```

### 3. Konfigurasi Environment
Salin file `.env.example` dan rename menjadi `.env`. Setelah itu generate application key dengan menjalan:
``` bash
php artisan key:generate
```

### 4. Konfigurasi Database
Buka **phpMyAdmin** lalu buka database baru dengan nama `onlineposdb`. Setelah itu bukan file `.env` dengan text editor dan ubah bagian database menjadi berikut
``` bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=onlineposdb
DB_USERNAME=root
DB_PASSWORD=
```
Isi `DB_PASSWORD` jika MySQL anda menggunakan password.

### 5. Migrasi Database & Seeding
Jalankan ini di terminal:
``` bash
php artisan migrate:fresh --seed
```

### 6. Install Node Module dan Run
Jalankan ini di terminal:
``` bash
npm install
npm run dev
```

atau setelah menjalankan `npm install` jalankan perintah berikut:
``` bash
npm run dev
```

### 7. Link Storage Gambar
Karena aplikasi ini menyimpan file gambar, buka terminal baru dan jalankan perintah berikut:
``` bash
php artisan storage:link
```
Jika mendapat error `link already exists` maka tidak perlu dijalankan ulang.
Jika saat menjalankan aplikasi dan mendapatkan error bahwa gambar tidak muncul, silahkan hapus folder `public/storage` dan jalankan perintah itu lagi.

### 8. Jalankan Server
Pastikan **XAMPP (Apache & MySQL)** sudah berjalan. Selanjutnya jalankan server Laravel:
``` bash
php artisan serve
```

### 9. Buka Aplikasi
Aplikasi sekarang dapat diakses di browser melalui alamat: `http://127.0.0.1:8000`

### 10. Akun Login Demo
| Role | Email | Password |
| :--- | :--- | :--- |
| Administrator | `admin@admin.com` | `password` |
| Customer | `customer@gmail.com` | `password` |

Silahkan gunakan akun diatas ini untuk menjalankan demo, atau bisa registrasi untuk membuat akun customer sendiri. Untuk mencegah penyebaran informasi, disarankan untuk tidak menggunakan email dan password pribadi.

## Konsep OOP & Arsitektur Aplikasi

Proyek ini dibangun dengan menerapkan prinsip **Object-Oriented Programming (OOP)** untuk menjaga kode tetap bersih, mudah dibaca, dan mudah dikembangkan. Berikut adalah implementasinya:

### 1. Model-View-Controller (MVC)
Arsitektur utama memisahkan logika aplikasi menjadi tiga komponen:
- **Model:** Mengelola data dan logika database (`app/Models`).
- **View:** Menangani tampilan antarmuka pengguna (`resources/views`).
- **Controller:** Menghubungkan Model dan View (`app/Http/Controllers`).
- **Entry Point:** Semua request diatur melalui `routes/web.php`.

### 2. PHP Enums
- **Lokasi:** `app/Enums/`
- **Implementasi:** `UserRole` untuk hak akses dan `TransactionStatus` untuk status pesanan.

### 3. Encapsulation & Helper Methods
Membungkus logika pengecekan di dalam Model untuk menyembunyikan kompleksitas dari Controller/View.
- **Contoh:** Method `isAdmin()` dan `isCustomer()` pada Model `User`.
- **Penggunaan:** Kita cukup memanggil `Auth::user()->isAdmin()` daripada menulis logika `Auth::user()->role === 'admin'` berulang kali.

### 4. Form Requests
Logika validasi input dipisahkan dari Controller ke kelas khusus (*Request Class*).
- **Lokasi:** `app/Http/Requests/`
- **Implementasi:** `StoreProductRequest` dan `UpdateProductRequest`.

### 5. Reusable Object
Menggunakan component yang disediakan `Blade Component` untuk membuat **`product card`**, sehingga pembuatan product card tidak manual melalui html, namun menggunakan **component**.

### 6. Soft Deletes
Menggunakan *Trait* bawaan Laravel untuk menangani penghapusan data secara aman. Produk yang dihapus tidak hilang permanen dari database (hanya disembunyikan), sehingga riwayat transaksi lama tetap aman.
- **Implementasi:** Trait `SoftDeletes` pada Model `Product`.

## Referensi
**Source code project:** https://github.com/2172015/tubes-paradigma

**Aset gambar:** 
1. https://static.vecteezy.com/system/resources/thumbnails/001/849/553/small/modern-gold-background-free-vector.jpg
2. https://freedesignfile.com/111607-modern-business-logos-design-art-vector-01/#google_vignette
3. https://www.shutterstock.com/image-vector/simple-owl-logo-vector-01-2715790511?irclickid=wTP3z9XZfxycUJ%3AwPLXbIwu0UkpXnUXNe0HESY0&irgwc=1&afsrc=1&pl=46057-560528&utm_medium=Affiliate&utm_campaign=Khayriddin%20Sodiqov&utm_source=46057&utm_term=&utm_content=560528

