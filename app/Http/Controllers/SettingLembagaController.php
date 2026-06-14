<?php

namespace App\Http\Controllers;

use App\Models\SettingLembaga;
use App\Models\TahunPenilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingLembagaController extends Controller
{
    public function edit()
    {
        try {
            $setting = $this->resolveSetting();
        } catch (\Exception $e) {
            Log::error('SettingLembaga resolveSetting failed: '.$e->getMessage());

            return back()->with('error', 'Gagal memuat pengaturan lembaga. Silakan coba lagi.');
        }

        $tahunList = TahunPenilaian::orderByDesc('periode_penilaian')->get();

        return view('admin.setting_lembaga.edit', compact('setting', 'tahunList'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_lembaga' => 'nullable|string|max:255',
            'nama_yayasan' => 'nullable|string|max:255',
            'alamat_lembaga' => 'nullable|string|max:255',
            'telepon_lembaga' => 'nullable|string|max:100',
            'email_lembaga' => 'nullable|email|max:150',
            'website_lembaga' => 'nullable|string|max:150',
            'sidebar_title' => 'nullable|string|max:150',
            'sidebar_subtitle_1' => 'nullable|string|max:255',
            'sidebar_subtitle_2' => 'nullable|string|max:255',
            'sidebar_show_title' => 'nullable|boolean',
            'sidebar_show_subtitle_1' => 'nullable|boolean',
            'sidebar_show_subtitle_2' => 'nullable|boolean',
            'lokasi_surat' => 'nullable|string|max:150',
            'nama_ketua_yayasan' => 'nullable|string|max:255',
            'nama_ketua_babinlumni' => 'nullable|string|max:255',
            'tahun_penilaian_id' => 'nullable|exists:tahun_penilaian,id',

            'logo' => 'nullable|image|mimes:png|max:1024',
            'ttd_ketua_yayasan' => 'nullable|image|mimes:png|max:512',
            'ttd_ketua_babinlumni' => 'nullable|image|mimes:png|max:512',

            'show_logo' => 'nullable|boolean',
            'show_tahun_ajaran' => 'nullable|boolean',
            'show_nama_pimpinan' => 'nullable|boolean',
            'show_tanda_tangan' => 'nullable|boolean',
            'lock_enabled' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $setting = $this->resolveSetting();

        $data = $request->only([
            'nama_lembaga',
            'nama_yayasan',
            'alamat_lembaga',
            'telepon_lembaga',
            'email_lembaga',
            'website_lembaga',
            'sidebar_title',
            'sidebar_subtitle_1',
            'sidebar_subtitle_2',
            'lokasi_surat',
            'nama_ketua_yayasan',
            'nama_ketua_babinlumni',
            'tahun_penilaian_id',
        ]);

        // Sidebar text may be intentionally left blank by user.
        $data['sidebar_title'] = trim((string) $request->input('sidebar_title', ''));
        $data['sidebar_subtitle_1'] = trim((string) $request->input('sidebar_subtitle_1', ''));
        $data['sidebar_subtitle_2'] = trim((string) $request->input('sidebar_subtitle_2', ''));
        $data['sidebar_show_title'] = $request->boolean('sidebar_show_title');
        $data['sidebar_show_subtitle_1'] = $request->boolean('sidebar_show_subtitle_1');
        $data['sidebar_show_subtitle_2'] = $request->boolean('sidebar_show_subtitle_2');

        $data['show_logo'] = $request->boolean('show_logo');
        $data['show_tahun_ajaran'] = $request->boolean('show_tahun_ajaran');
        $data['show_nama_pimpinan'] = $request->boolean('show_nama_pimpinan');
        $data['show_tanda_tangan'] = $request->boolean('show_tanda_tangan');
        $data['lock_enabled'] = $request->boolean('lock_enabled', true);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('setting-lembaga', 'public');
        }

        if ($request->hasFile('ttd_ketua_yayasan')) {
            $data['ttd_ketua_yayasan_path'] = $request->file('ttd_ketua_yayasan')->store('setting-lembaga', 'public');
        }

        if ($request->hasFile('ttd_ketua_babinlumni')) {
            $data['ttd_ketua_babinlumni_path'] = $request->file('ttd_ketua_babinlumni')->store('setting-lembaga', 'public');
        }

        if ($data['is_active']) {
            SettingLembaga::where('id', '!=', $setting->id)->update(['is_active' => false]);
        }

        $setting->update($data);

        return redirect()->route('admin.setting-lembaga.edit')
            ->with('success', 'Setting lembaga berhasil diperbarui.');
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
            'lock_enabled' => true,
            'show_logo' => true,
            'show_tahun_ajaran' => true,
            'show_nama_pimpinan' => true,
            'show_tanda_tangan' => true,
            'sidebar_title' => 'Website Aplikasi',
            'sidebar_subtitle_1' => 'Sistem Manajemen Kinerja Pengabdian',
            'sidebar_subtitle_2' => 'Yayasan Pondok Pesantren Al-Huda Mugomulyo',
            'sidebar_show_title' => true,
            'sidebar_show_subtitle_1' => true,
            'sidebar_show_subtitle_2' => true,
            'laporan_scoring_method' => 'weighted_kinerja_kegiatan',
            'laporan_bobot_kinerja' => 70,
            'laporan_bobot_kegiatan' => 30,
        ]);
    }
}
