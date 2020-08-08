<?php

use Illuminate\Database\Seeder;
use App\Models\Department;
use Faker\Factory as Faker;

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
                'kd_unik' => 5314,
                'nama' => 'Sistem Informasi',
                'singkatan' => 'SISFO',
                'jenjang' => 'S1',
                'no_sk' => 'PTN/20/2203/1229854/INFOR/UNG',
                'tgl_sk' => '2008/04/20',
                'pejabat_sk' => 'Kurniawan Salih, S.T, M.T',
                'thn_menerima' => '2008',
                // 'nip_kaprodi' => '198004172002122002',
                // 'nm_kaprodi' => 'Lillyan Hadjaratie',
                'created_at' => now()
            ],
            [
                'kd_prodi' => 83207,
                'kd_jurusan' => 57401,
                'kd_unik' => 5324,
                'nama' => 'Pend. Teknologi Informasi',
                'singkatan' => 'PTI',
                'jenjang' => 'S1',
                'no_sk' => 'PTN/32/2008/1034567/INFOR/UNG',
                'tgl_sk' => '2013/06/14',
                'pejabat_sk' => 'Hermawan Prasetyo, M.Eng',
                'thn_menerima' => '2013',
                // 'nip_kaprodi' => '197511242001121001',
                // 'nm_kaprodi' => 'Dian Novian',
                'created_at' => now()
            ],
        ]);

        $faker = Faker::create('id_ID');
        $jenjang = ['D3','S1','S2','S3'];

        // for($i = 0; $i < 20; $i++){
        //     $kd_prodi = rand(22222, 59999);
        //     DB::table('study_programs')->insert([
        //         [
        //             'kd_prodi'      => $kd_prodi,
        //             'kd_jurusan'    => Department::where('kd_jurusan','!=',setting('app_department_id'))->inRandomOrder()->first()->kd_jurusan,
        //             'kd_unik'       => substr($kd_prodi, 0, -1),
        //             'nama'          => 'Program Studi Random '.$i,
        //             'singkatan'     => 'PSR'.$i,
        //             'jenjang'       => $jenjang[array_rand($jenjang)],
        //             'nip_kaprodi'   => rand(197201011982010101, 199001012000010101),
        //             'nm_kaprodi'    => $faker->name,
        //             'created_at'    => now()
        //         ],
        //     ]);
        // }
    }
}
