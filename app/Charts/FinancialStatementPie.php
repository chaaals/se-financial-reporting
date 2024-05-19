<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class FinancialStatementPie
{
    protected $chart;
    public $title;
    public $data;
    public $colors = [
        '#008FFB', '#00E396', '#feb019', '#ff455f', '#775dd0', '#80effe',
        '#0077B5', '#ff6384', '#c9cbcf', '#0057ff', '#00a9f4', '#2ccdc9', '#5e72e4'
    ];

    public function __construct(string $chartTitle, $data)
    {
        $this->title = $chartTitle;
        $this->data = $data;
        $this->chart = new LarapexChart;
    }
    public function parseData(){
        $totals = json_decode($this->data->totals_data, true);
        $data = [];
        $labels = [];
        
        foreach ($totals as $label=>$value){
            array_push($labels, $label);
            array_push($data, $value);
        }
        return [$data, $labels];
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        [$data, $labels] = $this->parseData();
        // dd($data, $labels);
        return $this->chart->donutChart()
            ->setTitle($this->title)
            ->setSubtitle('Breakdown of totals')->setLabels($labels)
            ->setDataset($data)
            ->setLabels($labels);
    }
}
