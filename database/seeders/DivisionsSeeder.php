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
                'picture' => 'img/division/31fc1abb-7a74-4226-b8a7-f98576776d6c.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 2,
                'is_open' => 0,
                'description' => 'Mendokumentasikan dan menyebarluaskan kegiatan ILPC 2022.',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2, 
                'idDivisions' => 3,  
                'is_open' => 1,
                'description' => 'Menyiapkan perlengkapan untuk acara ILPC 2023.',
                'picture' => 'img/division/e0cf607a-838f-476a-b817-9a824289c109.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 4,
                'is_open' => 1,
                'description' => 'Menangani konsumsi panitia dan tamu.',
                'picture' => 'img/division/44d23c90-0f16-432f-8689-4ea416331be6.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
