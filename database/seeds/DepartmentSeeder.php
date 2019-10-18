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
            'nama'         => 'TEKNIK ELEKTRO',
            'nip_kajur'    => '197411252001121002',
            'nm_kajur'     => 'ERVAN HASAN HARUN',
        ],
        [
            'kd_jurusan'   => '22401',
            'id_fakultas'  => '5',
            'nama'         => 'TEKNIK SIPIL',
            'nip_kajur'    => '196904071999032001',
            'nm_kajur'     => 'ARYATI ALITU',
        ],
        [
            'kd_jurusan'   => '23401',
            'id_fakultas'  => '5',
            'nama'         => 'TEKNIK ARSITEKTUR',
            'nip_kajur'    => '198006022005012001',
            'nm_kajur'     => 'ELVIE FATMAH MOKODONGAN',
        ],
        [
            'kd_jurusan'   => '26401',
            'id_fakultas'  => '5',
            'nama'         => 'TEKNIK INDUSTRI',
            'nip_kajur'    => '197410222005011002',
            'nm_kajur'     => 'IDHAM HALID LAHAY',
        ],
        [
            'kd_jurusan'   => '57401',
            'id_fakultas'  => '5',
            'nama'         => 'TEKNIK INFORMATIKA',
            'nip_kajur'    => '197812082003121002',
            'nm_kajur'     => 'TAJUDDIN ABDILLAH',
        ],
        [
            'kd_jurusan'   => '88210',
            'id_fakultas'  => '5',
            'nama'         => 'TEKNIK KRIYA',
            'nip_kajur'    => '',
            'nm_kajur'     => 'DRS YUS IRYANTO ABAS, M.PD',
        ]
        ]);
    }
}
