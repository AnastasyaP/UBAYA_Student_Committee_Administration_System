<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterviewSchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tInterviewSchedules')->insert([
            [
                'date' => '2025-01-10',
                'time' => '09:00:00',
                'place' => 'Ruang Rapat Lantai 2',
                'link' => 'https://zoom.us/testinterview1',
                'idDivisions' => 2,   // sesuaikan dengan data di tListDivisions
                'idCommittees' => 1, // sesuaikan dengan data di tCommittees
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'date' => '2025-01-11',
                'time' => '13:30:00',
                'place' => 'Gedung Serbaguna UBAYA',
                'link' => 'https://zoom.us/testinterview2',
                'idDivisions' => 3,
                'idCommittees' => 2,
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'date' => '2025-01-12',
                'time' => '15:30:00',
                'place' => 'Bangku RGB Farmasi UBAYA',
                'link' => 'https://zoom.us/testinterview2',
                'idDivisions' => 3,
                'idCommittees' => 2,
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'date' => '2025-01-12',
                'time' => '10:00:00',
                'place' => 'Google Meet (Online)',
                'link' => 'https://meet.google.com/testinterview3',
                'idDivisions' => 4,
                'idCommittees' => 2,
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            
            [
                'date' => '2025-01-13',
                'time' => '10:00:00',
                'place' => 'Gaztek UBAYA',
                'link' => '',
                'idDivisions' => 4,
                'idCommittees' => 2,
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
        ]);
    }
}
