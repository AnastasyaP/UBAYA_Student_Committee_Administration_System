<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('tRegistrations')->insert([
            [
                'idUsers' => 1,                 // pastikan ID user ada
                'idDivisions' => 3,            // pastikan ID division ada
                'idCommittees' => 2,           // pastikan ID committee ada
                'status' => 'pending',
                'percentage' => 0,
                'position' => 'anggota',
                'idInterviewSchedules' => 2,   // pastikan ID schedule ada
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'idUsers' => 2,
                'idDivisions' => 2,
                'idCommittees' => 1,
                'status' => 'accepted',
                'percentage' => 87,
                'position' => 'koor',
                'idInterviewSchedules' => 1,
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'idUsers' => 3,
                'idDivisions' => 4,
                'idCommittees' => 2,
                'status' => 'rejected',
                'percentage' => 45,
                'position' => 'anggota',
                'idInterviewSchedules' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
