<?php

namespace App\Livewire\TrialBalance;

use App\Exports\ReportExport;
use App\Models\TrialBalance;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class PreviewTrialBalance extends Component
{
    public TrialBalance $trial_balance;
    public $confirming = null;

    public function mount(){
        $tb_id = Route::current()->parameter("tb_id");
        $query = TrialBalance::where('tb_id', $tb_id)->get();

        foreach($query as $tb){
            $this->trial_balance= $tb;
        }
    }

    public function export() {
        $export = new ReportExport(json_decode($this->trial_balance->tb_data));

        return Excel::download($export, 'TB_REPORT.xlsx');
    }

    public function confirmDelete($tbId)
    {
        $this->confirming = $tbId;
    }

    public function deleteTrialBalance($tbId)
    {
        // delete by ID
        TrialBalance::find($tbId)->delete();
        $this->reset('confirming');
        $this->redirect("/trial-balances");
    }

    public function render()
    {
        return view('livewire.trial-balance.preview-trial-balance');
    }
}
