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
                'emailAdmins' => 'adminilpc@gmail.com',
                'password'=> Hash::make('ilpc123'),
                'is_superAdmin'=>0,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'emailAdmins' => 'adminceg@gmail.com',
                'password'=> Hash::make('ceg123'),
                'is_superAdmin'=>0,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'emailAdmins' => 'admin@argon.com',
                'password'=> Hash::make('123'),
                'is_superAdmin'=>1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
