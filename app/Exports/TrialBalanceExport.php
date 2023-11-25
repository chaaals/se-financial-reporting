<?php

namespace App\Exports;

use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TrialBalanceExport implements FromArray, ShouldAutoSize, WithEvents
{

    protected array $report_data;
    
    public function __construct(array $report_data){
        $this->report_data = $report_data;
    }
    
    public function array(): array {
        return $this->report_data;
    }

    public function registerEvents(): array {
        return [
            BeforeSheet::class => function (BeforeSheet $event){
                $sheet = $event->sheet->getDelegate();

                foreach(range('A', 'I') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setWidth(16);
                }
            },
            AfterSheet::class => function (AfterSheet $event) {
                $tb_config = Config::get("trialbalance");
                $startRow = $tb_config["startRow"];
                $sheet = $event->sheet;
                extract($tb_config["data"]);

                foreach($tb_config["headers"] as $header){
                    extract($header);
                    $data = $sheet->getCell($cell)->getValue();
                    $sheet->mergeCells($mergeRange);
                    $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue($cell, $data);
                }

                for($i = $startRow; $i <= $sheet->getHighestRow(); $i++){
                    // cells
                    $accTitleCell = $accountTitles["cell"]($i);
                    $debitCell = $debit["cell"]($i);
                    $creditCell = $credit["cell"]($i);

                    // values
                    $accTitleData = $sheet->getCell($accTitleCell)->getValue();
                    $debitData = $sheet->getCell($debitCell)->getValue();
                    $creditData = $sheet->getCell($creditCell)->getValue();

                    // merge cells
                    $sheet->mergeCells($accountTitles["mergeRange"]($i));
                    $sheet->mergeCells($debit["mergeRange"]($i));
                    $sheet->mergeCells($credit["mergeRange"]($i));
                    
                    // re-set values
                    $sheet->setCellValue($accTitleCell, $accTitleData);
                    $sheet->setCellValue($debitCell, $debitData);
                    $sheet->setCellValue($creditCell, $creditData);
                }
            }
        ];
    }
}
