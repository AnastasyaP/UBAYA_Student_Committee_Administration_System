<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tUsers')->insert([
             // Fakultas Teknik
            [
                'email' => 's160422001@student.ubaya.ac.id',
                'nrp' => '160422001',
                'password' => Hash::make('ansos123'),
                'firstname' => 'Andi',
                'lastname' => 'Santoso',
                'cv' => 'cv_andi.pdf',
                'portofolio' => 'portofolio_andi.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's160122002@student.ubaya.ac.id',
                'nrp' => '160122002',
                'password' => Hash::make('budwi123'),
                'firstname' => 'Budi',
                'lastname' => 'Wijaya',
                'cv' => 'cv_budi.pdf',
                'portofolio' => 'portofolio_budi.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Bisnis & Ekonomi - Manajemen
            [
                'email' => 's130122001@student.ubaya.ac.id',
                'nrp' => '130122001',
                'password' => Hash::make('ciles123'),
                'firstname' => 'Citra',
                'lastname' => 'Lestari',
                'cv' => 'cv_citra.pdf',
                'portofolio' => 'portofolio_citra.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's130122002@student.ubaya.ac.id',
                'nrp' => '130122002',
                'password' => Hash::make('dehar123'),
                'firstname' => 'Dedi',
                'lastname' => 'Hartono',
                'cv' => 'cv_dedi.pdf',
                'portofolio' => 'portofolio_dedi.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Psikologi - Psikologi
            [
                'email' => 's14022201@student.ubaya.ac.id',
                'nrp' => '140222001',
                'password' => Hash::make('ekput123'),
                'firstname' => 'Eka',
                'lastname' => 'Putri',
                'cv' => 'cv_eka.pdf',
                'portofolio' => 'portofolio_eka.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's140222002@student.ubaya.ac.id',
                'nrp' => '140222002',
                'password' => Hash::make('finan123'),
                'firstname' => 'Fitri',
                'lastname' => 'Ananda',
                'cv' => 'cv_fitri.pdf',
                'portofolio' => 'portofolio_fitri.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Hukum - Ilmu Hukum
            [
                'email' => 's150322001@student.ubaya.ac.id',
                'nrp' => '150322001',
                'password' => Hash::make('gapra123'),
                'firstname' => 'Galih',
                'lastname' => 'Pratama',
                'cv' => 'cv_galih.pdf',
                'portofolio' => 'portofolio_galih.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's150322002@student.ubaya.ac.id',
                'nrp' => '150322002',
                'password' => Hash::make('henwi123'),
                'firstname' => 'Hendra',
                'lastname' => 'Wijaya',
                'cv' => 'cv_hendra.pdf',
                'portofolio' => 'portofolio_hendra.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Industri Kreatif - Desain Komunikasi Visual
            [
                'email' => 's170522001@student.ubaya.ac.id',
                'nrp' => '170522001',
                'password' => Hash::make('insap123'),
                'firstname' => 'Indra',
                'lastname' => 'Saputra',
                'cv' => 'cv_indra.pdf',
                'portofolio' => 'portofolio_indra.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's170522002@student.ubaya.ac.id',
                'nrp' => '170522002',
                'password' => Hash::make('jenat123'),
                'firstname' => 'Jessica',
                'lastname' => 'Natalia',
                'cv' => 'cv_jessica.pdf',   
                'portofolio' => 'portofolio_jessica.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
