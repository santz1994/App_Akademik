<?php

namespace App\Http\Controllers;

use App\Models\SettingLembaga;
use Illuminate\Http\Request;

class LaporanFormatController extends Controller
{
    private const AVAILABLE_COLUMNS = [
        'no',
        'kode_karyawan',
        'nama_karyawan',
        'pangkalan',
        'detail_kompetensi',
        'nilai_akhir',
        'rating',
    ];

    public function edit()
    {
        $setting = $this->resolveSetting();

        return view('admin.laporan_format.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'laporan_default_jenis' => 'required|in:ringkas,rinci',
            'laporan_show_no' => 'nullable|boolean',
            'laporan_show_kode_karyawan' => 'nullable|boolean',
            'laporan_show_pangkalan' => 'nullable|boolean',
            'laporan_show_nilai_akhir' => 'nullable|boolean',
            'laporan_show_rating' => 'nullable|boolean',
            'laporan_show_detail_kompetensi' => 'nullable|boolean',
            'laporan_show_bobot_kategori' => 'nullable|boolean',
            'laporan_paper_size' => 'required|in:a4,letter,legal',
            'laporan_orientation' => 'required|in:portrait,landscape',
            'laporan_margin_top' => 'required|numeric|min:0.5|max:5',
            'laporan_margin_right' => 'required|numeric|min:0.5|max:5',
            'laporan_margin_bottom' => 'required|numeric|min:0.5|max:5',
            'laporan_margin_left' => 'required|numeric|min:0.5|max:5',
            'laporan_text_align' => 'required|in:left,center,right,justify',
            'laporan_header_align' => 'required|in:left,center,right',
            'laporan_cell_padding' => 'required|integer|min:2|max:16',
            'laporan_border_width' => 'required|numeric|min:0.2|max:3',
            'laporan_font_size' => 'required|integer|min:9|max:14',
            'laporan_title_font_size' => 'required|integer|min:12|max:24',
            'laporan_col_width_no' => 'required|integer|min:24|max:80',
            'laporan_col_width_kode' => 'required|integer|min:50|max:160',
            'laporan_col_width_nama' => 'required|integer|min:120|max:320',
            'laporan_col_width_pangkalan' => 'required|integer|min:90|max:280',
            'laporan_col_width_nilai' => 'required|integer|min:70|max:160',
            'laporan_col_width_rating' => 'required|integer|min:90|max:220',
            'laporan_column_order' => 'required|string',
            'laporan_label_no' => 'required|string|max:100',
            'laporan_label_kode_karyawan' => 'required|string|max:100',
            'laporan_label_nama_karyawan' => 'required|string|max:100',
            'laporan_label_pangkalan' => 'required|string|max:100',
            'laporan_label_detail_kompetensi' => 'required|string|max:100',
            'laporan_label_nilai_akhir' => 'required|string|max:100',
            'laporan_label_rating' => 'required|string|max:100',
            'laporan_scoring_method' => 'required|in:weighted_kategori,average_kinerja_kegiatan',
        ]);

        $setting = $this->resolveSetting();

        $data = [
            'laporan_default_jenis' => $request->input('laporan_default_jenis', 'ringkas'),
            // Unchecked checkboxes are not submitted; default must be false, not true.
            'laporan_show_no' => $request->boolean('laporan_show_no'),
            'laporan_show_kode_karyawan' => $request->boolean('laporan_show_kode_karyawan'),
            'laporan_show_pangkalan' => $request->boolean('laporan_show_pangkalan'),
            'laporan_show_nilai_akhir' => $request->boolean('laporan_show_nilai_akhir'),
            'laporan_show_rating' => $request->boolean('laporan_show_rating'),
            'laporan_show_detail_kompetensi' => $request->boolean('laporan_show_detail_kompetensi'),
            'laporan_show_bobot_kategori' => $request->boolean('laporan_show_bobot_kategori'),
            'laporan_paper_size' => $request->input('laporan_paper_size', 'a4'),
            'laporan_orientation' => $request->input('laporan_orientation', 'portrait'),
            'laporan_margin_top' => (float) $request->input('laporan_margin_top', 2.54),
            'laporan_margin_right' => (float) $request->input('laporan_margin_right', 2.54),
            'laporan_margin_bottom' => (float) $request->input('laporan_margin_bottom', 2.54),
            'laporan_margin_left' => (float) $request->input('laporan_margin_left', 2.54),
            'laporan_text_align' => $request->input('laporan_text_align', 'left'),
            'laporan_header_align' => $request->input('laporan_header_align', 'center'),
            'laporan_cell_padding' => (int) $request->input('laporan_cell_padding', 6),
            'laporan_border_width' => (float) $request->input('laporan_border_width', 1),
            'laporan_font_size' => (int) $request->input('laporan_font_size', 11),
            'laporan_title_font_size' => (int) $request->input('laporan_title_font_size', 16),
            'laporan_col_width_no' => (int) $request->input('laporan_col_width_no', 32),
            'laporan_col_width_kode' => (int) $request->input('laporan_col_width_kode', 72),
            'laporan_col_width_nama' => (int) $request->input('laporan_col_width_nama', 190),
            'laporan_col_width_pangkalan' => (int) $request->input('laporan_col_width_pangkalan', 140),
            'laporan_col_width_nilai' => (int) $request->input('laporan_col_width_nilai', 88),
            'laporan_col_width_rating' => (int) $request->input('laporan_col_width_rating', 108),
            'laporan_label_no' => trim((string) $request->input('laporan_label_no', 'No')),
            'laporan_label_kode_karyawan' => trim((string) $request->input('laporan_label_kode_karyawan', 'Kode Karyawan')),
            'laporan_label_nama_karyawan' => trim((string) $request->input('laporan_label_nama_karyawan', 'Nama Karyawan')),
            'laporan_label_pangkalan' => trim((string) $request->input('laporan_label_pangkalan', 'Pangkalan')),
            'laporan_label_detail_kompetensi' => trim((string) $request->input('laporan_label_detail_kompetensi', 'Detail Kompetensi')),
            'laporan_label_nilai_akhir' => trim((string) $request->input('laporan_label_nilai_akhir', 'Nilai Akhir')),
            'laporan_label_rating' => trim((string) $request->input('laporan_label_rating', 'Rating')),
            'laporan_scoring_method' => $request->input('laporan_scoring_method', 'weighted_kategori'),
        ];

        $requestedOrder = json_decode((string) $request->input('laporan_column_order', '[]'), true);
        if (!is_array($requestedOrder)) {
            $requestedOrder = [];
        }

        $normalizedOrder = [];
        foreach ($requestedOrder as $columnKey) {
            $columnKey = (string) $columnKey;
            if (in_array($columnKey, self::AVAILABLE_COLUMNS, true) && !in_array($columnKey, $normalizedOrder, true)) {
                $normalizedOrder[] = $columnKey;
            }
        }
        foreach (self::AVAILABLE_COLUMNS as $fallbackColumn) {
            if (!in_array($fallbackColumn, $normalizedOrder, true)) {
                $normalizedOrder[] = $fallbackColumn;
            }
        }
        $data['laporan_column_order'] = json_encode($normalizedOrder);

        if (!$data['laporan_show_nilai_akhir']) {
            $data['laporan_show_rating'] = false;
        }

        if (!$data['laporan_show_detail_kompetensi'] && $data['laporan_default_jenis'] === 'rinci') {
            $data['laporan_default_jenis'] = 'ringkas';
        }

        $setting->update($data);

        return redirect()->route('admin.laporan.format.edit')
            ->with('success', 'Format laporan berhasil diperbarui.');
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
            'laporan_default_jenis' => 'ringkas',
            'laporan_show_no' => true,
            'laporan_show_kode_karyawan' => true,
            'laporan_show_pangkalan' => true,
            'laporan_show_nilai_akhir' => true,
            'laporan_show_rating' => true,
            'laporan_show_detail_kompetensi' => true,
            'laporan_show_bobot_kategori' => true,
            'laporan_paper_size' => 'a4',
            'laporan_orientation' => 'portrait',
            'laporan_margin_top' => 2.54,
            'laporan_margin_right' => 2.54,
            'laporan_margin_bottom' => 2.54,
            'laporan_margin_left' => 2.54,
            'laporan_text_align' => 'left',
            'laporan_header_align' => 'center',
            'laporan_cell_padding' => 6,
            'laporan_border_width' => 1.0,
            'laporan_font_size' => 11,
            'laporan_title_font_size' => 16,
            'laporan_col_width_no' => 32,
            'laporan_col_width_kode' => 72,
            'laporan_col_width_nama' => 190,
            'laporan_col_width_pangkalan' => 140,
            'laporan_col_width_nilai' => 88,
            'laporan_col_width_rating' => 108,
            'laporan_column_order' => json_encode(self::AVAILABLE_COLUMNS),
            'laporan_label_no' => 'No',
            'laporan_label_kode_karyawan' => 'Kode Karyawan',
            'laporan_label_nama_karyawan' => 'Nama Karyawan',
            'laporan_label_pangkalan' => 'Pangkalan',
            'laporan_label_detail_kompetensi' => 'Detail Kompetensi',
            'laporan_label_nilai_akhir' => 'Nilai Akhir',
            'laporan_label_rating' => 'Rating',
            'laporan_scoring_method' => 'weighted_kategori',
        ]);
    }
}
