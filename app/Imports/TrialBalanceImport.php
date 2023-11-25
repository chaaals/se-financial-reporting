<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TrialBalanceImport implements WithCalculatedFormulas, WithChunkReading, ToArray
{
    use Importable;
    
    public function array(array $rows): array
    {
        return $rows;
    }

    public function chunkSize(): int {
        return 1000;
    }
}
