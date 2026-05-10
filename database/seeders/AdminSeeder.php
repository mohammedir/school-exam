<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->insert([
            [
                'full_name' => 'المدير العام للنظام',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'super-admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'مدير',
                'username' => 'manager',
                'email' => 'manager@admin.com',
                'password' => Hash::make('10002000'),
                'role' => 'super-admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
