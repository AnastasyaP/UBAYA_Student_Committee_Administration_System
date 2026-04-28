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
                'status' => 'diterima',
                'percentage' => 0,
                'position' => 'Koordinator',
                'motivation' => 'Ingin mencoba hal baru',
                'idInterviewSchedules' => 2,   
                'invitation_token' => '',
                'invitation_expired' => now()->addDays(3),
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'idUsers' => 1,                 
                'idDivisions' => 1,           
                'idCommittees' => 1,           
                'status' => 'diterima',
                'percentage' => 0,
                'position' => 'Anggota',
                'motivation' => 'Ingin mencoba hal baru',
                'idInterviewSchedules' => 2,   
                'invitation_token' => '',
                'invitation_expired' => now()->addDays(3),
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'idUsers' => 2,
                'idDivisions' => 3,
                'idCommittees' => 2,
                'status' => 'menunggu',
                'percentage' => 70,
                'position' => 'Anggota',
                'motivation' => 'Ingin mencari dan menambah pengalaman',
                'idInterviewSchedules' => 1,
                'invitation_token' => '',
                'invitation_expired' => now()->addDays(3),
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'idUsers' => 3,
                'idDivisions' => 4,
                'idCommittees' => 2,
                'status' => 'ditolak',
                'percentage' => 40,
                'position' => 'Anggota',
                'motivation' => 'Ingin menambah relasi',
                'idInterviewSchedules' => 4,
                'invitation_token' => '',
                'invitation_expired' => now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
