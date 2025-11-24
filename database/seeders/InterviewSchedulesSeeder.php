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
                'date' => '2025-11-20',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'place' => 'Ruang Rapat Lantai 2',
                'link' => 'https://zoom.us/testinterview1',
                'idDivisions' => 2,   // sesuaikan dengan data di tListDivisions
                'idCommittees' => 1, // sesuaikan dengan data di tCommittees
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'date' => '2025-11-21',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'place' => 'Gedung Serbaguna UBAYA',
                'link' => 'https://zoom.us/testinterview2',
                'idDivisions' => 3,
                'idCommittees' => 2,
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'date' => '2025-11-24',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'place' => 'Bangku RGB Farmasi UBAYA',
                'link' => 'https://zoom.us/testinterview2',
                'idDivisions' => 3,
                'idCommittees' => 2,
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'date' => '2025-11-21',
                'start_time' => '13:30:00',
                'end_time' => '14:00:00',
                'place' => 'Google Meet (Online)',
                'link' => 'https://meet.google.com/testinterview3',
                'idDivisions' => 4,
                'idCommittees' => 2,
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            
            [
                'date' => '2025-11-23',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
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
