<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeywordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tKeywords')->insert([
            ['idKeywords'=>1,'name'=>'Event Planning', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>2,'name'=>'Public Speaking', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>3,'name'=>'Desain', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>4,'name'=>'Programming', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>5,'name'=>'Logika', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>6,'name'=>'Logistik', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>7,'name'=>'Kesehatan', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>8,'name'=>'Administrasi', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>9,'name'=>'Negosiasi', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>10,'name'=>'Security', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>11,'name'=>'Kreativitas', 'created_at' => now(), 'updated_at' => now()],
            ['idKeywords'=>12,'name'=>'Kerja Sama', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
