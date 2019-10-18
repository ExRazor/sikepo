<?php

use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('study_programs')->insert([
            [
                'kd_prodi' => 57201,
                'kd_jurusan' => 57401,
                'nama' => 'Sistem Informasi',
                'singkatan' => 'SISFO',
                'jenjang' => 'S1',
                'no_sk' => 'PTN/20/2203/1229854/INFOR/UNG',
                'tgl_sk' => '2008/04/20',
                'pejabat_sk' => 'Kurniawan Salih, S.T, M.T',
                'thn_menerima' => '2008',
                'nip_kaprodi' => '198004172002122002',
                'nm_kaprodi' => 'LILLYAN HADJARATIE',
            ],
            [
                'kd_prodi' => 53242,
                'kd_jurusan' => 57401,
                'nama' => 'Pendidikan Teknologi dan Informasi',
                'singkatan' => 'PTI',
                'jenjang' => 'S1',
                'no_sk' => 'PTN/32/2008/1034567/INFOR/UNG',
                'tgl_sk' => '2013/06/14',
                'pejabat_sk' => 'Hermawan Prasetyo, M.Eng',
                'thn_menerima' => '2013',
                'nip_kaprodi' => '197511242001121001',
                'nm_kaprodi' => 'DIAN NOVIAN',
            ],
        ]);
    }
}
