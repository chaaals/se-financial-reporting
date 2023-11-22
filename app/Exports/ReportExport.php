<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ReportExport implements FromArray
{

    protected array $report_data;
    
    public function __construct(array $report_data){
        $this->report_data = $report_data;
    }
    
    public function array(): array{
        return $this->report_data;
    }
}
