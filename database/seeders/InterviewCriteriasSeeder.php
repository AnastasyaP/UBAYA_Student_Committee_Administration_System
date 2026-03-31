<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterviewCriteriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tInterviewCriterias')->insert([
            ['name' => 'Pengalaman P3K','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pengetahuan Obat','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Komitmen Waktu','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Problem Solving','created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('tListDivisionAHPCriterias')->insert([
            ['idDivisions' => 4,'idCommittees' =>2, 'idAHPCriterias'=> 5, 'average_weight' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['idDivisions' => 4,'idCommittees' =>2, 'idAHPCriterias'=> 1, 'average_weight' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['idDivisions' => 3,'idCommittees' =>2, 'idAHPCriterias'=> 3, 'average_weight' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['idDivisions' => 3,'idCommittees' =>2, 'idAHPCriterias'=> 4, 'average_weight' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('tInterviewDivisionAHPCriterias')->insert([
            ['idInterviewCriterias' => 1,'idListDivisionAHPCriterias' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewCriterias' => 2,'idListDivisionAHPCriterias' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewCriterias' => 3,'idListDivisionAHPCriterias' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewCriterias' => 4,'idListDivisionAHPCriterias' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('tInterviewQuestions')->insert([
            ['question' => 'Cara menangani mimisan', 'max_score'=> '100', 'idInterviewCriterias' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Fungsi obat', 'max_score'=> '100', 'idInterviewCriterias' => 2,'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Apakah kamu bersedia datang awal pada saat hari H?','max_score'=> '100', 'idInterviewCriterias' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
