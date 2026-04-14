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
                'start_period' => '2022-01-01',
                'end_period' => '2022-12-31',
                'description' => 'Informatics Logical Programming Competition Universitas Surabaya Tahun 2022.',
                'requirements' => 'Aktif dan memiliki jiwa kepemimpinan.',
                'poster' => 'img/committee/poster/PosterILPC2025.jpeg',
                'contact' => '08123456789',
                'start_regis' => '2022-01-10',
                'end_regis' => '2022-01-31',
                'evaluation' => null,
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],            [
                'admin' =>11,
                'committee_name' => 'ILPC',
                'name' => 'ILPC 2023',
                'start_period' => '2023-01-01',
                'end_period' => '2023-12-31',
                'description' => 'Informatics Logical Programming Competition Universitas Surabaya Tahun 2023.',
                'requirements' => 'Aktif dan memiliki jiwa kepemimpinan.',
                'poster' => 'img/committee/poster/PosterILPC2021.jpg',
                'contact' => '08123456789',
                'start_regis' => '2023-01-10',
                'end_regis' => '2023-01-31',
                'evaluation' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin' => 12,
                'committee_name' => 'CEG',
                'name' => 'CEG 2021',
                'start_period' => '2021-03-01',
                'end_period' => '2021-07-01',
                'description' => 'Chemical Engineering Games Universitas Surabaya Tahun 2021.',
                'requirements' => 'Mampu bekerja dalam tim dan berkomitmen.',
                'poster' => 'img/committee/poster/99f6e3ac-ebca-40aa-a05a-7bc053cb991d.jpeg',
                'contact' => '08987654321',
                'start_regis' => '2021-02-01',
                'end_regis' => '2021-02-15',
                'evaluation' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
