<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizerUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // === tOrganizerUnits ===
        DB::table('tOrganizerUnits')->insert([
            ['name' => 'Universitas', 'level' => 'universitas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Teknik', 'level' => 'fakultas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Bisnis & Ekonomi', 'level' => 'fakultas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Farmasi', 'level' => 'fakultas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Psikologi', 'level' => 'fakultas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Hukum', 'level' => 'fakultas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Kedokteran', 'level' => 'fakultas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prodi Teknik Informatika', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prodi Teknik Kimia', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KSM Informatika', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KSM Mesin & Manufaktur', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KMM Sport Teknik', 'level' => 'fakultas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DPM Teknik', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DPM Farmasi', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DPM Teknobiologi', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MPM', 'level' => 'universitas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BEMUS', 'level' => 'universitas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BEM Teknik', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BEM Farmasi', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BEM Teknobiologi', 'level' => 'program studi', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
