<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvaluationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tEvaluationCriterias')->insert([
            [
                'idEvaluationCriterias' => 1,
                'name' => 'Kedisiplinan',
                'description' => 'Ketepatan waktu dan konsistensi kerja',
                'target_type' => 'user'
            ],
            [
                'idEvaluationCriterias' => 2,
                'name' => 'Tanggung Jawab',
                'description' => 'Keseriusan dalam menyelesaikan tugas',
                'target_type' => 'user'
            ],
            [
                'idEvaluationCriterias' => 3,
                'name' => 'Kerja Sama Tim',
                'description' => 'Kemampuan bekerja dalam tim',
                'target_type' => 'user'
            ],
            [
                'idEvaluationCriterias' => 4,
                'name' => 'Komunikasi',
                'description' => 'Kemampuan menyampaikan ide',
                'target_type' => 'user'
            ],
            [
                'idEvaluationCriterias' => 5,
                'name' => 'Kepemimpinan',
                'description' => 'Kemampuan memimpin tim',
                'target_type' => 'user'
            ],
            [
                'idEvaluationCriterias' => 6,
                'name' => 'Kejelasan Tugas',
                'description' => 'Kejelasan pembagian tugas dalam divisi',
                'target_type' => 'division'
            ],
            [
                'idEvaluationCriterias' => 7,
                'name' => 'Koordinasi Divisi',
                'description' => 'Koordinasi antar anggota divisi',
                'target_type' => 'division'
            ],
            [
                'idEvaluationCriterias' => 8,
                'name' => 'Beban Kerja',
                'description' => 'Kesesuaian workload',
                'target_type' => 'division'
            ],
            [
                'idEvaluationCriterias' => 9,
                'name' => 'Kualitas Acara',
                'description' => 'Seberapa baik acara dijalankan secara keseluruhan',
                'target_type' => 'committee'
            ],
            [
                'idEvaluationCriterias' => 10,
                'name' => 'Manajemen Waktu',
                'description' => 'Ketepatan waktu pelaksanaan acara',
                'target_type' => 'committee'
            ],
            [
                'idEvaluationCriterias' => 11,
                'name' => 'Koordinasi Panitia',
                'description' => 'Koordinasi antar divisi dalam kepanitiaan',
                'target_type' => 'committee'
            ],
            [
                'idEvaluationCriterias' => 12,
                'name' => 'Kepuasan Peserta',
                'description' => 'Tingkat kepuasan peserta terhadap acara',
                'target_type' => 'committee'
            ],
        ]);

        $committeeId = 2;

        DB::table('tEvaluationCriteriaScopes')->insert([

            // ======================
            // 🔹 GENERAL (SEMUA DIVISI)
            // ======================
            [
                'idEvaluationCriterias' => 1,
                'idCommittees' => $committeeId,
                'idDivisions' => null
            ],
            [
                'idEvaluationCriterias' => 2,
                'idCommittees' => $committeeId,
                'idDivisions' => null
            ],
            [
                'idEvaluationCriterias' => 3,
                'idCommittees' => $committeeId,
                'idDivisions' => null
            ],
            [
                'idEvaluationCriterias' => 4,
                'idCommittees' => $committeeId,
                'idDivisions' => null
            ],
            [
                'idEvaluationCriterias' => 9,
                'idCommittees' => $committeeId,
                'idDivisions' => null
            ],
            [
                'idEvaluationCriterias' => 10,
                'idCommittees' => $committeeId,
                'idDivisions' => null
            ],
            [
                'idEvaluationCriterias' => 11,
                'idCommittees' => $committeeId,
                'idDivisions' => null
            ],


            // ======================
            // 🔸 DIVISION: KONSUMSI (3)
            // ======================
            [
                'idEvaluationCriterias' => 7,
                'idCommittees' => $committeeId,
                'idDivisions' => 4
            ],
            [
                'idEvaluationCriterias' => 8,
                'idCommittees' => $committeeId,
                'idDivisions' => 4
            ],

            // ======================
            // 🔸 DIVISION: PERLENGKAPAN (4)
            // ======================
            [
                'idEvaluationCriterias' => 6,
                'idCommittees' => $committeeId,
                'idDivisions' => 3
            ],
            [
                'idEvaluationCriterias' => 7,
                'idCommittees' => $committeeId,
                'idDivisions' => 3
            ],
            [
                'idEvaluationCriterias' => 8,
                'idCommittees' => $committeeId,
                'idDivisions' => 3
            ],
        ]);
    }
}
