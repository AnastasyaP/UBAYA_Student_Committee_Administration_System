<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommitteesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tCommittees')->insert([
            [
                'admin' => 11,
                'committee_name' => 'ILPC',
                'name' => 'ILPC 2022',
                'start_period' => '2021-09-28',
                'end_period' => '2022-02-08',
                'end_evaluation' => '2022-03-08',
                'description' => 'Informatics Logical Programming Competition Universitas Surabaya Tahun 2022.',
                'requirements' => 'Aktif dan memiliki jiwa kepemimpinan.',
                'poster' => 'img/committee/poster/PosterILPC2025.jpeg',
                'picture' => 'img/committee/picture/logo_ilpc.jpeg',
                'contact' => '08123456789',
                'start_regis' => '2021-08-26',
                'end_regis' => '2021-09-09',
                'evaluation' => null,
                'is_active' => 0,
                'is_published' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],            [
                'admin' =>11,
                'committee_name' => 'ILPC',
                'name' => 'ILPC 2023',
                'start_period' => '2022-09-28',
                'end_period' => '2023-02-08',
                'end_evaluation' => '2023-03-08',
                'description' => 'Informatics Logical Programming Competition Universitas Surabaya Tahun 2023.',
                'requirements' => 'Aktif dan memiliki jiwa kepemimpinan.',
                'poster' => 'img/committee/poster/PosterILPC2021.jpg',
                'picture' => 'img/committee/picture/logo_ilpc.jpeg',
                'contact' => '08123456789',
                'start_regis' => '2022-08-26',
                'end_regis' => '2022-09-09',
                'evaluation' => null,
                'is_active' => 1,
                'is_published' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin' => 12,
                'committee_name' => 'CEG',
                'name' => 'CEG 2021',
                'start_period' => '2020-10-28',
                'end_period' => '2021-05-01',
                'end_evaluation' => '2021-06-01',
                'description' => 'Chemical Engineering Games Universitas Surabaya Tahun 2021.',
                'requirements' => 'Mampu bekerja dalam tim dan berkomitmen.',
                'poster' => 'img/committee/poster/99f6e3ac-ebca-40aa-a05a-7bc053cb991d.jpeg',
                'picture' => 'img/committee/picture/logo_ceg.png',
                'contact' => '08987654321',
                'start_regis' => '2020-09-26',
                'end_regis' => '2020-10-10',
                'evaluation' => null,
                'is_active' => 1,
                'is_published' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin' => 11,
                'committee_name' => 'Mini ILPC',
                'name' => 'Mini ILPC 2023',
                'start_period' => '2022-09-28',
                'end_period' => '2023-01-08',
                'end_evaluation' => '2023-02-08',
                'description' => 'Mini ILPC is a preparation before a real ILPC.',
                'requirements' => 'Mampu bekerja dalam tim dan berkomitmen.',
                'poster' => 'img/committee/poster/99f6e3ac-ebca-40aa-a05a-7bc053cb991d.jpeg',
                'picture' => 'img/committee/picture/logo_ilpc.jpeg',
                'contact' => '08987654321',
                'start_regis' => '2022-08-26',
                'end_regis' => '2022-09-09',
                'evaluation' => null,
                'is_active' => 1,
                'is_published' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin' => 13,
                'committee_name' => 'GI',
                'name' => 'Gathering Informatics 2027',
                'start_period' => '2026-11-08',
                'end_period' => '2027-06-02',
                'end_evaluation' => '2027-07-02',
                'description' => 'Gathering dalam bentuk outbound dengan tujuan memberikan pengalaman kepada peserta dalam berbaur dengan teman satu angkatannya maupun berbeda angkatan. Selain itu, terdapat kegiatan talkshow yang menghadirkan 2 alumni Teknik Informatika yang akan membawa materi terkait dunia kerja sebagai seorang fresh graduate dan orang yang sudah bekerja lama di bidang informatika.',
                'requirements' => 'Mampu bekerja dalam tim dan berkomitmen.',
                'poster' => 'img/committee/poster/99f6e3ac-ebca-40aa-a05a-7bc053cb991d.jpeg',
                'picture' => 'img/committee/picture/logo_gathering.jpeg',
                'contact' => '08987654321',
                'start_regis' => '2026-09-21',
                'end_regis' => '2026-10-02',
                'evaluation' => null,
                'is_active' => 1,
                'is_published' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
