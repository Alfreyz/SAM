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

        DB::table('users')->insert([
            'idn' => '123',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'idn' => '0503037001',
            'password' => Hash::make('dosen'),
            'role' => 'dosen',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'idn' => '72190315',
            'password' => Hash::make('mahasiswa'),
            'role' => 'mahasiswa',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}