<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Teacher;

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
                'role'       => 'admin',
                'kd_prodi'   => null,
                'name'       => 'Administrator',
                'created_at' => now()
            ],
            [
                'id'       => Str::uuid()->toString(),
                'username'   => 'dikkun',
                'password'   => Hash::make('dikkun123'),
                'role'       => 'admin',
                'kd_prodi'   => null,
                'name'       => 'Siddik Hatala',
                'created_at' => now()
            ],
            [
                'id'       => Str::uuid()->toString(),
                'username'   => 'kajur',
                'password'   => Hash::make('kajur'),
                'role'       => 'kajur',
                'kd_prodi'   => null,
                'name'       => 'Kepala Jurusan',
                'created_at' => now()
            ],
            [
                'id'       => Str::uuid()->toString(),
                'username'   => 'kaprodisi',
                'password'   => Hash::make('kaprodisi'),
                'role'       => 'kaprodi',
                'kd_prodi'   => '57201',
                'name'       => 'Kaprodi Sistem Informasi',
                'created_at' => now()
            ],
            [
                'id'       => Str::uuid()->toString(),
                'username'   => 'kaprodipti',
                'password'   => Hash::make('kaprodipti'),
                'role'       => 'kaprodi',
                'kd_prodi'   => '83207',
                'name'       => 'Kaprodi PTI',
                'created_at' => now()
            ],
        ]);

        $teacher = Teacher::all();

        foreach($teacher as $t) {
            DB::table('users')->insert([
                [
                    'id'         => Str::uuid()->toString(),
                    'username'   => $t->nidn,
                    'password'   => Hash::make($t->nidn),
                    'role'       => 'dosen',
                    'kd_prodi'   => null,
                    'name'       => $t->nama,
                    'defaultPass'=> 1,
                    'created_at' => now()
                ],
            ]);
        }
    }
}
