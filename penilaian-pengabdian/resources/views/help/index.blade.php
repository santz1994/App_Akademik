@extends('layouts.app')
@section('title','Help / QnA')
@section('page-title','Help / QnA')
@section('content')

<div class="card mb-3">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-download me-2"></i>Template Import (Download)</div>
    <div class="card-body" style="font-size:.9rem;">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
            <span class="fw-semibold me-1">User:</span>
            <a href="{{ route('help.template.import', ['entity' => 'user', 'format' => 'xlsx']) }}" class="btn btn-sm btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i>Template User XLSX
            </a>
            <a href="{{ route('help.template.import', ['entity' => 'user', 'format' => 'csv']) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-filetype-csv me-1"></i>Template User CSV
            </a>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="fw-semibold me-1">Karyawan:</span>
            <a href="{{ route('help.template.import', ['entity' => 'karyawan', 'format' => 'xlsx']) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-file-earmark-excel me-1"></i>Template Karyawan XLSX
            </a>
            <a href="{{ route('help.template.import', ['entity' => 'karyawan', 'format' => 'csv']) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-filetype-csv me-1"></i>Template Karyawan CSV
            </a>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-people me-2"></i>Alur Cepat Berdasarkan Peran</div>
    <div class="card-body" style="font-size:.9rem;">
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold mb-2"><span class="badge bg-danger me-1">Admin</span> Setup dan Kontrol</div>
                    <ol class="mb-0 ps-3">
                        <li>Atur Tahun Penilaian aktif.</li>
                        <li>Lengkapi Pangkalan Job dan mapping Kategori Kinerja.</li>
                        <li>Lengkapi master Kategori, Kompetensi, User, dan Karyawan.</li>
                        <li>Gunakan Lock/Unlock/Batch Unlock pada menu Transaksi bila dibutuhkan revisi.</li>
                        <li>Gunakan menu Laporan untuk cetak dan export.</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold mb-2"><span class="badge bg-warning text-dark me-1">Kepala</span> Input dan Finalisasi</div>
                    <ol class="mb-0 ps-3">
                        <li>Buka menu Transaksi pada tahun yang sesuai.</li>
                        <li>Input nilai per indikator untuk karyawan dalam pangkalan yang sama.</li>
                        <li>Nilai indikator yang terisi akan terkunci otomatis.</li>
                        <li>Jika perlu revisi, ajukan request unlock ke admin.</li>
                        <li>Submit Final bila data sudah valid.</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold mb-2"><span class="badge bg-primary me-1">User</span> Monitoring Laporan</div>
                    <ol class="mb-0 ps-3">
                        <li>Filter laporan berdasarkan tahun/mode.</li>
                        <li>Pilih Ringkas untuk ringkasan per kategori.</li>
                        <li>Pilih Rinci untuk detail indikator kompetensi.</li>
                        <li>Gunakan print, PDF, Excel, atau CSV sesuai kebutuhan.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-signpost-split me-2"></i>Panduan Langkah Penilaian</div>
    <div class="card-body" style="font-size:.9rem;">
        <ol class="mb-0">
            <li>Pastikan Tahun Penilaian aktif sudah benar.</li>
            <li>Pastikan tiap Pangkalan sudah dipetakan ke Kategori Kinerja yang sesuai.</li>
            <li>Pastikan Kategori Kegiatan wajib sudah ditandai dengan benar.</li>
            <li>Input nilai pada menu Transaksi (minimal satu Kinerja dan kategori Kegiatan wajib terpenuhi).</li>
            <li>Indikator yang sudah terisi akan terkunci otomatis pada sesi berikutnya.</li>
            <li>Jika ada koreksi, admin dapat melakukan Unlock per karyawan atau Batch Unlock.</li>
            <li>Gunakan Laporan Ringkas untuk melihat rata-rata per kategori.</li>
            <li>Gunakan Laporan Rinci untuk audit nilai per indikator.</li>
        </ol>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-upload me-2"></i>Format Import User</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kolom</th>
                        <th>Nama Field</th>
                        <th>Wajib</th>
                        <th>Keterangan</th>
                        <th>Contoh</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>name</td><td>Ya</td><td>Nama lengkap user</td><td>Ahmad Fauzi</td></tr>
                    <tr><td>2</td><td>username</td><td>Ya</td><td>Username unik</td><td>ahmad</td></tr>
                    <tr><td>3</td><td>email</td><td>Ya</td><td>Email unik</td><td>ahmad@mail.com</td></tr>
                    <tr><td>4</td><td>password</td><td>Tidak</td><td>Kosongkan jika tidak ingin ubah password user existing. Untuk user baru default user12345 jika kosong.</td><td>rahasia123</td></tr>
                    <tr><td>5</td><td>role</td><td>Tidak</td><td>admin atau user (default user)</td><td>user</td></tr>
                    <tr><td>6</td><td>kode_pangkalan</td><td>Tidak</td><td>Wajib jika is_kepala = true</td><td>PNG-001</td></tr>
                    <tr><td>7</td><td>is_kepala</td><td>Tidak</td><td>1/0, ya/tidak, true/false</td><td>1</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-upload me-2"></i>Format Import Karyawan</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kolom</th>
                        <th>Nama Field</th>
                        <th>Wajib</th>
                        <th>Keterangan</th>
                        <th>Contoh</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>kode_karyawan</td><td>Tidak</td><td>Jika kosong akan generate otomatis</td><td>KRY-0101</td></tr>
                    <tr><td>2</td><td>nama_karyawan</td><td>Ya</td><td>Nama karyawan</td><td>Siti Aminah</td></tr>
                    <tr><td>3</td><td>kode_pangkalan</td><td>Tidak</td><td>Harus sesuai data pangkalan jika diisi</td><td>PNG-002</td></tr>
                    <tr><td>4</td><td>tugas_khusus</td><td>Tidak</td><td>Deskripsi tugas</td><td>TU MA</td></tr>
                    <tr><td>5</td><td>alamat</td><td>Tidak</td><td>Alamat karyawan</td><td>Jl. Melati 10</td></tr>
                    <tr><td>6</td><td>is_active</td><td>Tidak</td><td>1/0, aktif/nonaktif, true/false</td><td>aktif</td></tr>
                    <tr><td>7</td><td>username</td><td>Tidak</td><td>Username user yang akan dihubungkan</td><td>siti</td></tr>
                    <tr><td>8</td><td>tahun_penilaian</td><td>Tidak</td><td>ID tahun atau periode, default tahun aktif jika kosong</td><td>2025/2026</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-diagram-3 me-2"></i>Aturan Relasi Data (Penting)</div>
    <div class="card-body" style="font-size:.9rem;">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Relasi</th>
                        <th>Jenis</th>
                        <th>Dampak</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pangkalan - Kategori Kinerja</td>
                        <td>Many-to-many</td>
                        <td>Menentukan kategori kinerja apa saja yang berlaku untuk karyawan di pangkalan tersebut.</td>
                    </tr>
                    <tr>
                        <td>Kategori - Kompetensi</td>
                        <td>Many-to-many</td>
                        <td>Satu kompetensi dapat dipakai di beberapa kategori.</td>
                    </tr>
                    <tr>
                        <td>Karyawan - Pangkalan</td>
                        <td>Many-to-one</td>
                        <td>Menentukan ruang lingkup penilaian kinerja karyawan.</td>
                    </tr>
                    <tr>
                        <td>Karyawan - User</td>
                        <td>Optional one-to-one</td>
                        <td>Akun user bisa ditautkan ke data karyawan untuk akses aplikasi.</td>
                    </tr>
                    <tr>
                        <td>Transaksi - (Karyawan, Tahun, Kompetensi)</td>
                        <td>Many-to-one per dimensi</td>
                        <td>Nilai tersimpan per indikator kompetensi pada kombinasi karyawan + tahun.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-lock me-2"></i>Aturan Lock, Unlock, dan Batch Unlock</div>
    <div class="card-body" style="font-size:.9rem;">
        <ul class="mb-2">
            <li>Indikator yang sudah terisi akan otomatis terkunci pada form input berikutnya.</li>
            <li>Indikator yang belum terisi tetap bisa diinput.</li>
            <li>Admin dapat unlock per karyawan dari kolom Aksi di menu Transaksi.</li>
            <li>Admin dapat batch unlock dengan checklist baris dan tombol Batch Unlock.</li>
            <li>Setelah di-unlock admin, indikator terisi bisa diedit kembali.</li>
        </ul>
        <div class="alert alert-light border mb-0">
            <strong>Catatan:</strong> Gunakan lock/unlock secara bertahap agar jejak revisi mudah dipantau.
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-lightbulb me-2"></i>QnA Umum</div>
    <div class="card-body" style="font-size:.9rem;">
        <div class="accordion" id="helpFaqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOneBody" aria-expanded="true" aria-controls="faqOneBody">
                        Kompetensi bisa dipakai untuk lebih dari satu kategori?
                    </button>
                </h2>
                <div id="faqOneBody" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#helpFaqAccordion">
                    <div class="accordion-body">Bisa. Sistem mendukung relasi many-to-many antara kategori dan kompetensi.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwoBody" aria-expanded="false" aria-controls="faqTwoBody">
                        Kenapa ada indikator terkunci padahal sheet belum di-lock?
                    </button>
                </h2>
                <div id="faqTwoBody" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#helpFaqAccordion">
                    <div class="accordion-body">Karena indikator yang sudah memiliki nilai akan otomatis terkunci. Ini berbeda dengan lock sheet penuh oleh admin/kepala.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThreeBody" aria-expanded="false" aria-controls="faqThreeBody">
                        Bagaimana cara koreksi nilai yang sudah terkunci otomatis?
                    </button>
                </h2>
                <div id="faqThreeBody" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#helpFaqAccordion">
                    <div class="accordion-body">Admin lakukan unlock (per baris atau batch). Setelah itu indikator yang sebelumnya terkunci bisa diedit kembali.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFourBody" aria-expanded="false" aria-controls="faqFourBody">
                        Kapan memakai laporan Ringkas dan Rinci?
                    </button>
                </h2>
                <div id="faqFourBody" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#helpFaqAccordion">
                    <div class="accordion-body">Ringkas untuk melihat rata-rata per kategori. Rinci untuk melihat nilai pada level indikator kompetensi.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFiveBody" aria-expanded="false" aria-controls="faqFiveBody">
                        Kenapa pada laporan ada tanda "-"?
                    </button>
                </h2>
                <div id="faqFiveBody" class="accordion-collapse collapse" aria-labelledby="faqFive" data-bs-parent="#helpFaqAccordion">
                    <div class="accordion-body">Tanda "-" berarti nilai belum diisi atau indikator/kategori tidak berlaku untuk baris karyawan tersebut.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqSixBody" aria-expanded="false" aria-controls="faqSixBody">
                        Kenapa nilai tidak tersimpan saat klik Simpan?
                    </button>
                </h2>
                <div id="faqSixBody" class="accordion-collapse collapse" aria-labelledby="faqSix" data-bs-parent="#helpFaqAccordion">
                    <div class="accordion-body">Pastikan Anda mengisi indikator yang memang berlaku untuk karyawan, serta memenuhi ketentuan kategori kegiatan wajib.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-life-preserver me-2"></i>Troubleshooting Cepat</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Masalah</th>
                        <th>Penyebab Umum</th>
                        <th>Solusi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tombol Batch Unlock tidak aktif</td>
                        <td>Tahun penilaian belum dipilih</td>
                        <td>Pilih Tahun Ajaran pada filter Transaksi terlebih dahulu.</td>
                    </tr>
                    <tr>
                        <td>Data karyawan tidak muncul di transaksi kepala</td>
                        <td>Karyawan beda pangkalan atau status tidak aktif</td>
                        <td>Pastikan data karyawan aktif dan pangkalan sesuai akun kepala.</td>
                    </tr>
                    <tr>
                        <td>Nilai akhir kosong di laporan</td>
                        <td>Belum ada nilai valid yang tersimpan</td>
                        <td>Isi minimal satu indikator yang berlaku dan simpan kembali.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
