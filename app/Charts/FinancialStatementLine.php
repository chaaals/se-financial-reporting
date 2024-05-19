<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class FinancialStatementLine
{
    protected $chart;
    public $title;

    public function __construct(string $chartTitle)
    {
        $this->title = $chartTitle;
        $this->chart = new LarapexChart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        return $this->chart->lineChart()
            ->setTitle($this->title)
            ->setSubtitle('Physical sales vs Digital sales.')
            ->addData('Physical sales', [40, 93, 35, 42, 18, 82])
            ->addData('Digital sales', [70, 29, 77, 28, 55, 45])
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);
    }
}
