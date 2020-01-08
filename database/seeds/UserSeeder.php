<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id'       => Str::uuid()->toString(),
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'role'       => 'Admin',
                'kd_prodi'   => null,
                'name'       => 'Administrator',
                'created_at' => now()
            ],
            [
                'id'       => Str::uuid()->toString(),
                'username'   => 'dikkun',
                'password'   => Hash::make('dikkun123'),
                'role'       => 'Admin',
                'kd_prodi'   => null,
                'name'       => 'Siddik Hatala',
                'created_at' => now()
            ],
            [
                'id'       => Str::uuid()->toString(),
                'username'   => 'kaprodi',
                'password'   => Hash::make('kajurinfor'),
                'role'       => 'Kajur',
                'kd_prodi'   => null,
                'name'       => 'Kepala Jurusan',
                'created_at' => now()
            ],
            [
                'id'       => Str::uuid()->toString(),
                'username'   => 'kaprodisi',
                'password'   => Hash::make('kaprodisi'),
                'role'       => 'Kaprodi',
                'kd_prodi'   => '57201',
                'name'       => 'Kaprodi Sistem Informasi',
                'created_at' => now()
            ],
            [
                'id'       => Str::uuid()->toString(),
                'username'   => 'kaprodipti',
                'password'   => Hash::make('kaprodipti'),
                'role'       => 'Kaprodi',
                'kd_prodi'   => '83207',
                'name'       => 'Kaprodi PTI',
                'created_at' => now()
            ],

            ]);
    }
}
