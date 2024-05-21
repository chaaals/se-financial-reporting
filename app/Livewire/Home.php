<?php

namespace App\Livewire;

use App\Models\FinancialStatement;
use App\Models\FinancialStatementCollection;
use App\Models\TrialBalance;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Home extends Component
{
    public $user;
    public $trialBalances;
    public $financialStatements;
    public $filterPeriod = 'Annual';
    public $filterQuarter;
    public $filterYear;
    public $colors = [
        '#008FFB', '#00E396', '#feb019', '#ff455f', '#775dd0', '#80effe',
        '#0077B5', '#ff6384', '#c9cbcf', '#0057ff', '#00a9f4', '#2ccdc9', '#5e72e4'
    ];
    public $filterOptions;
    public $sfpo;
    public $sfpe;
    public $scf;

    public function mount($user){
        $this->user = $user;

        $this->trialBalances = TrialBalance::orderBy('created_at', 'desc')->take(6)->get();
        $years = FinancialStatementCollection::selectRaw('YEAR(date) as year')->distinct()->orderBy('year')->pluck('year')->toArray();

        if(!$years){
            $years = [date('Y')];
            $this->filterYear = $years[0];
        }
        
        $this->filterOptions = [
            "Year" => [
                "model" => "filterYear",
                "options" => $years
            ],
            "Period" => [
                "model" => "filterPeriod",
                "options" => ["Quarterly", "Annual"]
            ],
            "Quarter" => [
                "model" => "filterQuarter",
                "options" => ["Q1", "Q2", "Q3", "Q4"]
            ]
        ];

    }

    public function parseStatement(FinancialStatement|null $fs, PieChartModel $chartModel){
        if(!$fs){
            return null;
        }
        $totals = json_decode($fs->totals_data, true);
        $i = 0;
        foreach ($totals as $label=>$value){
            $chartModel->addSlice($label, $value, $this->colors[$i]);
            $i++;
        }
        return $chartModel->asDonut();
    }

    public function fetchChart(){
        $query = FinancialStatementCollection::with('financialStatements')->whereYear('date', '=', $this->filterYear);

        if($this->filterPeriod == 'Annual' && $this->filterQuarter){
            $this->filterQuarter = null;
        }
        
        if($this->filterPeriod == 'Quarterly' && !$this->filterQuarter) {
            $this->filterQuarter = 'Q1';
            $query->where('interim_period', '=', $this->filterPeriod)->where('quarter', '=', $this->filterQuarter);
        } else if($this->filterPeriod == 'Quarterly' && $this->filterQuarter) {
            $query->where('interim_period', '=', $this->filterPeriod)->where('quarter', '=', $this->filterQuarter);
        }


        $query = $query->get();
        if($query->isEmpty()){
            // dd($query);
            $this->sfpo = null;
            $this->sfpe = null;
            $this->scf = null;
        }

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
    }
    
    public function render()
    {
        $this->fetchChart();

        $sfpoPieModel = $this->parseStatement($this->sfpo, (new PieChartModel())->setTitle('Financial Position'));
        $sfpePieModel = $this->parseStatement($this->sfpe, (new PieChartModel())->setTitle('Financial Performance'));
        $scfPieModel = $this->parseStatement($this->scf, (new PieChartModel())->setTitle('Cash Flows'));


        return view('livewire.home',[
            'sfpoPieModel' => $sfpoPieModel,
            'sfpePieModel' => $sfpePieModel,
            'scfPieModel' => $scfPieModel,
        ]);
    }
}
