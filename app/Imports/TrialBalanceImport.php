<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TrialBalanceImport implements WithCalculatedFormulas //, WithEvents
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }

    // public function registerEvents(): array {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $sheet = $event->sheet;
    //             $sheetDelegate = $sheet->getDelegate();
    //             $sheetDelegate->setCellValue();
    //         }
    //     ];
    // }
}
