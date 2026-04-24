# PT Sejahtera Tani

PT Sejahtera Tani adalah aplikasi web berbasis Laravel untuk pengelolaan operasional internal, pencatatan transaksi, absensi karyawan, data master, serta ringkasan produksi dan laporan keuangan.


## Ringkasan Proyek

- Framework utama: Laravel 11
- Bahasa: PHP 8.2+
- Arsitektur: MVC dengan Blade template
- Database: MySQL / MariaDB / kompatibel
- Export laporan: PDF via DomPDF
- Target pengguna: admin, staff, dan finance

## Fitur Utama

### 1. Autentikasi dan Hak Akses
- Login berbasis role
- Pemisahan akses untuk admin, staff, dan finance
- Proteksi route sesuai peran pengguna

### 2. Dashboard
- Dashboard admin untuk pemantauan data utama
- Dashboard staff untuk alur kerja operasional
- Dashboard finance untuk ringkasan keuangan

### 3. Manajemen Data Master
- Users
- Karyawan
- Produk
- Pemasok
- COA / akun akuntansi
- Mata uang
- Rekening

### 4. Operasional dan Produksi
- Absensi karyawan
- Absensi harian
- Hasil produksi
- Pengelolaan stok barang

### 5. Keuangan dan Transaksi
- Transaksi utama
- Detail transaksi
- Jurnal dan detail jurnal
- Arus kas
- Export laporan PDF

### 6. UX dan Portfolio Readiness
- Public landing page
- Tampilan dashboard yang lebih rapi
- Helper dan IDE support untuk mengurangi false error di editor

## Stack Teknologi

- Laravel 11
- PHP 8.2+
- Blade
- Eloquent ORM
- Bootstrap / custom dashboard styling
- DomPDF
- Vite untuk asset pipeline

## Struktur Folder Utama

- app/Http/Controllers — logika aplikasi dan workflow per role
- app/Models — model Eloquent
- app/Support — helper global dan helper controller untuk editor support
- resources/views — tampilan Blade
- routes/web.php — definisi route dan pembagian akses role
- database/migrations — struktur tabel database
- stubs/ — file stub untuk membantu analyzer / IDE

## Role Pengguna

### Admin
- Mengelola seluruh data master
- Mengelola user
- Mengelola absensi, transaksi, jurnal, rekening, dan hasil produksi

### Staff
- Mengelola data operasional harian
- Input absensi karyawan
- Input hasil produksi
- Mengelola transaksi operasional staff

### Finance
- Melihat dan mengelola informasi finansial
- Membaca ringkasan transaksi dan arus kas
- Mendukung proses pelaporan keuangan

## Instalasi

### 1. Install dependency PHP

```bash
composer install
```

### 2. Install dependency frontend

```bash
npm install
```

### 3. Salin file environment

```bash
copy .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Konfigurasi database

Atur koneksi database di file .env:

- DB_CONNECTION
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD

### 6. Jalankan migrasi

```bash
php artisan migrate
```

Jika tersedia data awal, jalankan seeder:

```bash
php artisan db:seed
```

### 7. Build asset frontend

```bash
npm run build
```

## Menjalankan Project

### Mode development

```bash
php artisan serve
```

### Jika menggunakan Vite

```bash
npm run dev
```

## Alur Penggunaan Singkat

1. Login sesuai role pengguna.
2. Admin mengelola data master dan monitoring.
3. Staff menginput absensi, transaksi, dan produksi.
4. Finance memantau data transaksi dan arus kas.
5. Laporan dapat diekspor ke PDF bila diperlukan.

## Modul dan Route Penting

- routes/web.php — route utama dan pembagian akses role
- app/Http/Controllers/UsersController.php — manajemen user admin
- app/Http/Controllers/DashboardController.php — dashboard admin
- app/Http/Controllers/Staff/* — modul operasional staff
- app/Models/BaseModel.php — dukungan static analysis untuk Eloquent

## Catatan Implementasi

- Project ini telah disesuaikan agar lebih siap dijadikan portfolio.
- Beberapa helper dan stub ditambahkan untuk membantu editor memahami helper Laravel dan method Eloquent.
- Validasi, redirect, dan view handling dibuat lebih konsisten.
- Struktur kode difokuskan agar mudah dipelihara dan dikembangkan lebih lanjut.

## Pengembangan Lanjutan yang Disarankan

- Tambahkan data seed demo agar mudah dipresentasikan.
- Tambahkan screenshot dashboard dan halaman CRUD.
- Tambahkan test untuk alur penting seperti login, transaksi, dan absensi.
- Tambahkan role management yang lebih granular jika dibutuhkan.
- Tambahkan logging dan audit trail untuk aktivitas penting.

## Deployment

Sebelum deployment, pastikan:

- .env production sudah benar
- APP_KEY terisi
- database sudah termigrasi
- storage link sudah dibuat jika diperlukan
- asset frontend sudah dibuild

```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
