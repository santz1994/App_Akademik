# Daftar Pengerjaan Tugas

> Kolom **Status** diisi oleh Anda setelah mereview.
> **Sesuai** / **Tidak Sesuai** + catatan di Keterangan.

## Update
| No | Tugas | Status | Keterangan |
|----|-------|--------|------------|
| 1 | Logo di halaman login | | Logo dari Setting Lembaga. Default icon. |
| 2 | Captcha dinamis | | 4 digit random, warna beda, validasi server. |

## Repair Bugs
| No | Tugas | Status | Keterangan |
|----|-------|--------|------------|
| 1 | Auto-search tanpa Terapkan | Sesuai | Debounce 500ms, select auto-submit. |
| 2 | Laporan perorangan Portrait A4 | | Ringkas/Rinci, multi-pangkalan, per-pangkalan scoring. PDF ringkas + rinci terpisah. |
| 3 | Laporan keseluruhan terpotong Ctrl+P | | Ditambah @media print CSS: force landscape, font kecil, table-layout fixed. |
| 4 | Foto user dari karyawan | Sesuai | Topbar foto karyawan. |
| 5 | Semua pencarian auto-submit | Sesuai | Karyawan, Transaksi, Laporan. |
| 6 | Sidemenu Semua Data Karyawan | Sesuai | Karyawan Aktif, Kepala, Semua. |
| 7 | Profil PDF hapus foto text | Sesuai | Row foto & kolom keterangan dihapus. |
| 8 | Sidebar responsive 1366x768 | Sesuai | max-height, overflow-y, media query. |
| 9 | Collapse sidebar | | CSS !important + cubic-bezier transition 0.3s. Text hidden, only icons. |
| 10 | Dashboard progress aktif | Sesuai | Filter is_active=true. |
| 11 | Lock setelah kepala nilai | | Pre-save lock check. |
| 13 | Multi-bagian | | Multiple select pangkalan di user & karyawan create/edit. getAllPangkalanIds() di laporan. |
| 14 | Mutasi 2 jenis | | Tahun Ajaran + Antar Pangkalan. |
| 15 | Field email, no_hp, kontak_darurat | | Migration + form. |
| 16 | Aktivasi akun + email login | | activate.blade.php + email auth. |
| 17 | User self-update | | profile.blade.php. |
| 18 | Hapus bobot di laporan | | Badge bobot dihapus. |
| 19 | Bobot configurable | | PenilaianMetodeController. |

## Sidebar Menu
| No | Menu | Status |
|----|------|--------|
| 1 | Dashboard | |
| 2 | Setting Lembaga | |
| 3 | User Account | |
| 4 | Karyawan > Aktif, Kepala, Semua | |
| 5 | Tahun Penilaian | |
| 6 | Data > Kategori, Pangkalan, Kompetensi, Rating | |
| 7 | Mutasi > Tahun Ajaran, Antar Pangkalan | |
| 8 | Transaksi > Penilaian, Bobot, Unlock | |
| 9 | Laporan > Keseluruhan, Perorangan, Format | |
| 10 | FAQ > Help, FAQ | |

## Perubahan Fix Terakhir- LaporanScoreCalculator: tambah method calculatePerPangkalan() untuk scoring per-pangkalan + rata-rata multi-pangkalan
- LaporanController: peroranganPdf & buildPeroranganData gunakan calculatePerPangkalan, load pangkalanLain relation
- LaporanController: peroranganPdf routing ke perorangan_ringkas_pdf vs perorangan_pdf berdasarkan jenis_laporan
- index.blade.php: Ringkas = kinerja per pangkalan (tabel), kegiatan per kategori (tabel). Rinci = flat indicator tanpa list pangkalan
- perorangan_pdf.blade.php: multi-pangkalan grouping, summary pakai perPangkalanData, hapus list semua pangkalan
- perorangan_ringkas_pdf.blade.php: view baru PDF ringkas (kinerja per pangkalan, kegiatan per kategori)- LaporanController: printView & exportPdf redirect ke perorangan_pdf saat mode=perorangan
- Laporan PDF: @media print CSS (landscape, font kecil, table-layout fixed)
- Sidebar CSS: !important pada display:none, cubic-bezier transition 0.3s
- KaryawanController: tambah validasi pangkalan_tambahan + sync pivot
- UserManagementController: tambah validasi pangkalan_tambahan + sync pivot
- Karyawan create/edit: multi-select pangkalan tambahan
- User create/edit: multi-select pangkalan tambahan
