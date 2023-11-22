<?php

namespace App\Livewire\FinancialReport;

// use App\Imports\FinancialReportImport;
use App\Models\FinancialReport;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
// use Maatwebsite\Excel\Facades\Excel;

class AddFinancialReport extends Component
{
    use WithFileUploads;

    #[Rule("nullable|sometimes|file|mimes:xlsx,xls")]
    public $imported_spreadsheet;
    public $spreadsheet = [];

    public function add($report_type, $tb_id)
    {
        if ($this->spreadsheet) {
            $report_name = '';
            if ($report_type === "Annual") {
                $report_name = "Annual Financial Report " . date('Y');
            } elseif ($report_type === "Quarterly") {
                $month = date('n');
                $quarter = ceil($month / 3);
                $report_name = "Q{$quarter} Financial Report " . date('Y');
            }

            FinancialReport::create([
                "report_name" => $report_name,
                "start_date" => date("Y-m-d"),
                "end_date" => date("Y-m-d"),
                "report_type" => $report_type,
                "report_status" => "Draft",
                "approved" => false,
                "tb_id" => $tb_id, 
            ]);

            $this->reset();
        }
    }

    public function previewSpreadsheet()
    {
        // TODO 
    }

    public function render()
    {
        if ($this->imported_spreadsheet) {
            $this->previewSpreadsheet();
        }

        return view('livewire.financial-report.add-financial-report');
    }
}
