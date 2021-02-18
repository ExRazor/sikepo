<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Teacher;

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
                // 'id'         => Str::uuid()->toString(),
                'username'   => 'dikadikkun',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'kd_prodi'   => null,
                'name'       => 'Siddik Hatala',
                'foto'       => 'administrator.jpg',
                'defaultpass' => false,
                'is_active'  => true,
                'created_at' => now()
            ],
            [
                // 'id'         => Str::uuid()->toString(),
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'kd_prodi'   => null,
                'name'       => 'Administrator',
                'foto'       => null,
                'defaultpass' => false,
                'is_active'  => true,
                'created_at' => now()
            ],
            // [
            //     // 'id'       => Str::uuid()->toString(),
            //     'username'   => 'dikkun',
            //     'password'   => Hash::make('dikkun123'),
            //     'role'       => 'admin',
            //     'kd_prodi'   => null,
            //     'name'       => 'Siddik Hatala',
            //     'foto'       => null,
            //     'is_active'  => true,
            //     'created_at' => now()
            // ],
            // [
            //     // 'id'       => Str::uuid()->toString(),
            //     'username'   => 'kajur',
            //     'password'   => Hash::make('kajur'),
            //     'role'       => 'kajur',
            //     'kd_prodi'   => null,
            //     'name'       => 'Kepala Jurusan',
            //     'foto'       => null,
            //     'is_active'  => true,
            //     'created_at' => now()
            // ],
            // [
            //     // 'id'       => Str::uuid()->toString(),
            //     'username'   => 'kaprodisi',
            //     'password'   => Hash::make('kaprodisi'),
            //     'role'       => 'kaprodi',
            //     'kd_prodi'   => '57201',
            //     'name'       => 'Kaprodi Sistem Informasi',
            //     'foto'       => null,
            //     'is_active'  => true,
            //     'created_at' => now()
            // ],
            // [
            //     // 'id'       => Str::uuid()->toString(),
            //     'username'   => 'kaprodipti',
            //     'password'   => Hash::make('kaprodipti'),
            //     'role'       => 'kaprodi',
            //     'kd_prodi'   => '83207',
            //     'name'       => 'Kaprodi PTI',
            //     'foto'       => null,
            //     'is_active'  => true,
            //     'created_at' => now()
            // ],
        ]);

        $teacher = Teacher::all();

        // foreach($teacher as $t) {
        //     DB::table('users')->insert([
        //         [
        //             // 'id'         => Str::uuid()->toString(),
        //             'username'   => $t->nidn,
        //             'password'   => Hash::make($t->nidn),
        //             'role'       => 'dosen',
        //             'kd_prodi'   => null,
        //             'name'       => $t->nama,
        //             'defaultPass'=> true,
        //             'is_active'  => true,
        //             'created_at' => now()
        //         ],
        //     ]);
        // }
    }
}
