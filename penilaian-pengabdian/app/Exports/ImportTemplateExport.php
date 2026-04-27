<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ImportTemplateExport implements FromArray
{
    private array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }
}
