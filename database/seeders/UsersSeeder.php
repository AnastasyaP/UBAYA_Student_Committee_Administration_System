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

        // seeder tabel user dan mahasiswa


        DB::table('tUsers')->insert([
             // Fakultas Teknik
            [
                'email' => 's160422001@student.ubaya.ac.id',
                'username' => 'ansos',
                'password' => Hash::make('ansos123'),
                'firstname' => 'Andi',
                'lastname' => 'Santoso',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's160122002@student.ubaya.ac.id',
                'username' => 'budwi',
                'password' => Hash::make('budwi123'),
                'firstname' => 'Budi',
                'lastname' => 'Wijaya',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Bisnis & Ekonomi - Manajemen
            [
                'email' => 's130122001@student.ubaya.ac.id',
                'username' => 'ciles',
                'password' => Hash::make('ciles123'),
                'firstname' => 'Citra',
                'lastname' => 'Lestari',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's130122002@student.ubaya.ac.id',
                'username' => 'dehar',
                'password' => Hash::make('dehar123'),
                'firstname' => 'Dedi',
                'lastname' => 'Hartono',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Psikologi - Psikologi
            [
                'email' => 's14022201@student.ubaya.ac.id',
                'username' => 'ekput',
                'password' => Hash::make('ekput123'),
                'firstname' => 'Eka',
                'lastname' => 'Putri',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's140222002@student.ubaya.ac.id',
                'username' => 'finan',
                'password' => Hash::make('finan123'),
                'firstname' => 'Fitri',
                'lastname' => 'Ananda',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Hukum - Ilmu Hukum
            [
                'email' => 's150322001@student.ubaya.ac.id',
                'username' => 'gaprat',
                'password' => Hash::make('gapra123'),
                'firstname' => 'Galih',
                'lastname' => 'Pratama',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's150322002@student.ubaya.ac.id',
                'username' => 'henwi',
                'password' => Hash::make('henwi123'),
                'firstname' => 'Hendra',
                'lastname' => 'Wijaya',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Industri Kreatif - Desain Komunikasi Visual
            [
                'email' => 's170522001@student.ubaya.ac.id',
                'username' => 'insap',
                'password' => Hash::make('insap123'),
                'firstname' => 'Indra',
                'lastname' => 'Saputra',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 's170522002@student.ubaya.ac.id',
                'username' => 'jenat',
                'password' => Hash::make('jenat123'),
                'firstname' => 'Jessica',
                'lastname' => 'Natalia',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Admins
            [
                'email' => 'adminilpc@gmail.com',
                'username' => 'adminilpc',
                'password'=> Hash::make('ilpc123'),
                'firstname' => 'Admin',
                'lastname' => 'ILPC',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'email' => 'adminceg@gmail.com',
                'username' => 'adminceg',
                'password'=> Hash::make('ceg123'),
                'firstname' => 'Admin',
                'lastname' => 'CEG',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'email' => 'admin@argon.com',
                'username' => 'superadmin',
                'password'=> Hash::make('123'),
                'firstname' => 'Super',
                'lastname' => 'Admin',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('tMahasiswas')->insert([
             // Fakultas Teknik
            [
                'nrp' => '160422001',
                'cv' => 'cv/297-617-1-PB.pdf',
                'portofolio' => 'portofolio/286-1269-2-PB.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nrp' => '160122002',
                'cv' => 'cv/297-617-1-PB.pdf',
                'portofolio' => 'portofolio_budi.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Bisnis & Ekonomi - Manajemen
            [
                'nrp' => '130122001',
                'cv' => 'cv/297-617-1-PB.pdf',
                'portofolio' => 'portofolio/286-1269-2-PB.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nrp' => '130122002',
                'cv' => 'cv/297-617-1-PB.pdf',
                'portofolio' => 'portofolio/286-1269-2-PB.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Psikologi - Psikologi
            [
                'nrp' => '140222001',
                'cv' => 'cv/297-617-1-PB.pdf',
                'portofolio' => 'portofolio/286-1269-2-PB.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nrp' => '140222002',
                'cv' => 'cv/297-617-1-PB.pdf',
                'portofolio' => 'portofolio/286-1269-2-PB.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Hukum - Ilmu Hukum
            [
                'nrp' => '150322001',
                'cv' => 'cv/297-617-1-PB.pdf',
                'portofolio' => 'portofolio/286-1269-2-PB.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nrp' => '150322002',
                'cv' => 'cv/297-617-1-PB.pdf',
                'portofolio' => 'portofolio/286-1269-2-PB.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fakultas Industri Kreatif - Desain Komunikasi Visual
            [
                'nrp' => '170522001',
                'cv' => 'cv/297-617-1-PB.pdf',
                'portofolio' => 'portofolio/286-1269-2-PB.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nrp' => '170522002',
                'cv' => 'cv/297-617-1-PB.pdf',  
                'portofolio' => 'portofolio/286-1269-2-PB.pdf',
                'picture' => 'img/mahasiswa/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'idUsers'=> 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
