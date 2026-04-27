<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPenilaianExport implements FromView
{
    private $karyawanList;
    private $kategoriList;
    private $selectedTahunData;
    private $jenisLaporan;
    private $reportFormat;

    public function __construct($karyawanList, $kategoriList, $selectedTahunData, $jenisLaporan = 'ringkas', array $reportFormat = [])
    {
        $this->karyawanList = $karyawanList;
        $this->kategoriList = $kategoriList;
        $this->selectedTahunData = $selectedTahunData;
        $this->jenisLaporan = in_array($jenisLaporan, ['ringkas', 'rinci'], true) ? $jenisLaporan : 'ringkas';
        $this->reportFormat = $reportFormat;
    }

    public function view(): View
    {
        return view('exports.laporan_penilaian', [
            'karyawanList' => $this->karyawanList,
            'kategoriList' => $this->kategoriList,
            'selectedTahunData' => $this->selectedTahunData,
            'jenisLaporan' => $this->jenisLaporan,
            'reportFormat' => $this->reportFormat,
        ]);
    }
}
