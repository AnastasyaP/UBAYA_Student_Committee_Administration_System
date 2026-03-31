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
            ['name' => 'Pengetahuan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inisiatif','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Komitmen','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kreativitas','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pengalaman','created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
