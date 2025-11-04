<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'username' => 'admin',
        //     'firstname' => 'Admin',
        //     'lastname' => 'Admin',
        //     'email' => 'admin@argon.com',
        //     'password' => '123',
        //     // 'password' => bcrypt('secret')
        // ]);
        $this->call(OrganizerUnitsSeeder::class);
        $this->call(AdminsSeeder::class);
        $this->call(CommitteesSeeder::class);
        $this->call(DivisionsSeeder::class);
    }
}
