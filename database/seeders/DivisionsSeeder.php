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
            ['name' => 'DDD', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sekretariat', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sponsorship', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Soal', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Konsumsi dan Kesehatan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sistem Informasi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Perlengkapan dan Konsumsi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keamanan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === tListDivisions ===
        DB::table('tListDivisions')->insert([
            // ILPC 2022
            [
                'idCommittees' => 1, 
                'idDivisions' => 1,  
                'is_open' => 1,
                'description' => 'Merancang konsep, tema, dan tagline ILPC 2025, Membuat rally games, game besar, ice breaking, MC dan PIC tiap bagian dari Acara, Mengatur pelaksanaan dan persiapan simulasi acara ILPC 2025, Membuat rundown acara ILPC 2025',
                'picture' => 'img/division/31fc1abb-7a74-4226-b8a7-f98576776d6c.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 6,
                'is_open' => 1,
                'description' => 'Sudah menentukan isi poster pendaftaran, Sudah mulai publikasi email ke sekolah-sekolah, Sudah menyiapkan ide untuk konten, Sudah mulai melakukan publikasi, Akan melakukan RoadShow mulai tanggal 15 dan 22 November',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 5,
                'is_open' => 1,
                'description' => 'Sudah membuat Virtual Identity, Sudah melakukan konfirmasi untuk cover proposal jurusan dan sekolah, Sudah membuat maskot ILPC 2025, Sudah membuat desain yang direquest divisi SI, sudah membuat desain untuk rally dari divisi Acara, Sudah membuat poster publikasi dan open PO, Sudah membuat video throwback ILPC',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 7,
                'is_open' => 1,
                'description' => 'Sudah melakukan 2 PO, Sudah ada 3 Media Partner terupload, Sedang menyebar proposal',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 8,
                'is_open' => 1,
                'description' => 'Soal logika udah ada 29 soal mudah dan 29 soal sedang, Soal pemrograman ada 5 soal mudah dan 7 soal sedang dan 1 soal susah, Akan melanjutkan pembuatan soal, Akan berkoordinasi dengan divisi acara terkait soal yang diperlukan, Akan berkoordinasi dan testing dengan divisi SI',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 3,
                'is_open' => 1,
                'description' => 'Sudah siapkan property dasar rally, Sudah mencetak beberapa design dari DDD, Sebagian property tinggal dihias, Sebagian besar barang game sudah ready',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 12,
                'is_open' => 1,
                'description' => 'Sudah mencari vendor HT, Sudah mencari tempat penginapan peserta, Sudah menentukan transport untuk roadshow, Sudah membuat SOP peserta dan panitia',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 9,
                'is_open' => 1,
                'description' => 'Sudah mencari catering, Sudah buat sheet obat dan alergi panitia & peserta, Sudah melakukan latihan 1 konkes, Sedang menyiapkan untuk pinjem obat dan tandu',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 1,
                'idDivisions' => 10,
                'is_open' => 0,
                'description' => 'Sudah melakukan update design web, Sudah melakukan deploy, Sudah melakukan update error message, Sudah melakukan update design programming, Sudah melakukan testing',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ILPC 2023
             [
                'idCommittees' => 2, 
                'idDivisions' => 1,  
                'is_open' => 1,
                'description' => 'Merancang konsep, tema, dan tagline ILPC 2025, Membuat rally games, game besar, ice breaking, MC dan PIC tiap bagian dari Acara, Mengatur pelaksanaan dan persiapan simulasi acara ILPC 2025, Membuat rundown acara ILPC 2025',
                'picture' => 'img/division/31fc1abb-7a74-4226-b8a7-f98576776d6c.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 6,
                'is_open' => 1,
                'description' => 'Sudah menentukan isi poster pendaftaran, Sudah mulai publikasi email ke sekolah-sekolah, Sudah menyiapkan ide untuk konten, Sudah mulai melakukan publikasi, Akan melakukan RoadShow mulai tanggal 15 dan 22 November',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 5,
                'is_open' => 1,
                'description' => 'Sudah membuat Virtual Identity, Sudah melakukan konfirmasi untuk cover proposal jurusan dan sekolah, Sudah membuat maskot ILPC 2025, Sudah membuat desain yang direquest divisi SI, sudah membuat desain untuk rally dari divisi Acara, Sudah membuat poster publikasi dan open PO, Sudah membuat video throwback ILPC',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 7,
                'is_open' => 1,
                'description' => 'Sudah melakukan 2 PO, Sudah ada 3 Media Partner terupload, Sedang menyebar proposal',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 8,
                'is_open' => 1,
                'description' => 'Soal logika udah ada 29 soal mudah dan 29 soal sedang, Soal pemrograman ada 5 soal mudah dan 7 soal sedang dan 1 soal susah, Akan melanjutkan pembuatan soal, Akan berkoordinasi dengan divisi acara terkait soal yang diperlukan, Akan berkoordinasi dan testing dengan divisi SI',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 3,
                'is_open' => 1,
                'description' => 'Sudah siapkan property dasar rally, Sudah mencetak beberapa design dari DDD, Sebagian property tinggal dihias, Sebagian besar barang game sudah ready',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 12,
                'is_open' => 1,
                'description' => 'Sudah mencari vendor HT, Sudah mencari tempat penginapan peserta, Sudah menentukan transport untuk roadshow, Sudah membuat SOP peserta dan panitia',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 9,
                'is_open' => 1,
                'description' => 'Sudah mencari catering, Sudah buat sheet obat dan alergi panitia & peserta, Sudah melakukan latihan 1 konkes, Sedang menyiapkan untuk pinjem obat dan tandu',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idCommittees' => 2,
                'idDivisions' => 10,
                'is_open' => 0,
                'description' => 'Sudah melakukan update design web, Sudah melakukan deploy, Sudah melakukan update error message, Sudah melakukan update design programming, Sudah melakukan testing',
                'picture' => 'img/division/ae8714d6-4623-4189-9b90-7512d27c23a7.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        // === tListDivisionKeywords ===
        DB::table('tListDivisionKeywords')->insert([

            // ===== ILPC 2022 (1) =====
            // Acara
            ['idDivisions'=>1,'idCommittees'=>1,'idKeywords'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>1,'idCommittees'=>1,'idKeywords'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>1,'idCommittees'=>1,'idKeywords'=>12,'created_at'=>now(),'updated_at'=>now()],

            // Sekretariat
            ['idDivisions'=>6,'idCommittees'=>1,'idKeywords'=>8,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>6,'idCommittees'=>1,'idKeywords'=>12,'created_at'=>now(),'updated_at'=>now()],

            // DDD
            ['idDivisions'=>5,'idCommittees'=>1,'idKeywords'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>5,'idCommittees'=>1,'idKeywords'=>11,'created_at'=>now(),'updated_at'=>now()],

            // Sponsorship
            ['idDivisions'=>7,'idCommittees'=>1,'idKeywords'=>9,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>7,'idCommittees'=>1,'idKeywords'=>12,'created_at'=>now(),'updated_at'=>now()],

            // Soal
            ['idDivisions'=>8,'idCommittees'=>1,'idKeywords'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>8,'idCommittees'=>1,'idKeywords'=>4,'created_at'=>now(),'updated_at'=>now()],

            // Perlengkapan
            ['idDivisions'=>3,'idCommittees'=>1,'idKeywords'=>6,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>3,'idCommittees'=>1,'idKeywords'=>12,'created_at'=>now(),'updated_at'=>now()],

            // Keamanan
            ['idDivisions'=>12,'idCommittees'=>1,'idKeywords'=>10,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>12,'idCommittees'=>1,'idKeywords'=>6,'created_at'=>now(),'updated_at'=>now()],

            // Konsumsi & Kesehatan
            ['idDivisions'=>9,'idCommittees'=>1,'idKeywords'=>7,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>9,'idCommittees'=>1,'idKeywords'=>6,'created_at'=>now(),'updated_at'=>now()],

            // Sistem Informasi
            ['idDivisions'=>10,'idCommittees'=>1,'idKeywords'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>10,'idCommittees'=>1,'idKeywords'=>5,'created_at'=>now(),'updated_at'=>now()],


            // ===== ILPC 2023 (2) =====
            // Acara
            ['idDivisions'=>1,'idCommittees'=>2,'idKeywords'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>1,'idCommittees'=>2,'idKeywords'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>1,'idCommittees'=>2,'idKeywords'=>12,'created_at'=>now(),'updated_at'=>now()],

            // Sekretariat
            ['idDivisions'=>6,'idCommittees'=>2,'idKeywords'=>8,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>6,'idCommittees'=>2,'idKeywords'=>12,'created_at'=>now(),'updated_at'=>now()],

            // DDD
            ['idDivisions'=>5,'idCommittees'=>2,'idKeywords'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>5,'idCommittees'=>2,'idKeywords'=>11,'created_at'=>now(),'updated_at'=>now()],

            // Sponsorship
            ['idDivisions'=>7,'idCommittees'=>2,'idKeywords'=>9,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>7,'idCommittees'=>2,'idKeywords'=>12,'created_at'=>now(),'updated_at'=>now()],

            // Soal
            ['idDivisions'=>8,'idCommittees'=>2,'idKeywords'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>8,'idCommittees'=>2,'idKeywords'=>4,'created_at'=>now(),'updated_at'=>now()],

            // Perlengkapan
            ['idDivisions'=>3,'idCommittees'=>2,'idKeywords'=>6,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>3,'idCommittees'=>2,'idKeywords'=>12,'created_at'=>now(),'updated_at'=>now()],

            // Keamanan
            ['idDivisions'=>12,'idCommittees'=>2,'idKeywords'=>10,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>12,'idCommittees'=>2,'idKeywords'=>6,'created_at'=>now(),'updated_at'=>now()],

            // Konsumsi & Kesehatan
            ['idDivisions'=>9,'idCommittees'=>2,'idKeywords'=>7,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>9,'idCommittees'=>2,'idKeywords'=>6,'created_at'=>now(),'updated_at'=>now()],

            // Sistem Informasi
            ['idDivisions'=>10,'idCommittees'=>2,'idKeywords'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['idDivisions'=>10,'idCommittees'=>2,'idKeywords'=>5,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
