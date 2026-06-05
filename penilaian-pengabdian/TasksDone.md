# Daftar Pengerjaan Tugas

> Kolom **Status** diisi oleh Anda setelah mereview.
> **Sesuai** / **Tidak Sesuai** + catatan di Keterangan.

## Update
| No | Tugas | Status | Keterangan |
|----|-------|--------|------------|
| 1 | Logo di halaman login | Sesuai | Logo dari Setting Lembaga. Default icon. |
| 2 | Captcha dinamis | Sesuai | 4 digit random, warna beda, validasi server. |

## Repair Bugs
| No | Tugas | Status | Keterangan |
|----|-------|--------|------------|
| 1 | Auto-search tanpa Terapkan | Sesuai | Debounce 500ms, select auto-submit. |
| 2 | Laporan perorangan Portrait A4 | Sesuai | Ringkas/Rinci, multi-pangkalan, per-pangkalan scoring. PDF ringkas + rinci terpisah. |
| 3 | Laporan keseluruhan terpotong Ctrl+P | Sesuai | Ditambah @media print CSS: force landscape, font kecil, table-layout fixed. |
| 4 | Foto user dari karyawan | Sesuai | Topbar foto karyawan. |
| 5 | Semua pencarian auto-submit | Sesuai | Karyawan, Transaksi, Laporan. |
| 6 | Sidemenu Semua Data Karyawan | Sesuai | Karyawan Aktif, Kepala, Semua. |
| 7 | Profil PDF hapus foto text | Sesuai | Row foto & kolom keterangan dihapus. |
| 8 | Sidebar responsive 1366x768 | Sesuai | max-height, overflow-y, media query. |
| 9 | Collapse sidebar | Sesuai | CSS !important + cubic-bezier transition 0.3s. Text hidden, only icons. |
| 10 | Dashboard progress aktif | Sesuai | Filter is_active=true. |
| 11 | Lock setelah kepala nilai | Sesuai | Pre-save lock check. |
| 12 | Pisah halaman laporan keseluruhan & perorangan | Sesuai | Route /laporan/perorangan terpisah. Controller + view baru. |
| 13 | Multi-bagian | Sesuai | Multiple select pangkalan di user & karyawan create/edit. getAllPangkalanIds() di laporan. |
| 14 | Mutasi 2 jenis | Sesuai | Tahun Ajaran + Antar Pangkalan. |
| 15 | Field email, no_hp, kontak_darurat | Sesuai | Migration + form. |
| 16 | Aktivasi akun + email login | Sesuai | activate.blade.php + email auth. |
| 17 | User self-update | Sesuai | profile.blade.php. |
| 18 | Hapus bobot di laporan | Sesuai | Badge bobot dihapus. |
| 19 | Bobot configurable | Sesuai | PenilaianMetodeController. |
| 20 | Login email/username + remember me | Sesuai | Support login pakai email atau username. Remember me checkbox. |

## Bug Fix Tambahan
| No | Tugas | Status | Keterangan |
|----|-------|--------|------------|
| 1 | Session corruption setelah 404 | Sesuai | Session driver database → file. Prevent session lock/corruption. |
| 2 | Redirect loop / ↔ /login | Sesuai | Root route cek Auth::check(), redirect ke dashboard sesuai role. |
| 3 | 404 pada /admin/, /kepala/, /user/ | Sesuai | Tambah root redirect ke dashboard masing-masing prefix. |
| 4 | Custom 404 error page | Sesuai | errors/404.blade.php standalone tanpa session dependency. |
| 5 | @else/@endif orphaned di index.blade.php | Sesuai | Hapus @else dan @endif sisa perorangan yang dihapus. |
| 6 | Database backup | Sesuai | mysqldump UTF-8 tanpa BOM via --result-file. |

## Sidebar Menu
| No | Menu | Status |
|----|------|--------|
| 1 | Dashboard | Sesuai |
| 2 | Setting Lembaga | Sesuai |
| 3 | User Account | Sesuai |
| 4 | Karyawan > Aktif, Kepala, Semua | Sesuai |
| 5 | Tahun Penilaian | Sesuai |
| 6 | Data > Kategori, Pangkalan, Kompetensi, Rating | Sesuai |
| 7 | Mutasi > Tahun Ajaran, Antar Pangkalan | Sesuai |
| 8 | Transaksi > Penilaian, Bobot, Unlock | Sesuai |
| 9 | Laporan > Keseluruhan, Perorangan, Format | Sesuai |
| 10 | FAQ > Help, FAQ | Sesuai |

## Perubahan Fix Terakhir
- LaporanScoreCalculator: tambah method calculatePerPangkalan() untuk scoring per-pangkalan + rata-rata multi-pangkalan
- LaporanController: peroranganPdf & buildPeroranganData gunakan calculatePerPangkalan, load pangkalanLain relation
- LaporanController: peroranganPdf routing ke perorangan_ringkas_pdf vs perorangan_pdf berdasarkan jenis_laporan
- LaporanController: tambah method perorangan(), kepalaPerorangan(), buildPeroranganPageData()
- index.blade.php: hapus mode=perorangan, hanya keseluruhan + perdireksi
- perorangan.blade.php: view baru standalone laporan perorangan
- perorangan_pdf.blade.php: multi-pangkalan grouping, summary pakai perPangkalanData
- perorangan_ringkas_pdf.blade.php: view baru PDF ringkas (kinerja per pangkalan, kegiatan per kategori)
- Laporan PDF: @media print CSS (landscape, font kecil, table-layout fixed)
- Sidebar CSS: !important pada display:none, cubic-bezier transition 0.3s
- Sidebar: link perorangan ke route terpisah admin.laporan.perorangan
- KaryawanController: tambah validasi pangkalan_tambahan + sync pivot
- UserManagementController: tambah validasi pangkalan_tambahan + sync pivot
- Karyawan create/edit: multi-select pangkalan tambahan
- User create/edit: multi-select pangkalan tambahan
- routes/web.php: tambah Auth facade import, root redirect cek role
- routes/web.php: tambah /admin/, /kepala/, /user/ root redirect ke dashboard
- bootstrap/app.php: revert exception handler (hapus redirect loop)
- .env: SESSION_DRIVER=database → SESSION_DRIVER=file
- errors/404.blade.php: custom 404 page standalone

## Perbaikan Multi-Pangkalan & Filter Aktif (2026-06-05)
- PangkalanJob utama dibuat multiple select: hapus field "Pangkalan Tambahan", ganti dengan single multi-select "Pangkalan Job"
- Karyawan model: tambah `pangkalans()` relationship (many-to-many via pivot), `syncPangkalan()` method, update `getAllPangkalanIds()` gunakan pivot table
- KaryawanController: store/update handle `pangkalan_ids[]` multi-select, sync ke pivot table, hapus `pangkalan_tambahan`
- Karyawan create/edit view: single multi-select "Pangkalan Job" menggantikan "Pangkalan Utama" + "Pangkalan Tambahan"
- Karyawan index view: tampilkan semua pangkalan dari pivot table di kolom Pangkalan Job
- Penilaian (TransaksiController): filter karyawan aktif saja (`is_active=true`) di create, kepalaCreate, kepalaIndex, dan default index
- Pangkalan data: tambah `is_active` field (migration + model), filter aktif by default, tambah toggle status
- PangkalanController: tambah `is_active` ke validasi store/update, tambah `toggleStatus()` method
- Pangkalan create/edit view: tambah field Status (Aktif/Tidak Aktif)
- Pangkalan index view: tambah filter status, kolom Status, tombol toggle aktif/nonaktif
- routes/web.php: tambah route `pangkalan.toggle-status`
- LaporanScoreCalculator: update `resolveMappedKategoriIdsByPangkalan()` untuk aggregate kategori dari semua pangkalan
- LaporanScoreCalculator: update `resolveKategoriUntukKaryawan()` support multi-pangkalan via getAllPangkalanIds()
- LaporanController: load `pangkalans` relation, filter perdireksi gunakan `whereHas('pangkalans', ...)`
- TransaksiController: load `pangkalans` relation di index dan kepalaIndex
- KaryawanController: filter pangkalan gunakan `whereHas('pangkalans', ...)` di index
- PDF profil karyawan: tampilkan semua pangkalan dari pivot table

## Perubahan Kepala Pangkalan Otomatis (2026-06-05)
- Migration: tambah `kepala_user_id` (nullable FK ke users) pada tabel pangkalan
- Pangkalan model: tambah `kepalaUser()` relationship, `kepala_user_id` ke fillable
- PangkalanController: create/edit pass `$userList` untuk dropdown kepala
- PangkalanController: store/update handle `kepala_user_id`, auto-sync `is_kepala` pada User
- PangkalanController: tambah `syncKepalaStatus()` method - set/unset is_kepala berdasarkan assignment
- PangkalanController: sync `kepala_pangkalan` pivot table otomatis saat assign/unassign kepala
- Pangkalan create/edit view: ganti input "Pimpinan Pos" text dengan dropdown "Kepala Pimpinan Pos"
- Pangkalan index view: tampilkan nama kepala dari relasi `kepalaUser`
- UserManagementController: hapus `is_kepala` toggle dari store/update validation
- UserManagementController: hapus `pangkalan_tambahan` dari user forms
- UserManagementController: hapus `syncPimpinanPosByUser()` dari store/update
- UserManagementController: simplify `destroy()` - hapus kepala assignment sebelum delete user
- User create view: hapus "Tetapkan sebagai Kepala Pimpinan Pos" checkbox & pangkalan tambahan
- User edit view: hapus toggle is_kepala, tampilkan badge info kepala jika applicable
- User import: set is_kepala=false (ditentukan dari data pangkalan)

## Jawaban Pertanyaan

### Q: Bagaimana kepala melakukan penilaian untuk karyawan multi-pangkalan?
**A:** Kepala bisa melihat dan menilai karyawan yang ada di pangkalan yang dia pimpin. Sistem sudah mendukung: `kepalaIndex` di TransaksiController menggunakan `whereIn('pangkalan_id', $user->getAllPangkalanIds())`. Jadi kepala bisa menilai semua karyawan yang terdaftar di pangkalan yang dipimpinnya. Karena `pangkalan_id` pada karyawan adalah derived field dari pivot table (pangkalan pertama yang dipilih), maka karyawan yang bekerja di multiple pangkalan akan muncul di penilaian kepala yang memimpin salah satu pangkalan tersebut.

## Perubahan Penilaian Per-Pangkalan (2026-06-05)
- Migration: tambah `pangkalan_id` (nullable FK ke pangkalan) pada tabel transaksi
- Transaksi model: tambah `pangkalan_id` ke fillable, tambah `pangkalan()` relationship
- TransaksiController::create() - tambah dropdown pilih pangkalan, filter existingNilai per pangkalan
- TransaksiController::kepalaCreate() - filter pangkalan berdasarkan kepala yang login
- TransaksiController::store() - validasi `pangkalan_id` required, simpan transaksi dengan `pangkalan_id`
- TransaksiController::resolveLockState() - tambah parameter `$pangkalanId` opsional untuk filter lock per pangkalan
- TransaksiController::resolveKategoriListForPangkalan() - method baru untuk resolve kategori per pangkalan
- Transaksi create view: tambah dropdown "Pangkalan Job" setelah pilih karyawan
- LaporanScoreCalculator::calculatePerPangkalan() - tambah parameter `$trxByPangkalan` untuk filter transaksi per pangkalan
- LaporanController::peroranganPdf() - build `$trxByPangkalan` map, pass ke calculatePerPangkalan
- LaporanController::buildPeroranganData() - build `$trxByPangkalan` map, pass ke calculatePerPangkalan
- Laporan rinci PDF: gunakan `$trxByPangkalan[$pangkalan_id]` untuk tampilan per-pangkalan
- KaryawanController::edit() - fallback ke `pangkalan_id` jika pivot table kosong
- Sync: jalankan `sync:transaksi-pangkalan` untuk data transaksi lama

## Jawaban Pertanyaan Baru

### Q1: Laporan rinci - bagaimana membedakan 2 pangkalan kinerja?
**A:** Sudah diperbaiki. Laporan rinci PDF (`perorangan_pdf.blade.php`) sekarang menggunakan `$trxByPangkalan[$pangkalan_id]` untuk menampilkan nilai per-pangkalan. Setiap pangkalan hanya menampilkan transaksi miliknya, sehingga tidak ada duplikasi nilai antar pangkalan.

### Q2: Penilaian 2 pangkalan - pastikan kepala tidak mengisi 2 form yang sama!
**A:** Sudah diperbaiki. TransaksiController::create() sekarang menampilkan dropdown "Pangkalan Job" yang wajib dipilih sebelum menilai. Setiap penilaian disimpan dengan `pangkalan_id`, sehingga:
- Karyawan dengan 2 pangkalan = 2 set penilaian terpisah
- Kepala hanya bisa memilih pangkalan yang dia pimpin
- Kategori kinerja dimuat berdasarkan mapping pangkalan yang dipilih
- Nilai disimpan per `karyawan_id + pangkalan_id + tahun_penilaian_id + kompetensi_id`

### Q3: Edit karyawan - mengapa hanya 1 pangkalan ter-highlight?
**A:** Sudah diperbaiki. KaryawanController::edit() sekarang memiliki fallback: jika pivot table kosong tetapi `pangkalan_id` ada, maka menggunakan `pangkalan_id` sebagai default. Sehingga karyawan yang belum di-sync ke pivot table tetap menampilkan pangkalan yang benar.

### Q: Bagaimana laporan nilai perorangan karyawan multi-pangkalan?
**A:** Laporan sudah mendukung multi-pangkalan. `LaporanScoreCalculator::calculatePerPangkalan()` menghitung nilai per pangkalan terpisah, lalu merata-ratakan untuk nilai akhir. Laporan perorangan PDF menampilkan breakdown per-pangkalan: kinerja per pangkalan terpisah, kegiatan wajib global. Jadi karyawan multi-pangkalan akan memiliki nilai kinerja yang berbeda per pangkalan, dan nilai akhir adalah rata-rata dari semua pangkalan + kegiatan wajib.

### Q: Apakah pangkalan pada user, karyawan, dan Kepala saling terhubung?
**A:** Ya, sekarang saling terhubung dengan perubahan ini:
- **Pangkalan → Kepala**: `pangkalan.kepala_user_id` → `users.id` (FK langsung)
- **Kepala → Pangkalan**: `users.is_kepala` di-auto-set dari `pangkalan.kepala_user_id`
- **Karyawan → Pangkalan**: `karyawan_pangkalan` pivot table (many-to-many)
- **User → Pangkalan**: `users.pangkalan_id` (tempat kerja user)
- **Kepala ↔ Pangkalan**: `kepala_pangkalan` pivot table (di-auto-sync dari PangkalanController)

Jika kepala diubah di data pangkalan, maka `is_kepala` pada user akan otomatis berubah. Jika user dihapus, `kepala_user_id` pada pangkalan akan menjadi null (nullOnDelete).
