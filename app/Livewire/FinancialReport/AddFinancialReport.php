<?php

namespace App\Livewire\FinancialReport;

use App\Models\FinancialReport;
use App\Models\TrialBalance;
use Livewire\Component;

class AddFinancialReport extends Component
{
    public $report_name;
    public $fiscal_year;
    public $interim_period;
    public $quarter;
    public $report_status;
    public $tb_id;
    public $trial_balances = [];
    protected $rules = [
        'report_name' => 'nullable|max:255',
        'fiscal_year' => 'required',
        'interim_period' => 'required|in:Quarterly,Annual',
        'report_status' => 'required|in:Draft,For Approval',
        'quarter' => 'nullable',
        'tb_id' => 'required',
    ];
    public $years;

    public function mount()
    {
        $this->trial_balances = TrialBalance::all();
        
        // default values so user does not need to interact with the form and just save
        $this->years = range(date('Y'), date('Y') - 50);
        $this->fiscal_year = $this->years[0];
        $this->interim_period = "Quarterly";
        $this->report_status = "Draft";
        if ($this->trial_balances->isNotEmpty()) {
            $this->tb_id = $this->trial_balances->first()->tb_id;
        }
    }

    public function add()
    {
        if ($this->report_name === null) {
            if ($this->interim_period === "Annual") {
                $this->report_name = "Annual Financial Report " . date('Y');
            } elseif ($this->interim_period === "Quarterly") {
                $month = date('n');
                $quarter = ceil($month / 3);
                $this->report_name = "Q{$quarter} Financial Report " . date('Y');
            } else {
                $this->report_name = "Financial Report " . date('Y-m');
            }
        }

        if ($this->interim_period === 'Quarterly') {
            $this->rules['quarter'] = 'required|in:Q1,Q2,Q3,Q4';
        } else {
            $this->quarter = null;
        }
        
        $this->validate();
        FinancialReport::create([
            "report_name" => $this->report_name,
            "fiscal_year" => $this->fiscal_year,
            "interim_period" => $this->interim_period,
            "quarter" => $this->quarter,
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
