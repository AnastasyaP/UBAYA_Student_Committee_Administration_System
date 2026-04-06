<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterviewEvaluationScoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('tInterviewEvaluations')->insert([
            ['idEvaluator' => 11,'idRegistrations' =>1, 'comment'=> 'cc', 'created_at' => now(), 'updated_at' => now()],
            ['idEvaluator' => 11,'idRegistrations' =>2, 'comment'=> 'ee', 'created_at' => now(), 'updated_at' => now()],
        ]);

         DB::table('tInterviewEvaluationScores')->insert([
            ['idInterviewEvaluations' => 1,'idInterviewCriterias' =>6, 'score'=> 5, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewEvaluations' => 1,'idInterviewCriterias' =>4, 'score'=> 4, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewEvaluations' => 1,'idInterviewCriterias' =>3, 'score'=> 3, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewEvaluations' => 1,'idInterviewCriterias' =>5, 'score'=> 7, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewEvaluations' => 2,'idInterviewCriterias' =>6, 'score'=> 6, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewEvaluations' => 2,'idInterviewCriterias' =>4, 'score'=> 2, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewEvaluations' => 2,'idInterviewCriterias' =>3, 'score'=> 4, 'created_at' => now(), 'updated_at' => now()],
            ['idInterviewEvaluations' => 2,'idInterviewCriterias' =>5, 'score'=> 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
