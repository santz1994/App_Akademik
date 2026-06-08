<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenilaianExport;
use App\Models\Karyawan;
use App\Models\KategoriKinerja;
use App\Models\Pangkalan;
use App\Models\SettingLembaga;
use App\Models\TahunPenilaian;
use App\Support\LaporanScoreCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->buildReportData($request, 'admin', false);
        $data['routePrefix'] = 'admin';

        return view('admin.laporan.index', $data);
    }

    public function perorangan(Request $request)
    {
        $data = $this->buildPeroranganPageData($request, 'admin');
        $data['routePrefix'] = 'admin';

        return view('admin.laporan.perorangan', $data);
    }

    public function kepalaIndex(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->is_kepala, 403);

        $data = $this->buildReportData($request, 'kepala', false);
        $data['routePrefix'] = 'kepala';

        return view('admin.laporan.index', $data);
    }

    public function kepalaPerorangan(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->is_kepala, 403);

        $data = $this->buildPeroranganPageData($request, 'kepala');
        $data['routePrefix'] = 'kepala';

        return view('admin.laporan.perorangan', $data);
    }

    public function userIndex(Request $request)
    {
        $user = Auth::user();

        // User biasa langsung tampil perorangan (hanya bisa lihat nilai sendiri)
        if ($user->karyawan) {
            // Auto-set karyawan_id ke karyawan milik user ini
            $request->merge(['karyawan_id' => $user->karyawan->id]);

            $data = $this->buildPeroranganPageData($request, 'user');
            $data['routePrefix'] = 'user';
            return view('admin.laporan.perorangan', $data);
        }

        // Jika user tidak punya karyawan, tampilkan halaman kosong
        $data = $this->buildReportData($request, 'user', false);
        $data['routePrefix'] = 'user';
        return view('admin.laporan.index', $data);
    }

    public function userPerorangan(Request $request)
    {
        $data = $this->buildPeroranganPageData($request, 'user');
        $data['routePrefix'] = 'user';

        return view('admin.laporan.perorangan', $data);
    }

    public function printView(Request $request)
    {
        $scope = $this->resolveScope();

        // If perorangan mode with specific karyawan, use the dedicated perorangan PDF view
        if ($request->input('mode') === 'perorangan' && $request->filled('karyawan_id')) {
            return $this->peroranganPdf($request);
        }

        $data = $this->buildReportData($request, $scope, true);

        return view('admin.laporan.pdf', $data);
    }

    public function exportPdf(Request $request)
    {
        // If perorangan mode with specific karyawan, use the dedicated perorangan PDF view
        if ($request->input('mode') === 'perorangan' && $request->filled('karyawan_id')) {
            return $this->peroranganPdf($request);
        }

        $scope = $this->resolveScope();
        $data = $this->buildReportData($request, $scope, true);

        $paperSize = $data['reportFormat']['paper_size'] ?? 'a4';
        $orientation = $data['reportFormat']['orientation'] ?? 'portrait';
        if (!in_array($paperSize, ['a4', 'letter', 'legal'], true)) {
            $paperSize = 'a4';
        }
        if (!in_array($orientation, ['portrait', 'landscape'], true)) {
            $orientation = 'portrait';
        }

        $fileName = 'laporan-penilaian-' . now()->format('Ymd-His') . '.pdf';
        return Pdf::loadView('admin.laporan.pdf', $data)
            ->setPaper($paperSize, $orientation)
            ->download($fileName);
    }

    public function exportExcel(Request $request)
    {
        $scope = $this->resolveScope();
        $data = $this->buildReportData($request, $scope, true);

        $fileName = 'laporan-penilaian-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new LaporanPenilaianExport(
                $data['karyawanList'],
                $data['kategoriList'],
                $data['selectedTahunData'],
                $data['jenisLaporan'],
                $data['reportFormat']
            ),
            $fileName
        );
    }

    public function exportCsv(Request $request)
    {
        $scope = $this->resolveScope();
        $data = $this->buildReportData($request, $scope, true);

        $fileName = 'laporan-penilaian-' . now()->format('Ymd-His') . '.csv';

        return Excel::download(
            new LaporanPenilaianExport(
                $data['karyawanList'],
                $data['kategoriList'],
                $data['selectedTahunData'],
                $data['jenisLaporan'],
                $data['reportFormat']
            ),
            $fileName,
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    private function resolveScope(): string
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return 'admin';
        }

        return $user->is_kepala ? 'kepala' : 'user';
    }

    private function buildReportData(Request $request, string $scope, bool $forOutput = false): array
    {
        $user = Auth::user();
        $setting = SettingLembaga::where('is_active', true)->latest()->first()
            ?? SettingLembaga::latest()->first();

        $tahunList         = TahunPenilaian::orderByDesc('periode_penilaian')->get();
        $tahunAktif        = TahunPenilaian::where('is_active', true)->first();
        $defaultTahunId    = $setting?->tahun_penilaian_id ?: $tahunAktif?->id;
        $selectedTahun     = $request->input('tahun_penilaian_id', $defaultTahunId);
        $selectedTahunData = $selectedTahun ? TahunPenilaian::find($selectedTahun) : $tahunAktif;

        $allowedModes = $scope === 'admin'
            ? ['keseluruhan', 'perdireksi']
            : ['keseluruhan'];
        $mode = (string) $request->input('mode', 'keseluruhan');
        // Auto-detect mode from filter parameters
        if ($mode === 'keseluruhan' && $request->filled('pangkalan_id') && $scope === 'admin') {
            $mode = 'perdireksi';
        }
        if (!in_array($mode, $allowedModes, true)) {
            $mode = 'keseluruhan';
        }

        $reportFormat = [
            'show_no' => (bool) ($setting?->laporan_show_no ?? true),
            'show_kode_karyawan' => (bool) ($setting?->laporan_show_kode_karyawan ?? true),
            'show_pangkalan' => (bool) ($setting?->laporan_show_pangkalan ?? true),
            'show_nilai_akhir' => (bool) ($setting?->laporan_show_nilai_akhir ?? true),
            'show_rating' => (bool) ($setting?->laporan_show_rating ?? true),
            'show_detail_kompetensi' => (bool) ($setting?->laporan_show_detail_kompetensi ?? true),
            'show_bobot_kategori' => (bool) ($setting?->laporan_show_bobot_kategori ?? true),
            'paper_size' => $setting?->laporan_paper_size ?: 'a4',
            'orientation' => $setting?->laporan_orientation ?: 'portrait',
            'margin_top' => (float) ($setting?->laporan_margin_top ?? 2.54),
            'margin_right' => (float) ($setting?->laporan_margin_right ?? 2.54),
            'margin_bottom' => (float) ($setting?->laporan_margin_bottom ?? 2.54),
            'margin_left' => (float) ($setting?->laporan_margin_left ?? 2.54),
            'text_align' => $setting?->laporan_text_align ?: 'left',
            'header_align' => $setting?->laporan_header_align ?: 'center',
            'cell_padding' => (int) ($setting?->laporan_cell_padding ?? 6),
            'border_width' => (float) ($setting?->laporan_border_width ?? 1),
            'font_size' => (int) ($setting?->laporan_font_size ?? 11),
            'title_font_size' => (int) ($setting?->laporan_title_font_size ?? 16),
            'col_width_no' => (int) ($setting?->laporan_col_width_no ?? 32),
            'col_width_kode' => (int) ($setting?->laporan_col_width_kode ?? 72),
            'col_width_nama' => (int) ($setting?->laporan_col_width_nama ?? 190),
            'col_width_pangkalan' => (int) ($setting?->laporan_col_width_pangkalan ?? 140),
            'col_width_nilai' => (int) ($setting?->laporan_col_width_nilai ?? 88),
            'col_width_rating' => (int) ($setting?->laporan_col_width_rating ?? 108),
            'column_order' => json_decode((string) ($setting?->laporan_column_order ?? ''), true),
            'labels' => [
                'no' => $setting?->laporan_label_no ?: 'No',
                'kode_karyawan' => $setting?->laporan_label_kode_karyawan ?: 'Kode Karyawan',
                'nama_karyawan' => $setting?->laporan_label_nama_karyawan ?: 'Nama Karyawan',
                'pangkalan' => $setting?->laporan_label_pangkalan ?: 'Pangkalan',
                'detail_kompetensi' => $setting?->laporan_label_detail_kompetensi ?: 'Detail Kompetensi',
                'nilai_akhir' => $setting?->laporan_label_nilai_akhir ?: 'Nilai Akhir',
                'rating' => $setting?->laporan_label_rating ?: 'Rating',
            ],
            'scoring_method' => $setting?->laporan_scoring_method ?: 'weighted_kinerja_kegiatan',
            'score_weight_kinerja' => (float) ($setting?->laporan_bobot_kinerja ?? 70),
            'score_weight_kegiatan' => (float) ($setting?->laporan_bobot_kegiatan ?? 30),
        ];

        if (!$reportFormat['show_nilai_akhir']) {
            $reportFormat['show_rating'] = false;
        }

        if (!in_array($reportFormat['paper_size'], ['a4', 'letter', 'legal'], true)) {
            $reportFormat['paper_size'] = 'a4';
        }
        if (!in_array($reportFormat['orientation'], ['portrait', 'landscape'], true)) {
            $reportFormat['orientation'] = 'portrait';
        }
        if (!in_array($reportFormat['text_align'], ['left', 'center', 'right', 'justify'], true)) {
            $reportFormat['text_align'] = 'left';
        }
        if (!in_array($reportFormat['header_align'], ['left', 'center', 'right'], true)) {
            $reportFormat['header_align'] = 'center';
        }

        $availableColumns = ['no', 'kode_karyawan', 'nama_karyawan', 'pangkalan', 'detail_kompetensi', 'nilai_akhir', 'rating'];
        $columnOrder = is_array($reportFormat['column_order']) ? $reportFormat['column_order'] : [];
        $normalizedOrder = [];
        foreach ($columnOrder as $columnKey) {
            $columnKey = (string) $columnKey;
            if (in_array($columnKey, $availableColumns, true) && !in_array($columnKey, $normalizedOrder, true)) {
                $normalizedOrder[] = $columnKey;
            }
        }
        foreach ($availableColumns as $columnKey) {
            if (!in_array($columnKey, $normalizedOrder, true)) {
                $normalizedOrder[] = $columnKey;
            }
        }
        $reportFormat['column_order'] = $normalizedOrder;

        if (!in_array($reportFormat['scoring_method'], ['weighted_kategori', 'weighted_kinerja_kegiatan', 'average_kinerja_kegiatan'], true)) {
            $reportFormat['scoring_method'] = 'weighted_kinerja_kegiatan';
        }
        if ($reportFormat['scoring_method'] === 'average_kinerja_kegiatan') {
            $reportFormat['scoring_method'] = 'weighted_kinerja_kegiatan';
        }

        $reportFormat['score_weight_kinerja'] = max(0.0, min(100.0, (float) $reportFormat['score_weight_kinerja']));
        $reportFormat['score_weight_kegiatan'] = max(0.0, min(100.0, (float) $reportFormat['score_weight_kegiatan']));

        $defaultJenisLaporan = $setting?->laporan_default_jenis;
        if (!in_array($defaultJenisLaporan, ['ringkas', 'rinci'], true)) {
            $defaultJenisLaporan = 'ringkas';
        }

        $jenisLaporan = $request->input('jenis_laporan', $defaultJenisLaporan);
        if (!in_array($jenisLaporan, ['ringkas', 'rinci'], true)) {
            $jenisLaporan = 'ringkas';
        }

        if (!$reportFormat['show_detail_kompetensi'] && $jenisLaporan === 'rinci') {
            $jenisLaporan = 'ringkas';
        }

        $filterPangkalan = ($mode === 'perdireksi' || $scope === 'kepala') ? $request->input('pangkalan_id') : null;
        $filterKaryawan = $mode === 'perorangan' ? $request->input('karyawan_id') : null;
        $showPangkalanFilter = $scope === 'admin' && $mode === 'perdireksi';
        $showKaryawanFilter = $scope !== 'user' && $mode === 'perorangan';

        $kategoriList = KategoriKinerja::with('kompetensi')
            ->orderBy('jenis')
            ->orderBy('kode_kategori')
            ->get();

        // For kepala scope, filter kategori to only those mapped to their pangkalan
        if ($scope === 'kepala') {
            $kepalaPangkalanIds = $filterPangkalan
                ? [(int) $filterPangkalan]
                : $user->getAllPangkalanIds();
            $mappedKategoriIds = collect();
            foreach ($kepalaPangkalanIds as $pId) {
                $pangkalan = \App\Models\Pangkalan::with('kategoriKinerja')->find($pId);
                if ($pangkalan) {
                    $mappedKategoriIds = $mappedKategoriIds->merge(
                        $pangkalan->kategoriKinerja->pluck('id')->map(fn($id) => (int) $id)
                    );
                }
            }
            if ($mappedKategoriIds->isNotEmpty()) {
                $kategoriList = $kategoriList->filter(
                    fn($kat) => $mappedKategoriIds->contains((int) $kat->id)
                )->values();
            }
        }

        $karyawanQuery = Karyawan::with([
            'pangkalan.kategoriKinerja',
            'pangkalans.kategoriKinerja',
            'transaksi' => fn($q) => $q
                ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
                ->with('kompetensi.kategoriKinerja'),
        ])
        ->bukanKepala()
        ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun));

        if ($scope === 'kepala') {
            // Filter by selected pangkalan or all kepala's pangkalans
            $kepalaPangkalanIds = $filterPangkalan
                ? [(int) $filterPangkalan]
                : $user->getAllPangkalanIds();
            $karyawanQuery->whereHas('pangkalans', fn($q) => $q->whereIn('pangkalan.id', $kepalaPangkalanIds));
            if ($mode === 'perorangan' && $filterKaryawan) {
                $karyawanQuery->where('id', $filterKaryawan);
            }
        } elseif ($scope === 'user') {
            $karyawanQuery->where('id', $user->karyawan?->id ?? 0);
        } else {
            if ($mode === 'perdireksi' && $filterPangkalan) {
                $karyawanQuery->whereHas('pangkalans', fn($q) => $q->where('pangkalan.id', $filterPangkalan));
            }
            if ($mode === 'perorangan' && $filterKaryawan) {
                $karyawanQuery->where('id', $filterKaryawan);
            }
        }

        $karyawanSummaryCollection = (clone $karyawanQuery)->get();

        if ($scope === 'user' || ($mode === 'perorangan' && $filterKaryawan)) {
            $singleKaryawan = (clone $karyawanQuery)->first();
            if ($singleKaryawan) {
                $kategoriList = LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $singleKaryawan);
            }
        }

        $totalKaryawanFiltered = $karyawanSummaryCollection->count();

        $karyawanListQuery = $karyawanQuery->orderBy('nama_karyawan');
        $karyawanList = $forOutput
            ? $karyawanListQuery->get()
            : $this->paginateWithPerPage($karyawanListQuery, $request, 10);

        $totalKompetensi = $kategoriList->sum(fn($k) => $k->kompetensi->count());
        $totalTransaksi = 0;
        $ratedCount = 0;

        foreach ($karyawanSummaryCollection as $summaryKaryawan) {
            $kategoriUntukKaryawan = LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $summaryKaryawan);
            $applicableKompetensiIds = LaporanScoreCalculator::kompetensiIdsFromKategori($kategoriUntukKaryawan);

            $jumlahDinilai = $summaryKaryawan->transaksi
                ->filter(fn($trx) => $trx->nilai !== null && $applicableKompetensiIds->contains((int) $trx->kompetensi_id))
                ->count();

            $totalTransaksi += $jumlahDinilai;
            if ($jumlahDinilai > 0) {
                $ratedCount++;
            }
        }

        $pangkalanList = $scope === 'admin'
            ? Pangkalan::orderBy('nama_pangkalan')->get()
            : ($scope === 'kepala'
                ? Pangkalan::whereIn('id', $user->getAllPangkalanIds())->where('is_active', true)->orderBy('nama_pangkalan')->get()
                : collect());

        $karyawanFilterList = collect();
        if ($showKaryawanFilter) {
            $karyawanFilterList = Karyawan::query()
                ->bukanKepala()
                ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
                ->when($scope === 'kepala', fn($q) => $q->whereHas('pangkalans', fn($pq) => $pq->whereIn('pangkalan.id', $user->getAllPangkalanIds())))
                ->when($scope === 'user', fn($q) => $q->where('id', $user->karyawan?->id ?? 0))
                ->orderBy('nama_karyawan')
                ->get();
        }

        return compact(
            'tahunList',
            'selectedTahun',
            'selectedTahunData',
            'kategoriList',
            'karyawanList',
            'totalKaryawanFiltered',
            'ratedCount',
            'totalKompetensi',
            'totalTransaksi',
            'setting',
            'mode',
            'jenisLaporan',
            'reportFormat',
            'filterPangkalan',
            'filterKaryawan',
            'showPangkalanFilter',
            'showKaryawanFilter',
            'pangkalanList',
            'karyawanFilterList'
        );
    }

    public function peroranganPdf(Request $request)
    {
        $user = Auth::user();
        $scope = $this->resolveScope();

        $setting = SettingLembaga::where('is_active', true)->latest()->first()
            ?? SettingLembaga::latest()->first();

        $tahunAktif = TahunPenilaian::where('is_active', true)->first();
        $selectedTahun = $request->input('tahun_penilaian_id', $tahunAktif?->id);
        $selectedTahunData = $selectedTahun ? TahunPenilaian::find($selectedTahun) : $tahunAktif;

        $karyawanId = $request->input('karyawan_id');

        if (!$karyawanId) {
            return back()->with('error', 'Pilih karyawan terlebih dahulu.');
        }

        $karyawanQuery = Karyawan::with([
            'pangkalan.kategoriKinerja',
            'pangkalanLain.kategoriKinerja',
            'pangkalans.kategoriKinerja',
            'transaksi' => fn($q) => $q
                ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
                ->with('kompetensi.kategoriKinerja'),
            'tahunPenilaian',
            'user',
        ])
        ->bukanKepala()
        ->where('id', $karyawanId);

        if ($scope === 'kepala') {
            $karyawanQuery->whereHas('pangkalans', fn($q) => $q->whereIn('pangkalan.id', $user->getAllPangkalanIds()));
        } elseif ($scope === 'user') {
            $karyawanQuery->where('id', $user->karyawan?->id ?? 0);
        }

        $karyawan = $karyawanQuery->first();

        if (!$karyawan) {
            return back()->with('error', 'Data karyawan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $kategoriList = KategoriKinerja::with('kompetensi')
            ->orderBy('jenis')
            ->orderBy('kode_kategori')
            ->get();

        $kategoriList = LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $karyawan);

        $reportFormat = [
            'scoring_method' => $setting?->laporan_scoring_method ?: 'weighted_kinerja_kegiatan',
            'score_weight_kinerja' => (float) ($setting?->laporan_bobot_kinerja ?? 70),
            'score_weight_kegiatan' => (float) ($setting?->laporan_bobot_kegiatan ?? 30),
        ];

        if (!in_array($reportFormat['scoring_method'], ['weighted_kategori', 'weighted_kinerja_kegiatan', 'average_kinerja_kegiatan'], true)) {
            $reportFormat['scoring_method'] = 'weighted_kinerja_kegiatan';
        }

        $trxByKompetensi = $karyawan->transaksi
            ->filter(fn($t) => $t->nilai !== null)
            ->keyBy('kompetensi_id');

        // Determine jenis_laporan for perorangan (ringkas vs rinci)
        $jenisLaporan = $request->input('jenis_laporan', 'rinci');
        if (!in_array($jenisLaporan, ['ringkas', 'rinci'], true)) {
            $jenisLaporan = 'rinci';
        }

        // Get all pangkalan IDs for this karyawan
        $allPangkalanIds = $karyawan->getAllPangkalanIds();

        // Load all pangkalan with kategoriKinerja relation
        $allPangkalan = Pangkalan::with('kategoriKinerja')
            ->whereIn('id', $allPangkalanIds)
            ->get();

        // Build per-pangkalan transaksi map (keyed by pangkalan_id => kompetensi_id)
        $trxByPangkalan = [];
        foreach ($allPangkalanIds as $pId) {
            $pIdInt = (int) $pId;
            $trxByPangkalan[$pIdInt] = $karyawan->transaksi
                ->filter(fn($t) => $t->nilai !== null && (int) ($t->pangkalan_id ?? 0) === $pIdInt)
                ->keyBy('kompetensi_id');
            // Fallback: if no pangkalan-specific transaksi, use global (for legacy data)
            if ($trxByPangkalan[$pIdInt]->isEmpty()) {
                $trxByPangkalan[$pIdInt] = $trxByKompetensi;
            }
        }

        // Calculate per-pangkalan breakdown
        $perPangkalanData = LaporanScoreCalculator::calculatePerPangkalan(
            $kategoriList,
            $trxByKompetensi,
            $allPangkalanIds,
            $allPangkalan,
            [
                'bobot_kinerja' => $reportFormat['score_weight_kinerja'],
                'bobot_kegiatan' => $reportFormat['score_weight_kegiatan'],
            ],
            $trxByPangkalan
        );

        $nilaiAkhir = $perPangkalanData['nilaiAkhir'];
        $ratingMeta = LaporanScoreCalculator::ratingMeta($nilaiAkhir);
        $rewardPunishmentInfo = LaporanScoreCalculator::getRewardPunishmentInfo($nilaiAkhir);

        $fileName = 'laporan-pegawai-' . strtolower($karyawan->kode_karyawan) . '.pdf';

        $viewName = $jenisLaporan === 'ringkas'
            ? 'admin.laporan.perorangan_ringkas_pdf'
            : 'admin.laporan.perorangan_pdf';

        return Pdf::loadView($viewName, compact(
            'karyawan',
            'kategoriList',
            'trxByKompetensi',
            'nilaiAkhir',
            'ratingMeta',
            'setting',
            'selectedTahunData',
            'reportFormat',
            'perPangkalanData',
            'allPangkalan',
            'jenisLaporan',
            'trxByPangkalan',
            'rewardPunishmentInfo'
        ))
        ->setPaper('a4', 'portrait')
        ->stream($fileName);
    }

    /**
     * Build data for inline perorangan display in laporan index.
     */
    private function buildPeroranganData(Request $request, string $scope): array
    {
        $user = Auth::user();
        $setting = SettingLembaga::where('is_active', true)->latest()->first()
            ?? SettingLembaga::latest()->first();

        $selectedTahun = $request->input('tahun_penilaian_id');
        $selectedTahunData = $selectedTahun ? TahunPenilaian::find($selectedTahun) : null;
        $karyawanId = $request->input('karyawan_id');

        $karyawanQuery = Karyawan::with([
            'pangkalan.kategoriKinerja',
            'pangkalanLain.kategoriKinerja',
            'pangkalans.kategoriKinerja',
            'transaksi' => fn($q) => $q
                ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
                ->with('kompetensi.kategoriKinerja'),
            'tahunPenilaian',
            'user',
        ])
        ->bukanKepala()
        ->where('id', $karyawanId);

        if ($scope === 'kepala') {
            $karyawanQuery->whereHas('pangkalans', fn($q) => $q->whereIn('pangkalan.id', $user->getAllPangkalanIds()));
        } elseif ($scope === 'user') {
            $karyawanQuery->where('id', $user->karyawan?->id ?? 0);
        }

        $karyawan = $karyawanQuery->first();

        if (!$karyawan) {
            return ['peroranganKaryawan' => null];
        }

        $kategoriList = KategoriKinerja::with('kompetensi')
            ->orderBy('jenis')
            ->orderBy('kode_kategori')
            ->get();

        $kategoriList = LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $karyawan);

        $reportFormat = [
            'scoring_method' => $setting?->laporan_scoring_method ?: 'weighted_kinerja_kegiatan',
            'score_weight_kinerja' => (float) ($setting?->laporan_bobot_kinerja ?? 70),
            'score_weight_kegiatan' => (float) ($setting?->laporan_bobot_kegiatan ?? 30),
        ];

        if (!in_array($reportFormat['scoring_method'], ['weighted_kategori', 'weighted_kinerja_kegiatan', 'average_kinerja_kegiatan'], true)) {
            $reportFormat['scoring_method'] = 'weighted_kinerja_kegiatan';
        }

        $trxByKompetensi = $karyawan->transaksi
            ->filter(fn($t) => $t->nilai !== null)
            ->keyBy('kompetensi_id');

        // Get all pangkalan IDs for this karyawan
        $allPangkalanIds = $karyawan->getAllPangkalanIds();

        // Load all pangkalan with kategoriKinerja relation
        $allPangkalan = Pangkalan::with('kategoriKinerja')
            ->whereIn('id', $allPangkalanIds)
            ->get();

        // Build per-pangkalan transaksi map
        $trxByPangkalan = [];
        foreach ($allPangkalanIds as $pId) {
            $pIdInt = (int) $pId;
            $trxByPangkalan[$pIdInt] = $karyawan->transaksi
                ->filter(fn($t) => $t->nilai !== null && (int) ($t->pangkalan_id ?? 0) === $pIdInt)
                ->keyBy('kompetensi_id');
            if ($trxByPangkalan[$pIdInt]->isEmpty()) {
                $trxByPangkalan[$pIdInt] = $trxByKompetensi;
            }
        }

        // Calculate per-pangkalan breakdown
        $perPangkalanData = LaporanScoreCalculator::calculatePerPangkalan(
            $kategoriList,
            $trxByKompetensi,
            $allPangkalanIds,
            $allPangkalan,
            [
                'bobot_kinerja' => $reportFormat['score_weight_kinerja'],
                'bobot_kegiatan' => $reportFormat['score_weight_kegiatan'],
            ],
            $trxByPangkalan
        );

        $nilaiAkhir = $perPangkalanData['nilaiAkhir'];
        $ratingMeta = LaporanScoreCalculator::ratingMeta($nilaiAkhir);

        return [
            'peroranganKaryawan' => $karyawan,
            'peroranganKategoriList' => $kategoriList,
            'peroranganTrxByKompetensi' => $trxByKompetensi,
            'peroranganNilaiAkhir' => $nilaiAkhir,
            'peroranganRatingMeta' => $ratingMeta,
            'peroranganSetting' => $setting,
            'peroranganReportFormat' => $reportFormat,
            'peroranganPerPangkalanData' => $perPangkalanData,
            'peroranganAllPangkalan' => $allPangkalan,
        ];
    }

    /**
     * Build data for the standalone perorangan page.
     */
    private function buildPeroranganPageData(Request $request, string $scope): array
    {
        $user = Auth::user();
        $setting = SettingLembaga::where('is_active', true)->latest()->first()
            ?? SettingLembaga::latest()->first();

        $tahunList = TahunPenilaian::orderByDesc('periode_penilaian')->get();
        $tahunAktif = TahunPenilaian::where('is_active', true)->first();
        $defaultTahunId = $setting?->tahun_penilaian_id ?: $tahunAktif?->id;
        $selectedTahun = $request->input('tahun_penilaian_id', $defaultTahunId);
        $selectedTahunData = $selectedTahun ? TahunPenilaian::find($selectedTahun) : $tahunAktif;

        $jenisLaporan = $request->input('jenis_laporan', 'rinci');
        if (!in_array($jenisLaporan, ['ringkas', 'rinci'], true)) {
            $jenisLaporan = 'rinci';
        }

        // Build karyawan filter list
        $karyawanFilterList = Karyawan::query()
            ->bukanKepala()
            ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
            ->when($scope === 'kepala', fn($q) => $q->whereHas('pangkalans', fn($pq) => $pq->whereIn('pangkalan.id', $user->getAllPangkalanIds())))
            ->orderBy('nama_karyawan')
            ->get();

        $data = [
            'tahunList' => $tahunList,
            'selectedTahun' => $selectedTahun,
            'selectedTahunData' => $selectedTahunData,
            'setting' => $setting,
            'jenisLaporan' => $jenisLaporan,
            'karyawanFilterList' => $karyawanFilterList,
            'peroranganKaryawan' => null,
        ];

        // If karyawan selected, build perorangan detail
        if ($request->filled('karyawan_id')) {
            $peroranganData = $this->buildPeroranganData($request, $scope);
            $data = array_merge($data, $peroranganData);
        }

        return $data;
    }
}
