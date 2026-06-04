# Update and Repair needed for the project

## Update
- Logo in the login page
- Captcha in the login page masih static, captcha angka masih sama setiap kali login, perlu dibuatkan logic untuk generate captcha angka yang berbeda setiap kali login.

## Repair Bugs
1. CRUD http://localhost:8000/admin/karyawan?status_kepala=nonkepala pencarian karyawan perlu mengklik tombol terapkan untuk menampilkan hasil pencarian, seharusnya langsung menampilkan hasil pencarian tanpa perlu mengklik tombol terapkan
2. Laporan nilai perorangan karyawan, dibuat seperti laporan profil karyawan. Portrait A4, dengan informasi sebagai berikut :
    - Nama karyawan : ${nama_karyawan}
    - Pangkalan Job : ${pangkalan_job}
    - Nilai Kinerja : ${nilai_kinerja} List nilai kinerja per kegiatan perorangan karyawan
    - Nilai Kegiatan : ${nilai_kegiatan} List nilai kegiatan per kegiatan perorangan karyawan
    - Nilai Akhir : ${nilai_akhir} Nilai akhir perorangan karyawan
3. Laporan nilai keseluruhan karyawan digunakan oleh kepala untuk melihat nilai keseluruhan karyawan sudah benar, hanya perlu diperbaiki untuk generate laporan nilainya. Karena terpotong untuk tombol, "cetak, PDF, EXCEL, CSV".
4. Foto pada user account, diambil defaultnya dari foto karyawan yang sudah diupload, jika belum ada foto karyawan yang diupload, maka akan menggunakan foto default.
5. Perbaikan keseluruhan pada fungsi tombol pencarian berdasarkan, karena memerlukan klik tombol terapkan untuk menampilkan hasil pencarian, seharusnya langsung menampilkan hasil pencarian tanpa perlu mengklik tombol terapkan.
6. Tambahkan sidemenu di dalam menu Data Karyawan, yaitu Semua Data Karyawan untuk menampilkan semua data karyawan yang aktif dan sudah tidak aktif. Jadi untuk menu data karyawan hanya menampilkan saja data karyawan yang aktif saja, berikut dengan jumlahnya.
7. Profil karyawan http://localhost:8000/admin/karyawan/60/profil-pdf hilangkan saja tulisan Foto Karyawan 3x4 tersedia, dan hilangkan colom keterangan.
8. Sidebar dan halaman buat agar lebih responsive, karena ada user yang menggunakan resolusi layar 1366x768 sehingga list menu di sidebar tidak muncul semua, dan halaman juga tidak responsive. Perlu juga dibuat scroll untuk sidebar agar bisa menampilkan semua menu di sidebar.
9. Tambahkan fitur collapse untuk sidebar agar main content bisa lebih luas, dan sidebar bisa disembunyikan jika tidak diperlukan.
10. Pada halaman dashboard. informasi progress penilaian tahun aktif, total yang dinilai dari jumlah karyawan yang aktif. Bukan keseluruhan karyawan yang ada di database, karena ada karyawan yang sudah tidak aktif.
11. Status lock pada menu transaksi tidak bekerja dengan baik. Setelah penilaian oleh user kepala, status lock tidak berubah menjadi terkunci, sehingga user kepala bisa melakukan penilaian ulang. Seharusnya setelah penilaian oleh user kepala, status lock berubah menjadi terkunci, sehingga user kepala tidak bisa melakukan penilaian ulang. Dan perlu meminta Admin untuk melakukan unlock jika ingin melakukan penilaian ulang.
13. Karena pada real dilapangan ada user kepala yang mengepalai lebih dari satu bagian. Dan juga karyawan yang bekerja di lebih dari satu bagian. Maka perlu dibuatkan logic baru :
    - Kepala bisa mengepalai lebih dari satu bagian, sehingga bisa melakukan penilaian untuk semua bagian yang dipimpinnya.
    - Karyawan bisa bekerja di lebih dari satu bagian, sehingga bisa dinilai untuk semua bagian yang dia kerjakan.
