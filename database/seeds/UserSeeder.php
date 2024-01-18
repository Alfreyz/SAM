<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        // Admin
        DB::table('users')->insert([
            'idn' => '123',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
