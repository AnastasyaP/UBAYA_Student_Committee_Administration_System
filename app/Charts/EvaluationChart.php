<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class EvaluationChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($labels, $scores): \ArielMejiaDev\LarapexCharts\BarChart
    {
        return $this->chart->barChart()
            ->addData($scores, 'Score')
            ->setXAxis($labels);
    }
}
