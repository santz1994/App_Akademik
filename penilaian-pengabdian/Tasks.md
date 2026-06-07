# Tasks

## Task 1 : Rule
1. Setiap user kepala pangkalan hanya dapat menilai karyawan yang berada di bawahnya.
2. User Kepala Pangkalan hanya dapat mengisi, mengubah, dan menggenerate laporan penilaian karyawan yang berada di dalam pangkalannya.
3. Setiap user karyawan hanya dapat melihat laporan penilaian dirinya sendiri.
4. Untuk user admin dapat mengakses semua fitur dan data yang ada di dalam aplikasi, termasuk mengelola data karyawan, pangkalan, dan laporan penilaian karyawan.
5. Add new Roles : Tata usaha, memiliki akses melihat fungsi keseluruhan laporan penilaian karyawan, namun tidak memiliki akses untuk mengisi atau mengubah data penilaian karyawan. Tata usaha hanya dapat melihat laporan penilaian karyawan yang berada di dalam pangkalannya. Dan mencetak laporan penilaian keseluruhan karyawan, (perorangan dan keseluruhan pangkalan). Juga memberikan akses untuk membuka Lock pada penilaian karyawan jika terjadi kesalahan dalam pengisian data penilaian karyawan, sehingga user kepala pangkalan dapat mengubah data penilaian karyawan yang sudah di lock sebelumnya. Namun tidak dapat mengisi dan mengedit data penilaian karyawan yang sudah di lock sebelumnya.

## Task 2 : Sistem
1. Setiap karyawan jika pada nilai akhir yang mendapat nilai C dan D akan mendapatkan hukuman berupa :
    - Nilai C : 5 Sak Semen
    - Nilai D : 10 Sak Semen
2. Perlu ditambahkan pada fitur laporan penilaian karyawan, keterangan jika karyawan mendapatkan nilai C atau D, maka akan muncul keterangan hukuman yang harus dijalankan oleh karyawan tersebut.
3. Pages FAQ hanya muncul pada user admin dan user kepala pangkalan, sedangkan untuk user karyawan tidak muncul pada menu utama.

## Task 3 : Bugs
1. Sering muncul error 419 : Pages Expired setelah login.
2. Perlu handler error untuk menangani setiap error yang muncul. Dan dengan ditambahkan tombol kembali ke halaman sebelumnya atau ke halaman utama jika terjadi error.
3. Foto user masih belum tampil, seharusnya foto user mengambil dari data karyawan jika ada, jika tidak ada maka akan menampilkan foto default.

## Task 4 : Fitur Tambahan
1. Tambahkan Menu Pengaturan database untuk melakukan backup dan restore database.
2. Tambahkan Menu baru untuk mengatur reward dan punishment yang akan diberikan kepada karyawan berdasarkan nilai akhir yang didapatkan.
3. Tambahkan fitur baru, pada pangkalan job dapat dipilih kategori kinerja terkait, apakah job ini adalah kinerja atau kegiatan. Sehingga setiap kategori akan ada penanggung jawab penilaiannya.

## Task 5 : Dokumentasi
1. Perlu dibuat dokumentasi untuk setiap fitur yang ada di dalam aplikasi, termasuk cara penggunaan dan penjelasan setiap fitur yang ada.
2. Dokumentasi juga perlu dibuat untuk setiap error yang sering muncul, beserta cara mengatasinya.
3. Update juga file README.md dengan informasi terbaru mengenai fitur yang ada di dalam aplikasi, cara penggunaan, dan penjelasan setiap fitur yang ada. Juga tambahkan informasi mengenai error yang sering muncul dan cara mengatasinya.

## Task 6 : Laporan Penilaian Karyawan dan penilaian karyawan
1. Untuk karyawan yang memiliki lebih dari 1 pangkalan, perlu ada pembagian colom atau baris nilai pangkalan, di dalam laporan keseluruhan. Untuk saat ini laporan keseluruhan hanya menampilkan 1 colom atau baris nilai pangkalan, sehingga jika karyawan memiliki lebih dari 1 pangkalan, maka nilai pangkalan yang saat ini ditampilkan hanya salah satu pangkalan saja, sedangkan pangkalan lainnya tidak ditampilkan. Sehingga perlu ada pembagian colom atau baris nilai pangkalan, sehingga semua pangkalan yang dimiliki oleh karyawan tersebut dapat ditampilkan di dalam laporan keseluruhan.
2. Untuk laporan perorangan, jika karyawan memiliki lebih dari 1 pangkalan maka di dalam laporan perorangan juga perlu menampilkan semua pangkalan yang dimiliki oleh karyawan tersebut, sehingga tidak hanya menampilkan salah satu pangkalan saja, tetapi semua pangkalan yang dimiliki oleh karyawan tersebut dapat ditampilkan di dalam laporan perorangan. Sehingga perlu ada pembagian colom atau baris nilai pangkalan, sehingga semua pangkalan yang dimiliki oleh karyawan tersebut dapat ditampilkan di dalam laporan perorangan.
3. Untuk penilaian karyawan, jika karyawan memiliki lebih dari 1 pangkalan, maka di dalam penilaian karyawan juga perlu menampilkan semua pangkalan yang dimiliki oleh karyawan tersebut, sehingga tidak hanya menampilkan salah satu pangkalan saja, tetapi semua pangkalan yang dimiliki oleh karyawan tersebut dapat ditampilkan di dalam penilaian karyawan. Sehingga perlu ada pembagian colom atau baris nilai pangkalan, sehingga semua pangkalan yang dimiliki oleh karyawan tersebut dapat ditampilkan di dalam penilaian karyawan. Berikut juga dengan keterangan pangkalannya yang ditampilkan di dalam penilaian karyawan, sehingga user kepala pangkalan dapat dengan mudah mengetahui pangkalan mana yang sedang dinilai oleh karyawan tersebut. Sehingga perlu ada pembagian colom atau baris nilai pangkalan, sehingga semua pangkalan yang dimiliki oleh karyawan tersebut dapat ditampilkan di dalam penilaian karyawan, beserta keterangan pangkalannya.
4. Hilangkan setiap kode pangkalan, kode kategori, kode karyawan, kode jabatan, dan kode lainnya yang ada di dalam laporan penilaian karyawan, baik di dalam laporan perorangan maupun laporan keseluruhan, serta di dalam penilaian karyawan. Sehingga yang ditampilkan hanya nama pangkalan, nama kategori, nama karyawan, nama jabatan, dan nama lainnya yang ada di dalam laporan penilaian karyawan, baik di dalam laporan perorangan maupun laporan keseluruhan, serta di dalam penilaian karyawan. Sehingga tidak perlu menampilkan kode pangkalan, kode kategori, kode karyawan, kode jabatan, dan kode lainnya yang ada di dalam laporan penilaian karyawan, baik di dalam laporan perorangan maupun laporan keseluruhan, serta di dalam penilaian karyawan.
5. Foto pada user profile karyawan masih belum tampil, seharusnya foto user mengambil dari data karyawan jika ada, jika tidak ada maka akan menampilkan foto default. Sehingga perlu diperbaiki agar foto user dapat tampil dengan benar di dalam user profile karyawan, sehingga user dapat melihat foto dirinya sendiri di dalam user profile karyawan. Sehingga perlu diperbaiki agar foto user dapat tampil dengan benar di dalam user profile karyawan, sehingga user dapat melihat foto dirinya sendiri di dalam user profile karyawan. http://localhost:8000/user/dashboard
6. Pada form penilaian http://localhost:8000/kepala/transaksi/create?karyawan_id=32&tahun_penilaian_id=2, masih tampil penilaian kegiatan. Seharusnya pada form penilaian hanya menampilkan penilaian kinerja saja, sedangkan untuk penilaian kegiatan tidak perlu ditampilkan di dalam form penilaian, karena penilaian kegiatan hanya digunakan untuk laporan perorangan dan laporan keseluruhan saja, sehingga tidak perlu ditampilkan di dalam form penilaian. Penilaian kegiatan hanya diinputkan oleh admin dan Tata Usaha saja, sedangkan untuk kepala pangkalan tidak perlu menginputkan penilaian kegiatan di dalam form penilaian, karena penilaian kegiatan hanya digunakan untuk laporan perorangan dan laporan keseluruhan saja, sehingga tidak perlu ditampilkan di dalam form penilaian.
7. Pada angka count di halaman pangkalan job, yang dihitung jumlahnya hanya karyawan yang aktif saja, menghitung jumlah tanpa menyertakan kepala pangkalannya, seharusnya menampilkan count realtime. Sesuai dengan jumlah karyawan aktif yang berada di dalam pangkalan tersebut. <span class="badge bg-primary">{{ $p->karyawan_count }}</span>