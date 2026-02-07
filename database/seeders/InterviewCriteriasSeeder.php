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
            ['question' => 'Bagaimana cara kamu menyampaikan ide ke tim?', 'max_score' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Ceritakan pengalaman memimpin tim', 'max_score' => 100,'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Apa komitmen kamu jika diterima?', 'max_score' => 100,'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Coba buatkan tema acara untuk kepanitiaan ini', 'max_score' => 100,'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
