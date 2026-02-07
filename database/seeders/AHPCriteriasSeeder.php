<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AHPCriteriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tAHPCriterias')->insert([
            ['name' => 'Komunikasi', 'idDivisions' => '3', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Leadership', 'idDivisions' => '1','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Komitmen', 'idDivisions' => '4','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kreativitas', 'idDivisions' => '2','created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
