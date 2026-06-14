<?php

namespace App\Http\Controllers;

use App\Models\SettingLembaga;
use Illuminate\Http\Request;

class PenilaianMetodeController extends Controller
{
    public function edit()
    {
        $setting = $this->resolveSetting();

        return view('admin.penilaian_metode.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'laporan_scoring_method' => 'required|in:weighted_kategori,weighted_kinerja_kegiatan,average_kinerja_kegiatan',
            'laporan_bobot_kinerja' => 'required|numeric|min:0|max:100',
            'laporan_bobot_kegiatan' => 'required|numeric|min:0|max:100',
        ]);

        $setting = $this->resolveSetting();

        $scoringMethod = (string) $request->input('laporan_scoring_method', 'weighted_kinerja_kegiatan');
        if ($scoringMethod === 'average_kinerja_kegiatan') {
            $scoringMethod = 'weighted_kinerja_kegiatan';
        }

        $bobotKinerja = (float) $request->input('laporan_bobot_kinerja', 70);
        $bobotKegiatan = (float) $request->input('laporan_bobot_kegiatan', 30);

        $totalBobot = $bobotKinerja + $bobotKegiatan;
        if (abs($totalBobot - 100.0) > 0.01) {
            return back()
                ->withInput()
                ->withErrors([
                    'laporan_bobot_kinerja' => 'Total bobot Kinerja dan Kegiatan harus tepat 100%.',
                ]);
        }

        $setting->update([
            'laporan_scoring_method' => $scoringMethod,
            'laporan_bobot_kinerja' => $bobotKinerja,
            'laporan_bobot_kegiatan' => $bobotKegiatan,
        ]);

        return redirect()->route('admin.penilaian-metode.edit')
            ->with('success', 'Pengaturan cara penilaian berhasil diperbarui.');
    }

    private function resolveSetting(): SettingLembaga
    {
        $setting = SettingLembaga::where('is_active', true)->latest()->first()
            ?? SettingLembaga::latest()->first();

        if ($setting) {
            return $setting;
        }

        return SettingLembaga::create([
            'is_active' => true,
            'show_logo' => true,
            'show_tahun_ajaran' => true,
            'show_nama_pimpinan' => true,
            'show_tanda_tangan' => true,
            'sidebar_title' => 'Website Aplikasi',
            'sidebar_subtitle_1' => 'Sistem Manajemen Kinerja Pengabdian',
            'sidebar_subtitle_2' => 'Yayasan Pondok Pesantren Al-Huda Mugomulyo',
            'laporan_scoring_method' => 'weighted_kinerja_kegiatan',
            'laporan_bobot_kinerja' => 70,
            'laporan_bobot_kegiatan' => 30,
        ]);
    }
}
