<?php

namespace App\Livewire\FinancialReport;

use App\Models\FinancialReport;
use App\Models\TrialBalance;
use Livewire\Component;

class AddFinancialReport extends Component
{
    public $report_name;
    public $report_type;
    public $report_status;
    public $tb_id;
    public $trial_balances = [];
    protected $rules = [
        'report_name' => 'nullable|max:255',
        'report_type' => 'required|in:Quarterly,Annual',
        'report_status' => 'required|in:Draft,For Approval',
        'tb_id' => 'required',
    ];

    public function mount()
    {
        $this->trial_balances = TrialBalance::all();

        // default values so user does not need to interact with the form and just save
        $this->report_type = "Quarterly";
        $this->report_status = "Draft";
        if ($this->trial_balances->isNotEmpty()) {
            $this->tb_id = $this->trial_balances->first()->tb_id;
        }
    }

    public function add()
    {
        if ($this->report_name === null) {
            if ($this->report_type === "Annual") {
                $this->report_name = "Annual Financial Report " . date('Y');
            } elseif ($this->report_type === "Quarterly") {
                $month = date('n');
                $quarter = ceil($month / 3);
                $this->report_name = "Q{$quarter} Financial Report " . date('Y');
            } else {
                $this->report_name = "Financial Report " . date('Y-m');
            }
        }
        
        $this->validate();
        FinancialReport::create([
            "report_name" => $this->report_name,
            "start_date" => date("Y-m-d"),
            "end_date" => date("Y-m-d"),
            "report_type" => $this->report_type,
            "report_status" => $this->report_status,
            "approved" => false,
            "tb_id" => $this->tb_id, 
        ]);

        $this->redirect('/financial-reports');
    }

    public function previewSpreadsheet()
    {
        // TODO 
    }

    public function render()
    {
        return view('livewire.financial-report.add-financial-report');
    }
}
