<?php

namespace App\Http\Controllers;

use App\Exports\ImportTemplateExport;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Maatwebsite\Excel\Facades\Excel;

class HelpController extends Controller
{
    public function index()
    {
        return view('help.index');
    }

    public function downloadImportTemplate(string $entity, string $format)
    {
        $entity = strtolower($entity);
        $format = strtolower($format);

        if (!in_array($entity, ['user', 'karyawan'], true) || !in_array($format, ['csv', 'xlsx'], true)) {
            abort(404);
        }

        $rows = $this->getTemplateRows($entity);
        $filename = "template-import-{$entity}.{$format}";

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($rows) {
                $handle = fopen('php://output', 'w');

                foreach ($rows as $row) {
                    fputcsv($handle, $row);
                }

                fclose($handle);
            }, $filename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        return Excel::download(new ImportTemplateExport($rows), $filename, ExcelWriter::XLSX);
    }

    private function getTemplateRows(string $entity): array
    {
        if ($entity === 'user') {
            return [
                ['name', 'username', 'email', 'password', 'role', 'kode_pangkalan', 'is_kepala'],
                ['Ahmad Fauzi', 'ahmad', 'ahmad@mail.com', 'rahasia123', 'user', 'PNG-001', '1'],
                ['Rina Admin', 'rinaadmin', 'rina.admin@mail.com', '', 'admin', '', '0'],
            ];
        }

        return [
            ['kode_karyawan', 'nama_karyawan', 'kode_pangkalan', 'tugas_khusus', 'alamat', 'is_active', 'username', 'tahun_penilaian'],
            ['KRY-0101', 'Siti Aminah', 'PNG-002', 'TU MA', 'Jl. Melati 10', 'aktif', 'siti', '2025/2026'],
            ['', 'Budi Santoso', 'PNG-001', 'Operator', 'Jl. Mawar 3', '1', 'budi', ''],
        ];
    }
}
