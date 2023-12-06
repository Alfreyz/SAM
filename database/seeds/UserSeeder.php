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

        // Dosen
        DB::table('users')->insert([
            'idn' => '0503037001',
            'password' => Hash::make('dosen1'),
            'role' => 'dosen',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'idn' => '0527076801',
            'password' => Hash::make('dosen2'),
            'role' => 'dosen',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->insert([
            'idn' => '0508087001',
            'password' => Hash::make('dosen3'),
            'role' => 'dosen',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->insert([
            'idn' => '0503017001',
            'password' => Hash::make('dosen4'),
            'role' => 'dosen',
            'created_at' => $now,
            'updated_at' => $now,
        ]);


        // Mahasiswa angkatan 21
        $startIdnMahasiswa21 = 72210447;
        $endIdnMahasiswa21 = 72210519;

        for ($idn21 = $startIdnMahasiswa21; $idn21 <= $endIdnMahasiswa21; $idn21++) {
            $password = Hash::make('mahasiswa21' . ($idn21 - $startIdnMahasiswa21 + 1));

            DB::table('users')->insert([
                'idn' => $idn21,
                'password' => $password,
                'role' => 'mahasiswa',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        // Mahasiswa
        $startIdnMahasiswa22 = 72220520;
        $endIdnMahasiswa22 = 72220605;
        for ($idn22 = $startIdnMahasiswa22; $idn22 <= $endIdnMahasiswa22; $idn22++) {
            $password = Hash::make('mahasiswa22' . ($idn22 - $startIdnMahasiswa22 + 1));
            DB::table('users')->insert([
                'idn' => $idn22,
                'password' => $password,
                'role' => 'mahasiswa',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
