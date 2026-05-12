<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AHPService
{
    public function calculate($criterias, $pairwise){
        $n = count($criterias);

        $matrix = [];
        $columnSum = [];
        $normalized = [];
        $priority = [];
        $weightedSum = [];
        $lambdaVector = [];
        $lambdaMax = null;
        $CI = null;
        $CR = null;
        $RI = null;
        $isConsistent = null;

        // matrix identitas
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $matrix[$i][$j] = ($i == $j) ? 1 : 0;
            }
        }

        foreach ($pairwise as $p) {
            $i = $criterias->search(fn($c) => $c->idAHPCriterias == $p->idCriteria1);
            $j = $criterias->search(fn($c) => $c->idAHPCriterias == $p->idCriteria2);

            if ($i !== false && $j !== false) {
                $matrix[$i][$j] = $p->weight;
                $matrix[$j][$i] = 1 / $p->weight;
            }
        }

        // hitung jumlah kolom
        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $matrix[$i][$j];
            }
            $columnSum[$j] = $sum;
        }

        // Normalisasi
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalized[$i][$j] = $matrix[$i][$j] / $columnSum[$j];
            }
        }

        // priority Vector
        for ($i = 0; $i < $n; $i++) {
            $priority[$i] = array_sum($normalized[$i]) / $n;
        }

        // weighted Sum / Konsistensi per kriteria
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;

            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $priority[$j];
            }

            $weightedSum[$i] = $sum;
        }

        // lambda vector
        for ($i = 0; $i < $n; $i++) {
            $lambdaVector[$i] = $weightedSum[$i] / $priority[$i];
        }

        $lambdaMax = array_sum($lambdaVector) / $n;

        $CI = ($lambdaMax - $n) / ($n - 1);

        $RI_table = [
            1 => 0.00,
            2 => 0.00,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49
        ];

        $RI = $RI_table[$n] ?? 1.49;

        $CR = ($RI == 0) ? 0 : $CI / $RI;

        $isConsistent = $CR < 0.1;

        return [
            'matrix'=>$matrix,
            'column_sum'=>$columnSum,
            'normalized'=>$normalized,
            'priority_vector'=>$priority,
            'weighted_sum'=>$weightedSum,
            'lambda_vector'=>$lambdaVector,
            'lambda_max'=>$lambdaMax,
            'CI'=>$CI,
            'CR'=>$CR,
            'RI'=>$RI,
            'is_consistent'=>$isConsistent
        ];
    }
}