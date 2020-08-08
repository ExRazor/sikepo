<?php

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
        [
            'kd_jurusan'   => '20401',
            'id_fakultas'  => '5',
            'nama'         => 'Teknik Elektro',
            // 'nip_kajur'    => '197411252001121002',
            // 'nm_kajur'     => 'Ervan Hasan Harun',
            'created_at' => now()
        ],
        [
            'kd_jurusan'   => '22401',
            'id_fakultas'  => '5',
            'nama'         => 'Teknik Sipil',
            // 'nip_kajur'    => '196904071999032001',
            // 'nm_kajur'     => 'Aryati Alitu',
            'created_at' => now()
        ],
        [
            'kd_jurusan'   => '23401',
            'id_fakultas'  => '5',
            'nama'         => 'Teknik Arsitektur',
            // 'nip_kajur'    => '198006022005012001',
            // 'nm_kajur'     => 'Elvie Fathmah Mokodongan',
            'created_at' => now()
        ],
        [
            'kd_jurusan'   => '26401',
            'id_fakultas'  => '5',
            'nama'         => 'Teknik Industri',
            // 'nip_kajur'    => '197410222005011002',
            // 'nm_kajur'     => 'Idham Halid Lahay',
            'created_at' => now()
        ],
        [
            'kd_jurusan'   => '57401',
            'id_fakultas'  => '5',
            'nama'         => 'Teknik Informatika',
            // 'nip_kajur'    => '197812082003121002',
            // 'nm_kajur'     => 'Tajuddin Abdillah',
            'created_at' => now()
        ],
        [
            'kd_jurusan'   => '88210',
            'id_fakultas'  => '5',
            'nama'         => 'Teknik Kriya',
            // 'nip_kajur'    => '',
            // 'nm_kajur'     => 'Drs. Yus Iryanto Abas',
            'created_at' => now()
        ]
        ]);
    }
}
