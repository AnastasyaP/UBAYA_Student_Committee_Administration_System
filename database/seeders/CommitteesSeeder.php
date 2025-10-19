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
                'idAdmins' => 1,
                'name' => 'ILPC 2022',
                'start_period' => '2022-01-01',
                'end_period' => '2022-12-31',
                'description' => 'Informatics Logical Programming Competition Universitas Surabaya Tahun 2022.',
                'requirements' => 'Aktif dan memiliki jiwa kepemimpinan.',
                'picture' => 'ilpc2022.png',
                'contact' => '08123456789',
                'start_regis' => '2022-01-10',
                'end_regis' => '2022-01-31',
                'evaluation' => null,
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],            [
                'idAdmins' =>1,
                'name' => 'ILPC 2023',
                'start_period' => '2023-01-01',
                'end_period' => '2023-12-31',
                'description' => 'Informatics Logical Programming Competition Universitas Surabaya Tahun 2023.',
                'requirements' => 'Aktif dan memiliki jiwa kepemimpinan.',
                'picture' => 'ilpc2023.png',
                'contact' => '08123456789',
                'start_regis' => '2023-01-10',
                'end_regis' => '2023-01-31',
                'evaluation' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idAdmins' => 2,
                'name' => 'CEG 2021',
                'start_period' => '2021-03-01',
                'end_period' => '2021-07-01',
                'description' => 'Chemical Engineering Games Universitas Surabaya Tahun 2021.',
                'requirements' => 'Mampu bekerja dalam tim dan berkomitmen.',
                'picture' => 'ceg2021.png',
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
