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
                'idUsers' => 1,                 
                'idDivisions' => 3,           
                'idCommittees' => 2,           
                'status' => 'pending',
                'percentage' => 0,
                'position' => 'anggota',
                'motivation' => 'Ingin mencoba hal baru',
                'idInterviewSchedules' => 2,   
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'idUsers' => 2,
                'idDivisions' => 2,
                'idCommittees' => 1,
                'status' => 'accepted',
                'percentage' => 70,
                'position' => 'koor',
                'motivation' => 'Ingin mencari dan menambah pengalaman',
                'idInterviewSchedules' => 1,
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'idUsers' => 3,
                'idDivisions' => 4,
                'idCommittees' => 2,
                'status' => 'rejected',
                'percentage' => 40,
                'position' => 'anggota',
                'motivation' => 'Ingin menambah relasi',
                'idInterviewSchedules' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
