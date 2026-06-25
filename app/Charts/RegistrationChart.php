<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class RegistrationChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(array $data): \ArielMejiaDev\LarapexCharts\LineChart
    {
        return $this->chart->lineChart()
            ->setTitle('Tren Pendaftaran Kepanitiaan')
            ->setSubtitle('Jumlah pendaftaran kepanitiaan per bulan pada tahun ' . date('Y'))
            ->addData($data, 'Jumlah Pendaftar')
            ->setXAxis([
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ]);
    }
}
