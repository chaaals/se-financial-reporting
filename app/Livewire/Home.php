<?php

namespace App\Livewire;

use App\Charts\FinancialStatementCharts;
use App\Charts\FinancialStatementLine;
use App\Charts\FinancialStatementPie;
use App\Models\FinancialStatementCollection;
use App\Models\TrialBalance;
use Livewire\Component;

class Home extends Component
{
    public $user;
    public $trialBalances;
    public $financialStatements;
    public $filterPeriod = 'Annual';
    public $filterQuarter;


    public $filterOptions = [
        "Period" => [
            "model" => "filterPeriod",
            "options" => ["Quarterly", "Annual"]
        ],
        "Quarter" => [
            "model" => "filterQuarter",
            "options" => ["Q1", "Q2", "Q3", "Q4"]
        ]
    ];
    public $sfpo;
    public $sfpe;
    public $scf;

    public function mount($user){
        $this->user = $user;

        $this->trialBalances = TrialBalance::orderBy('created_at', 'desc')->take(6)->get();

        // $this->financialStatements = FinancialStatementCollection::orderBy('created_at', 'desc')->take(6)->get();
    }
    
    public function render()
    {
        $query = FinancialStatementCollection::with('financialStatements')->get();

        foreach ($query as $fsc){
            foreach ($fsc->financialStatements as $financialStatement) {
                if($financialStatement->fs_type == 'SFPO'){
                    $this->sfpo = $financialStatement;
                };
                if($financialStatement->fs_type == 'SFPE'){
                    $this->sfpe = $financialStatement;
                };
                if($financialStatement->fs_type == 'SCF'){
                    $this->scf = $financialStatement;
                };
            }
        }
        
        $sfpoPie = new FinancialStatementPie('Financial Position',$this->sfpo);
        $sfpePie = new FinancialStatementPie('Financial Performance',$this->sfpe);
        $scfPie = new FinancialStatementPie('Cash Flows',$this->scf);

        dd($sfpoPie->build());

        return view('livewire.home', ['sfpoPie' => $sfpoPie->build(), 'sfpePie' => $sfpePie->build(), 'scfPie' => $scfPie->build()]);
    }
}
