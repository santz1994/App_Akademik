# Penilaian Pengabdian - Sistem Manajemen Kinerja

Aplikasi manajemen penilaian kinerja pengabdian karyawan untuk **Yayasan Pondok Pesantren Al-Huda Mugomulyo**.

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Role & Akses](#role--akses)
- [Instalasi](#instalasi)
- [Panduan Penggunaan](#panduan-penggunaan)
- [Error & Solusi](#error--solusi)
- [Teknologi](#teknologi)

---

## Fitur Utama

### 1. Dashboard
- Statistik karyawan, penilaian, dan progres
- Filter per pangkalan untuk kepala
- Grafik dan ringkasan data

### 2. Manajemen Karyawan
- CRUD data karyawan (nama, NIK, alamat, foto, dll)
- Multi-pangkalan assignment (1 karyawan bisa di beberapa pangkalan)
- Status aktif/nonaktif
- Import dari Excel/CSV
- Profil PDF

### 3. Data Master
- **Pangkalan Job**: Data lokasi/unit kerja dengan kepala pangkalan
- **Kategori Kinerja**: Kategori penilaian (kinerja & kegiatan)
- **Kompetensi**: Indikator penilaian per kategori
- **Performance Rating**: Grade A-E dengan range nilai
- **Tahun Penilaian**: Periode penilaian aktif

### 4. Penilaian Karyawan
- Input nilai per kompetensi per pangkalan
- Multi-pangkalan: karyawan dinilai terpisah per pangkalan
- Lock/unlock sistem untuk keamanan data
- Request unlock oleh kepala, approval oleh admin
- Batch unlock untuk admin

### 5. Laporan
- **Laporan Keseluruhan**: Rekap semua karyawan, per-pangkalan
- **Laporan Perorangan**: Detail nilai per karyawan (ringkas & rinci)
- Export: PDF, Excel, CSV
- Format cetak configurable (margin, font, orientasi)
- Keterangan reward/punishment otomatis

### 6. Reward & Punishment
- Konfigurasi reward per grade (A, B)
- Konfigurasi punishment per grade (C, D, E)
- Otomatis muncul di laporan berdasarkan nilai akhir

### 7. Database Backup & Restore
- Backup database ke file SQL
- Restore dari file SQL
- Download & hapus file backup

### 8. Mutasi
- Mutasi antar tahun ajaran
- Mutasi antar pangkalan

### 9. Setting Lembaga
- Informasi lembaga (nama, alamat, kontak)
- Logo & tanda tangan
- Format laporan (kolom, margin, font)
- Sidebar text & visibility

---

## Role & Akses

| Role | Akses |
|------|-------|
| **Admin** | Semua fitur: kelola data, input nilai, laporan, setting, backup |
| **Kepala Pimpinan Pos** | Input nilai karyawan di pangkalannya, laporan pangkalannya |
| **Tata Usaha** | Lihat laporan (keseluruhan & perorangan), unlock penilaian, cetak |
| **User/Karyawan** | Lihat laporan penilaian diri sendiri, edit profil |

### Keterangan:
- Kepala ditentukan dari data **Pangkalan** (field Kepala Pimpinan Pos)
- Tata Usaha tidak bisa input/edit nilai, hanya bisa melihat & unlock
- User biasa hanya bisa melihat nilainya sendiri

---

## Instalasi

### Prerequisites
- PHP 8.2+
- MySQL/MariaDB
- Composer
- Node.js (untuk asset)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repo-url>
cd penilaian-pengabdian

# 2. Install dependencies
composer install
npm install

# 3. Copy .env
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ypphmugo_apk
DB_USERNAME=root
DB_PASSWORD=

# 5. Import database
mysql -u root -p ypphmugo_apk < ypphmugo_apk.sql

# 6. Jalankan migrasi
php artisan migrate

# 7. Link storage
php artisan storage:link

# 8. Clear cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 9. Jalankan server
php artisan serve
```

### Default Login
| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | (sesuai database) |

---

## Panduan Penggunaan

### Input Penilaian
1. Login sebagai **Admin** atau **Kepala**
2. Buka menu **Transaksi > Penilaian Karyawan**
3. Klik **Input Nilai**
4. Pilih **Karyawan**, **Pangkalan Job**, dan **Tahun Ajaran**
5. Isi nilai untuk setiap indikator kompetensi (0-100)
6. Klik **Simpan**

### Multi-Pangkalan
- Karyawan yang terdaftar di beberapa pangkalan akan dinilai **terpisah per pangkalan**
- Setiap pangkalan memiliki kategori kinerja yang berbeda sesuai mapping
- Nilai akhir = rata-rata kinerja per pangkalan + kegiatan wajib

### Laporan
1. Buka menu **Laporan**
2. Filter: Tahun, Pangkalan, Jenis (Ringkas/Rinci)
3. Klik **Cetak**, **PDF**, **Excel**, atau **CSV**
4. Laporan perorangan: pilih karyawan terlebih dahulu

### Backup Database
1. Buka menu **Pengaturan > Database Backup**
2. Klik **Backup Sekarang**
3. File backup tersimpan di server, bisa di-download

---

## Error & Solusi

### 1. Error 419: Page Expired
**Penyebab:** CSRF token expired karena sesi habis.
**Solusi:**
- Klik tombol **Login Kembali** di halaman error
- Atau tekan F5 lalu login ulang
- Pastikan `SESSION_DRIVER=file` di `.env`

### 2. Error 500: Internal Server Error
**Penyebab:** Kesalahan kode atau database.
**Solusi:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
chmod -R 777 storage bootstrap/cache
```

### 3. Foto Tidak Muncul
**Penyebab:** Storage belum di-link.
**Solusi:**
```bash
php artisan storage:link
```

### 4. Data Hilang Setelah Cache Clear
**Penyebab:** Restore database dari dump lama.
**Solusi:** Selalu buat backup terbaru sebelum restore. Gunakan menu Database Backup.

### 5. Redirect Loop
**Penyebab:** Konfigurasi session/auth bermasalah.
**Solusi:**
```bash
php artisan config:clear
# Pastikan SESSION_DRIVER=file di .env
```

### 6. Import Gagal
**Penyebab:** Format file tidak sesuai.
**Solusi:** Gunakan template yang disediakan di menu Help. Format: .xlsx, .xls, .csv.

---

## Teknologi

- **Framework:** Laravel 11
- **Database:** MySQL/MariaDB
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **PDF:** DomPDF
- **Excel:** Maatwebsite Excel
- **Image:** Intervention Image

---

## Struktur Database

| Tabel | Fungsi |
|-------|--------|
| `users` | Akun user (admin, kepala, tata_usaha, user) |
| `karyawan` | Data karyawan |
| `karyawan_pangkalan` | Relasi karyawan ↔ pangkalan (many-to-many) |
| `pangkalan` | Data pangkalan/job |
| `kepala_pangkalan` | Relasi kepala ↔ pangkalan |
| `kategori_kinerja` | Kategori penilaian |
| `kompetensi` | Indikator penilaian |
| `pangkalan_kategori_kinerja` | Mapping pangkalan ↔ kategori |
| `transaksi` | Data nilai penilaian |
| `penilaian_locks` | Status lock penilaian |
| `tahun_penilaian` | Periode penilaian |
| `setting_lembaga` | Konfigurasi aplikasi |
| `reward_punishment` | Konfigurasi reward & punishment |

---

## License

Proprietary - Yayasan Pondok Pesantren Al-Huda Mugomulyo
