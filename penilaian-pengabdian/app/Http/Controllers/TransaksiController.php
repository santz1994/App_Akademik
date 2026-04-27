<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Karyawan;
use App\Models\Kompetensi;
use App\Models\KategoriKinerja;
use App\Models\Pangkalan;
use App\Models\SettingLembaga;
use App\Models\TahunPenilaian;
use App\Models\PerformanceRating;
use App\Models\PenilaianLock;
use App\Models\PenilaianUnlockRequest;
use App\Support\LaporanScoreCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $tahunList     = TahunPenilaian::orderByDesc('periode_penilaian')->get();
        $tahunAktif    = TahunPenilaian::where('is_active', true)->first();
        $selectedTahun = $request->input('tahun_penilaian_id', $tahunAktif?->id);
        $totalKompetensi = Kompetensi::count();
        $search = trim((string) $request->input('q'));
        $filterPangkalan = $request->input('pangkalan_id');
        $filterStatusLock = $request->input('status_lock');
        $filterStatusAktif = $request->input('status_aktif');

        $karyawanList = Karyawan::with([
            'pangkalan.kategoriKinerja',
            'transaksi' => fn($q) => $q->when($selectedTahun, fn($q) =>
                $q->where('tahun_penilaian_id', $selectedTahun))->with('kompetensi'),
            'penilaianLocks' => fn($q) => $q->when($selectedTahun, fn($q) =>
                $q->where('tahun_penilaian_id', $selectedTahun)),
        ])
        ->bukanKepala()
        ->when($search !== '', function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('nama_karyawan', 'like', "%{$search}%")
                    ->orWhere('kode_karyawan', 'like', "%{$search}%");
            });
        })
        ->when($filterPangkalan, fn($q) => $q->where('pangkalan_id', $filterPangkalan))
        ->when($filterStatusAktif === 'aktif', fn($q) => $q->where('is_active', true))
        ->when($filterStatusAktif === 'nonaktif', fn($q) => $q->where('is_active', false))
        ->when($filterStatusLock === 'locked' && $selectedTahun, fn($q) =>
            $q->whereHas('penilaianLocks', fn($lq) => $lq
                ->where('tahun_penilaian_id', $selectedTahun)
                ->where('is_locked', true)
            )
        )
        ->when($filterStatusLock === 'unlocked' && $selectedTahun, fn($q) =>
            $q->where(function ($sub) use ($selectedTahun) {
                $sub->whereHas('penilaianLocks', fn($lq) => $lq
                    ->where('tahun_penilaian_id', $selectedTahun)
                    ->where('is_locked', false)
                )
                ->orWhereDoesntHave('penilaianLocks', fn($lq) => $lq->where('tahun_penilaian_id', $selectedTahun));
            })
        )
        ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
        ->orderBy('nama_karyawan');

        $karyawanList = $this->paginateWithPerPage($karyawanList, $request, 10);

        $pangkalanList = Pangkalan::orderBy('nama_pangkalan')->get();
        $kategoriListForScore = KategoriKinerja::with('kompetensi:id')->orderBy('jenis')->orderBy('kode_kategori')->get();
        $setting = SettingLembaga::where('is_active', true)->latest()->first()
            ?? SettingLembaga::latest()->first();
        $scoreMethod = $setting?->laporan_scoring_method;
        if (!in_array($scoreMethod, ['weighted_kategori', 'weighted_kinerja_kegiatan', 'average_kinerja_kegiatan'], true)) {
            $scoreMethod = 'weighted_kinerja_kegiatan';
        }
        if ($scoreMethod === 'average_kinerja_kegiatan') {
            $scoreMethod = 'weighted_kinerja_kegiatan';
        }
        $scoreWeightKinerja = max(0.0, min(100.0, (float) ($setting?->laporan_bobot_kinerja ?? 70)));
        $scoreWeightKegiatan = max(0.0, min(100.0, (float) ($setting?->laporan_bobot_kegiatan ?? 30)));
        $pendingUnlockCount = PenilaianUnlockRequest::where('status', 'pending')->count();

        return view('admin.transaksi.index', compact(
            'karyawanList',
            'tahunList',
            'selectedTahun',
            'totalKompetensi',
            'kategoriListForScore',
            'scoreMethod',
            'scoreWeightKinerja',
            'scoreWeightKegiatan',
            'pendingUnlockCount',
            'search',
            'filterPangkalan',
            'filterStatusLock',
            'filterStatusAktif',
            'pangkalanList'
        ));
    }

    public function kepalaIndex(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->is_kepala, 403);

        $tahunList     = TahunPenilaian::orderByDesc('periode_penilaian')->get();
        $tahunAktif    = TahunPenilaian::where('is_active', true)->first();
        $selectedTahun = $request->input('tahun_penilaian_id', $tahunAktif?->id);
        $totalKompetensi = Kompetensi::count();
        $search = trim((string) $request->input('q'));

        $karyawanList = Karyawan::with([
            'pangkalan.kategoriKinerja',
            'transaksi' => fn($q) => $q
                ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
                ->with('kompetensi'),
            'penilaianLocks' => fn($q) => $q->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun)),
        ])
        ->bukanKepala()
        ->where('pangkalan_id', $user->pangkalan_id)
        ->when($search !== '', function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('nama_karyawan', 'like', "%{$search}%")
                    ->orWhere('kode_karyawan', 'like', "%{$search}%");
            });
        })
        ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
        ->orderBy('nama_karyawan');

        $karyawanList = $this->paginateWithPerPage($karyawanList, $request, 10);

        $routePrefix = 'kepala';
        $isKepalaView = true;
        $filterPangkalan = null;
        $filterStatusLock = null;
        $filterStatusAktif = null;
        $pangkalanList = collect();
        $kategoriListForScore = KategoriKinerja::with('kompetensi:id')->orderBy('jenis')->orderBy('kode_kategori')->get();
        $setting = SettingLembaga::where('is_active', true)->latest()->first()
            ?? SettingLembaga::latest()->first();
        $scoreMethod = $setting?->laporan_scoring_method;
        if (!in_array($scoreMethod, ['weighted_kategori', 'weighted_kinerja_kegiatan', 'average_kinerja_kegiatan'], true)) {
            $scoreMethod = 'weighted_kinerja_kegiatan';
        }
        if ($scoreMethod === 'average_kinerja_kegiatan') {
            $scoreMethod = 'weighted_kinerja_kegiatan';
        }
        $scoreWeightKinerja = max(0.0, min(100.0, (float) ($setting?->laporan_bobot_kinerja ?? 70)));
        $scoreWeightKegiatan = max(0.0, min(100.0, (float) ($setting?->laporan_bobot_kegiatan ?? 30)));
        $pendingUnlockCount = 0;

        return view('admin.transaksi.index', compact(
            'karyawanList',
            'tahunList',
            'selectedTahun',
            'totalKompetensi',
            'kategoriListForScore',
            'scoreMethod',
            'scoreWeightKinerja',
            'scoreWeightKegiatan',
            'pendingUnlockCount',
            'routePrefix',
            'isKepalaView',
            'search',
            'filterPangkalan',
            'filterStatusLock',
            'filterStatusAktif',
            'pangkalanList'
        ));
    }

    public function create(Request $request)
    {
        $karyawanList  = Karyawan::with('pangkalan.kategoriKinerja')->bukanKepala()->orderBy('nama_karyawan')->get();
        $tahunList     = TahunPenilaian::orderByDesc('periode_penilaian')->get();
        $tahunAktif    = TahunPenilaian::where('is_active', true)->first();
        $kategoriBase = KategoriKinerja::with(['kompetensi' => fn($q) => $q->orderBy('kode_kompetensi')])
            ->orderBy('kode_kategori')
            ->get();
        $kategoriList = $kategoriBase;

        $selectedKaryawan = null;
        $selectedTahun    = null;
        $existingNilai    = [];
        $lockedKompetensiIds = [];
        $isLocked = false;
        $canEditLockedScores = false;
        $hasPendingUnlockRequest = false;

        if ($request->filled('karyawan_id') && $request->filled('tahun_penilaian_id')) {
            $selectedKaryawan = Karyawan::with('pangkalan.kategoriKinerja')->bukanKepala()->find($request->karyawan_id);
            $selectedTahun    = TahunPenilaian::find($request->tahun_penilaian_id);

            if ($selectedKaryawan) {
                $existingNilai = Transaksi::where('karyawan_id', $request->karyawan_id)
                    ->where('tahun_penilaian_id', $request->tahun_penilaian_id)
                    ->whereNotNull('nilai')
                    ->pluck('nilai', 'kompetensi_id')
                    ->toArray();

                $lockState = $this->resolveLockState((int) $request->karyawan_id, (int) $request->tahun_penilaian_id);
                $isLocked = $lockState['is_locked'];
                $canEditLockedScores = $this->canEditLockedScores($lockState);
                $kategoriList = $this->resolveKategoriListForKaryawan($selectedKaryawan, $kategoriBase);
                $lockedKompetensiIds = $canEditLockedScores
                    ? []
                    : array_map('intval', array_keys($existingNilai));
                if ($isLocked) {
                    $hasPendingUnlockRequest = PenilaianUnlockRequest::where('karyawan_id', $selectedKaryawan->id)
                        ->where('tahun_penilaian_id', $request->tahun_penilaian_id)
                        ->where('status', 'pending')
                        ->exists();
                }
            }
        }

        return view('admin.transaksi.create', compact(
            'karyawanList', 'tahunList', 'tahunAktif',
            'kategoriList', 'selectedKaryawan', 'selectedTahun', 'existingNilai', 'lockedKompetensiIds', 'isLocked', 'canEditLockedScores', 'hasPendingUnlockRequest'
        ));
    }

    public function kepalaCreate(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->is_kepala, 403);

        $karyawanList  = Karyawan::with('pangkalan.kategoriKinerja')
            ->bukanKepala()
            ->where('pangkalan_id', $user->pangkalan_id)
            ->orderBy('nama_karyawan')
            ->get();
        $tahunList     = TahunPenilaian::orderByDesc('periode_penilaian')->get();
        $tahunAktif    = TahunPenilaian::where('is_active', true)->first();
        $kategoriBase  = KategoriKinerja::with(['kompetensi' => fn($q) => $q->orderBy('kode_kompetensi')])
            ->orderBy('kode_kategori')
            ->get();
        $kategoriList = $kategoriBase;

        $selectedKaryawan = null;
        $selectedTahun    = null;
        $existingNilai    = [];
        $lockedKompetensiIds = [];
        $isLocked = false;
        $canEditLockedScores = false;
        $hasPendingUnlockRequest = false;

        if ($request->filled('karyawan_id') && $request->filled('tahun_penilaian_id')) {
            $selectedKaryawan = Karyawan::with('pangkalan.kategoriKinerja')
                ->bukanKepala()
                ->where('pangkalan_id', $user->pangkalan_id)
                ->find($request->karyawan_id);

            $selectedTahun = TahunPenilaian::find($request->tahun_penilaian_id);

            if ($selectedKaryawan) {
                $existingNilai = Transaksi::where('karyawan_id', $selectedKaryawan->id)
                    ->where('tahun_penilaian_id', $request->tahun_penilaian_id)
                    ->whereNotNull('nilai')
                    ->pluck('nilai', 'kompetensi_id')
                    ->toArray();

                $lockState = $this->resolveLockState((int) $selectedKaryawan->id, (int) $request->tahun_penilaian_id);
                $isLocked = $lockState['is_locked'];
                $canEditLockedScores = $this->canEditLockedScores($lockState);
                $kategoriList = $this->resolveKategoriListForKaryawan($selectedKaryawan, $kategoriBase);
                $lockedKompetensiIds = $canEditLockedScores
                    ? []
                    : array_map('intval', array_keys($existingNilai));
                if ($isLocked) {
                    $hasPendingUnlockRequest = PenilaianUnlockRequest::where('karyawan_id', $selectedKaryawan->id)
                        ->where('tahun_penilaian_id', $request->tahun_penilaian_id)
                        ->where('status', 'pending')
                        ->exists();
                }
            }
        }

        $routePrefix = 'kepala';

        return view('admin.transaksi.create', compact(
            'karyawanList',
            'tahunList',
            'tahunAktif',
            'kategoriList',
            'selectedKaryawan',
            'selectedTahun',
            'existingNilai',
            'lockedKompetensiIds',
            'routePrefix',
            'isLocked',
            'canEditLockedScores',
            'hasPendingUnlockRequest'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'        => 'required|exists:karyawan,id',
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
            'nilai'              => 'required|array',
            'nilai.*'            => 'nullable|numeric|min:0|max:100',
        ]);

        $karyawanId = $request->karyawan_id;
        $tahunId    = $request->tahun_penilaian_id;

        $user = Auth::user();
        abort_unless($user && $user->canAccessPenilaianArea(), 403);

        $karyawan = Karyawan::with(['pangkalan.kategoriKinerja', 'user'])->findOrFail($karyawanId);
        if ($karyawan->user?->is_kepala) {
            return back()->withInput()->with('error', 'Data Kepala tidak termasuk objek penilaian karyawan.');
        }

        $lockState = $this->resolveLockState((int) $karyawanId, (int) $tahunId);
        if ($lockState['is_locked']) {
            return back()->withInput()->with('error', 'Nilai sudah terkunci. Ajukan unlock terlebih dahulu.');
        }

        if ($user && $user->is_kepala) {
            $isAllowed = (int) $karyawan->pangkalan_id === (int) $user->pangkalan_id;

            if (!$isAllowed) {
                return back()->with('error', 'Anda hanya dapat menilai karyawan dalam pangkalan yang sama.');
            }
        }

        $nilaiInput = (array) $request->input('nilai', []);
        $kategoriList = $this->resolveKategoriListForKaryawan($karyawan);
        $allowedKompetensiIds = $this->resolveAllowedKompetensiIds($kategoriList);
        $existingScoredKompetensiIds = Transaksi::where('karyawan_id', $karyawanId)
            ->where('tahun_penilaian_id', $tahunId)
            ->whereNotNull('nilai')
            ->pluck('kompetensi_id')
            ->map(fn($id) => (int) $id)
            ->intersect($allowedKompetensiIds)
            ->values();
        $canEditLockedScores = $this->canEditLockedScores($lockState);
        $existingLockedKompetensiIds = $canEditLockedScores
            ? collect()
            : $existingScoredKompetensiIds;
        $scoredKompetensiIds = $this->extractScoredKompetensiIds($nilaiInput)
            ->intersect($allowedKompetensiIds)
            ->reject(fn($id) => $existingLockedKompetensiIds->contains((int) $id))
            ->values();
        $clearedKompetensiIds = $this->extractClearedKompetensiIds($nilaiInput)
            ->intersect($allowedKompetensiIds)
            ->reject(fn($id) => $existingLockedKompetensiIds->contains((int) $id))
            ->values();
        $finalScoredKompetensiIds = $existingScoredKompetensiIds
            ->diff($clearedKompetensiIds)
            ->merge($scoredKompetensiIds)
            ->unique()
            ->values();

        if ($scoredKompetensiIds->isEmpty() && $existingScoredKompetensiIds->isEmpty()) {
            return back()->withInput()->withErrors([
                'nilai' => 'Minimal isi satu nilai valid (0 - 100) pada indikator yang sesuai kategori penilaian.',
            ]);
        }

        if ($karyawan->pangkalan_id) {
            $kinerjaKompetensiIds = $kategoriList
                ->filter(fn($kategori) => strtolower((string) $kategori->jenis) === 'kinerja')
                ->flatMap(fn($kategori) => $kategori->kompetensi->pluck('id'))
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            if ($kinerjaKompetensiIds->isNotEmpty() && $finalScoredKompetensiIds->intersect($kinerjaKompetensiIds)->isEmpty()) {
                return back()->withInput()->withErrors([
                    'nilai' => 'Karyawan yang memiliki pangkalan wajib memiliki minimal satu penilaian kinerja (nilai 0 - 100).',
                ]);
            }
        }

        $missingMandatory = [];
        $mandatoryKategoriList = $kategoriList
            ->filter(fn($kategori) => strtolower((string) $kategori->jenis) === 'kegiatan' && (bool) $kategori->is_wajib)
            ->values();

        foreach ($mandatoryKategoriList as $kategori) {
            $mandatoryKompetensiIds = $kategori->kompetensi
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            if ($mandatoryKompetensiIds->isEmpty()) {
                continue;
            }

            if ($finalScoredKompetensiIds->intersect($mandatoryKompetensiIds)->isEmpty()) {
                $missingMandatory[] = $kategori->kategori;
            }
        }

        if (!empty($missingMandatory)) {
            return back()->withInput()->withErrors([
                'nilai' => 'Kategori kegiatan wajib belum dinilai: ' . implode(', ', $missingMandatory) . '.',
            ]);
        }

        $counter    = Transaksi::max('id') ?? 0;
        $savedAny = false;

        foreach ($nilaiInput as $kompetensiId => $nilai) {
            $kompetensiId = (int) $kompetensiId;
            if (!$allowedKompetensiIds->contains($kompetensiId)) {
                continue;
            }

            if (!$canEditLockedScores && $existingLockedKompetensiIds->contains($kompetensiId)) {
                continue;
            }

            $numericNilai = is_numeric($nilai) ? (float) $nilai : null;

            if ($numericNilai === null) {
                Transaksi::where('karyawan_id', $karyawanId)
                    ->where('tahun_penilaian_id', $tahunId)
                    ->where('kompetensi_id', $kompetensiId)
                    ->delete();
                continue;
            }

            $counter++;
            Transaksi::updateOrCreate(
                [
                    'karyawan_id'        => $karyawanId,
                    'tahun_penilaian_id' => $tahunId,
                    'kompetensi_id'      => $kompetensiId,
                ],
                [
                    'kode_transaksi' => 'TRX-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
                    'nilai'          => $numericNilai,
                ]
            );
            $savedAny = true;
        }

        if (!$savedAny) {
            return back()->withInput()->withErrors([
                'nilai' => 'Tidak ada nilai baru yang disimpan. Indikator yang sudah terisi terkunci otomatis sampai admin melakukan unlock.',
            ]);
        }

        $routeName = ($user && $user->is_kepala) ? 'kepala.transaksi.index' : 'admin.transaksi.index';

        return redirect()->route($routeName, ['tahun_penilaian_id' => $tahunId])
            ->with('success', 'Nilai penilaian berhasil disimpan.');
    }

    public function submitFinal(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
        ]);

        $user = Auth::user();
        abort_unless($user && $user->is_kepala, 403);

        $karyawanId = (int) $request->karyawan_id;
        $tahunId = (int) $request->tahun_penilaian_id;

        $karyawan = Karyawan::with('user')
            ->where('id', $karyawanId)
            ->where('pangkalan_id', $user->pangkalan_id)
            ->first();

        $isAllowed = $karyawan && !($karyawan->user?->is_kepala);

        if (!$isAllowed) {
            return back()->with('error', 'Akses ditolak untuk karyawan ini.');
        }

        PenilaianLock::updateOrCreate(
            ['karyawan_id' => $karyawanId, 'tahun_penilaian_id' => $tahunId],
            [
                'is_final_submitted' => true,
                'is_locked' => true,
                'submitted_by_user_id' => $user->id,
                'submitted_at' => now(),
                'locked_by_user_id' => $user->id,
                'locked_at' => now(),
            ]
        );

        return back()->with('success', 'Nilai final telah disubmit dan dikunci.');
    }

    public function lock(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
        ]);

        $user = Auth::user();
        abort_unless($user && $user->role === 'admin', 403);

        $karyawanId = (int) $request->karyawan_id;
        $tahunId = (int) $request->tahun_penilaian_id;

        $karyawan = Karyawan::with('user')->findOrFail($karyawanId);
        if ($karyawan->user?->is_kepala) {
            return back()->with('error', 'Data Kepala tidak termasuk objek penilaian karyawan.');
        }

        $lockState = $this->resolveLockState($karyawanId, $tahunId);
        if (!$lockState['has_scores']) {
            return back()->with('error', 'Belum ada nilai tersimpan untuk dikunci.');
        }

        PenilaianLock::updateOrCreate(
            ['karyawan_id' => $karyawanId, 'tahun_penilaian_id' => $tahunId],
            [
                'is_final_submitted' => true,
                'is_locked' => true,
                'locked_by_user_id' => $user->id,
                'locked_at' => now(),
            ]
        );

        return back()->with('success', 'Nilai berhasil dikunci oleh admin.');
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
        ]);

        $user = Auth::user();
        abort_unless($user && $user->role === 'admin', 403);

        $karyawanId = (int) $request->karyawan_id;
        $tahunId = (int) $request->tahun_penilaian_id;

        $karyawan = Karyawan::with('user')->findOrFail($karyawanId);
        if ($karyawan->user?->is_kepala) {
            return back()->with('error', 'Data Kepala tidak termasuk objek penilaian karyawan.');
        }

        PenilaianLock::updateOrCreate(
            ['karyawan_id' => $karyawanId, 'tahun_penilaian_id' => $tahunId],
            [
                'is_locked' => false,
                'is_final_submitted' => false,
                'unlocked_by_user_id' => $user->id,
                'unlocked_at' => now(),
            ]
        );

        return back()->with('success', 'Nilai berhasil dibuka lock oleh admin.');
    }

    public function batchUnlock(Request $request)
    {
        $request->validate([
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
            'karyawan_ids' => 'required|array|min:1',
            'karyawan_ids.*' => 'required|exists:karyawan,id',
        ]);

        $user = Auth::user();
        abort_unless($user && $user->role === 'admin', 403);

        $tahunId = (int) $request->tahun_penilaian_id;
        $karyawanIds = collect($request->karyawan_ids)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $validKaryawanIds = Karyawan::query()
            ->with('user:id,is_kepala')
            ->whereIn('id', $karyawanIds)
            ->get()
            ->filter(fn($k) => !($k->user?->is_kepala))
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values();

        if ($validKaryawanIds->isEmpty()) {
            return back()->with('error', 'Tidak ada data valid untuk batch unlock.');
        }

        foreach ($validKaryawanIds as $karyawanId) {
            PenilaianLock::updateOrCreate(
                ['karyawan_id' => $karyawanId, 'tahun_penilaian_id' => $tahunId],
                [
                    'is_locked' => false,
                    'is_final_submitted' => false,
                    'unlocked_by_user_id' => $user->id,
                    'unlocked_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Batch unlock berhasil untuk ' . $validKaryawanIds->count() . ' karyawan.');
    }

    public function requestUnlock(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
            'alasan' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        abort_unless($user && $user->is_kepala, 403);

        $karyawanId = (int) $request->karyawan_id;
        $tahunId = (int) $request->tahun_penilaian_id;

        $karyawan = Karyawan::with('user')
            ->where('id', $karyawanId)
            ->where('pangkalan_id', $user->pangkalan_id)
            ->first();

        $isAllowed = $karyawan && !($karyawan->user?->is_kepala);

        if (!$isAllowed) {
            return back()->with('error', 'Akses ditolak untuk karyawan ini.');
        }

        $lockState = $this->resolveLockState($karyawanId, $tahunId);
        if (!$lockState['is_locked']) {
            return back()->with('error', 'Data nilai belum terkunci, sehingga tidak memerlukan request unlock.');
        }

        if (!$lockState['has_scores']) {
            return back()->with('error', 'Belum ada nilai tersimpan untuk diajukan unlock.');
        }

        $hasPendingRequest = PenilaianUnlockRequest::where('karyawan_id', $karyawanId)
            ->where('tahun_penilaian_id', $tahunId)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingRequest) {
            return back()->with('error', 'Sudah ada permintaan unlock yang masih pending untuk data ini.');
        }

        PenilaianUnlockRequest::create([
            'karyawan_id' => $karyawanId,
            'tahun_penilaian_id' => $tahunId,
            'requested_by_user_id' => $user->id,
            'alasan' => $request->alasan,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Permintaan unlock telah dikirim ke admin.');
    }

    public function unlockRequests(Request $request)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $requests = PenilaianUnlockRequest::with(['karyawan', 'tahunPenilaian', 'requestedBy'])
            ->latest();

        $requests = $this->paginateWithPerPage($requests, $request, 10);

        return view('admin.transaksi.unlock_requests', compact('requests'));
    }

    public function reviewUnlockRequest(Request $request, PenilaianUnlockRequest $unlockRequest)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        if ($unlockRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $status = $request->status;
        $unlockRequest->update([
            'status' => $status,
            'reviewed_by_user_id' => Auth::id(),
            'reviewed_at' => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        if ($status === 'approved') {
            PenilaianLock::updateOrCreate(
                [
                    'karyawan_id' => $unlockRequest->karyawan_id,
                    'tahun_penilaian_id' => $unlockRequest->tahun_penilaian_id,
                ],
                [
                    'is_locked' => false,
                    'is_final_submitted' => false,
                    'unlocked_by_user_id' => Auth::id(),
                    'unlocked_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Permintaan unlock berhasil diproses.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ]);

        $sheets = Excel::toArray([], $request->file('file'));
        $rows = $sheets[0] ?? [];

        if (empty($rows)) {
            return back()->with('error', 'File import kosong atau format tidak dikenali.');
        }

        $counter = Transaksi::max('id') ?? 0;
        $imported = 0;
        $skipped = 0;

        foreach ($rows as $idx => $row) {
            if (!is_array($row)) {
                $skipped++;
                continue;
            }

            if ($idx === 0 && $this->looksLikeHeaderRow($row)) {
                continue;
            }

            $kodeKaryawan = trim((string)($row[0] ?? ''));
            $tahunRaw = trim((string)($row[1] ?? ''));
            $kodeKompetensi = trim((string)($row[2] ?? ''));
            $nilaiRaw = $row[3] ?? null;
            $keterangan = isset($row[4]) ? trim((string)$row[4]) : null;

            if ($kodeKaryawan === '' || $tahunRaw === '' || $kodeKompetensi === '' || $nilaiRaw === null || $nilaiRaw === '') {
                $skipped++;
                continue;
            }

            $karyawan = Karyawan::where('kode_karyawan', $kodeKaryawan)->first();
            if (!$karyawan) {
                $skipped++;
                continue;
            }

            $kategoriList = $this->resolveKategoriListForKaryawan($karyawan);
            $allowedKompetensiIds = $this->resolveAllowedKompetensiIds($kategoriList);

            $tahun = is_numeric($tahunRaw)
                ? TahunPenilaian::find((int)$tahunRaw)
                : TahunPenilaian::where('periode_penilaian', $tahunRaw)->first();
            if (!$tahun) {
                $skipped++;
                continue;
            }

            $kompetensi = Kompetensi::where('kode_kompetensi', $kodeKompetensi)->first();
            if (!$kompetensi) {
                $skipped++;
                continue;
            }

            if (!$allowedKompetensiIds->contains((int) $kompetensi->id)) {
                $skipped++;
                continue;
            }

            $nilai = is_numeric($nilaiRaw) ? (float)$nilaiRaw : null;
            if ($nilai === null || $nilai < 0 || $nilai > 100) {
                $skipped++;
                continue;
            }

            $counter++;
            Transaksi::updateOrCreate(
                [
                    'karyawan_id' => $karyawan->id,
                    'tahun_penilaian_id' => $tahun->id,
                    'kompetensi_id' => $kompetensi->id,
                ],
                [
                    'kode_transaksi' => 'TRX-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
                    'nilai' => $nilai,
                    'keterangan' => $keterangan,
                ]
            );

            $imported++;
        }

        return back()->with('success', "Import selesai. Berhasil: {$imported}, dilewati: {$skipped}.");
    }

    private function looksLikeHeaderRow(array $row): bool
    {
        $header = strtolower(implode(' ', array_map(fn($v) => trim((string)$v), $row)));

        return str_contains($header, 'kode_karyawan')
            || str_contains($header, 'tahun')
            || str_contains($header, 'kompetensi')
            || str_contains($header, 'nilai');
    }

    public function edit(Transaksi $transaksi)
    {
        // Redirect to scoresheet for that karyawan
        $user = Auth::user();
        $routeName = ($user && $user->is_kepala) ? 'kepala.transaksi.create' : 'admin.transaksi.create';

        return redirect()->route($routeName, [
            'karyawan_id'        => $transaksi->karyawan_id,
            'tahun_penilaian_id' => $transaksi->tahun_penilaian_id,
        ]);
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        return redirect()->route('admin.transaksi.index');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return back()->with('success', 'Data penilaian dihapus.');
    }

    /** Delete all penilaian for one karyawan in one tahun */
    public function destroyByKaryawan(Request $request, Karyawan $karyawan)
    {
        $tahunId = $request->input('tahun_penilaian_id');
        Transaksi::where('karyawan_id', $karyawan->id)
            ->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->delete();

        PenilaianLock::where('karyawan_id', $karyawan->id)
            ->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->delete();

        return redirect()->route('admin.transaksi.index', ['tahun_penilaian_id' => $tahunId])
            ->with('success', 'Semua penilaian karyawan berhasil dihapus.');
    }

    private function resolveLockState(int $karyawanId, int $tahunId): array
    {
        $lock = PenilaianLock::where('karyawan_id', $karyawanId)
            ->where('tahun_penilaian_id', $tahunId)
            ->first();

        $hasScores = Transaksi::where('karyawan_id', $karyawanId)
            ->where('tahun_penilaian_id', $tahunId)
            ->whereNotNull('nilai')
            ->exists();

        $isLocked = $lock ? (bool) $lock->is_locked : false;

        return [
            'lock' => $lock,
            'has_scores' => $hasScores,
            'is_locked' => $isLocked,
        ];
    }

    private function extractScoredKompetensiIds(array $nilaiInput)
    {
        return collect($nilaiInput)
            ->filter(fn($nilai) => is_numeric($nilai))
            ->keys()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();
    }

    private function extractClearedKompetensiIds(array $nilaiInput)
    {
        return collect($nilaiInput)
            ->filter(fn($nilai) => $nilai === null || $nilai === '')
            ->keys()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();
    }

    private function resolveAllowedKompetensiIds($kategoriList)
    {
        return LaporanScoreCalculator::kompetensiIdsFromKategori($kategoriList);
    }

    private function canEditLockedScores(array $lockState): bool
    {
        return (bool) ($lockState['lock'] && !$lockState['is_locked']);
    }

    private function resolveKategoriListForKaryawan(?Karyawan $karyawan, $baseList = null)
    {
        $kategoriList = $baseList ?? KategoriKinerja::with([
            'kompetensi' => fn($q) => $q->orderBy('kode_kompetensi')
        ])->orderBy('kode_kategori')->get();

        return LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $karyawan);
    }
}
