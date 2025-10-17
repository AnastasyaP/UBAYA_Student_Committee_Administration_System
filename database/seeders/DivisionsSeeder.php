<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // === tDivisions ===
        DB::table('tDivisions')->insert([
            ['name' => 'Acara', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dokumentasi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Perlengkapan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Konsumsi', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === tListDivisions ===
        DB::table('tListDivisions')->insert([
            [
                'idCommittees' => 1, 
                'idDivisions' => 1,  
                'is_open' => 1,
                'description' => 'Mengatur seluruh kegiatan acara ILPC 2022.',
                'picture' => 'acara_ilpc2022.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 2,
                'is_open' => 0,
                'description' => 'Mendokumentasikan dan menyebarluaskan kegiatan ILPC 2022.',
                'picture' => 'dokumentasi_ilpc2022.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2, 
                'idDivisions' => 3,  
                'is_open' => 1,
                'description' => 'Menyiapkan perlengkapan untuk acara ILPC 2023.',
                'picture' => 'perlengkapan_ilpc2023.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 4,
                'is_open' => 1,
                'description' => 'Menangani konsumsi panitia dan tamu.',
                'picture' => 'konsumsi_ilpc2023.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
