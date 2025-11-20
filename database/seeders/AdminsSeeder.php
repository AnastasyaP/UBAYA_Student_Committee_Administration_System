<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tAdmins')->insert([
            [
                'is_superAdmin'=>0,
                'idOrganizerUnits' => 8,
                'idUsers' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'is_superAdmin'=>0,
                'idOrganizerUnits' => 9,
                'idUsers' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'is_superAdmin'=>1,
                'idOrganizerUnits' => 1,
                'idUsers' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
