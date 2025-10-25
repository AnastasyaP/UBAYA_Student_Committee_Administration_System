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
            ['name' => 'Universitas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Teknik', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Bisnis & Ekonomi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Farmasi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Psikologi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Hukum', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fakultas Kedokteran', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prodi Teknik Informatika', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prodi Teknik Kimia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KSM Informatika', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KSM Mesin & Manufaktur', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KMM Sport Teknik', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DPM Teknik', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DPM Farmasi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DPM Teknobiologi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MPM', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BEMUS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BEM Teknik', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BEM Farmasi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BEM Teknobiologi', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === tCommitteeOrganizers ===
        DB::table('tCommitteeOrganizers')->insert([
            [
                'idCommittees' => 1, 
                'idOrganizerUnits' => 8,  
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2, 
                'idOrganizerUnits' => 8,  
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 3, 
                'idOrganizerUnits' => 9,  
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
