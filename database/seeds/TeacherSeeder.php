<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\StudyProgram;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $jk = ['Laki-Laki','Perempuan'];
        $agama = ['Islam','Kristen','Katholik','Buddha','Hindu','Kong Hu Cu'];
        $pend = ['D3','S1','S2','S3'];
        $jurusan = ['Informatika','Ilmu Komputer','Elektro','Industri'];
        $jabatan = ['Tenaga Pengajar','Asisten Ahli','Lektor','Lektor Kepala','Guru Besar'];
        $sesuai = ['Ya','Tidak'];
        $bidang_ahli = ['PHP','Photoshop','Elektronika','OOP','Office'];
        $ikatan = ['Dosen Tetap PS','Dosen Tidak Tetap','Dosen Tetap PT'];


        $random = ['0','1','2'];

    	for($i = 0; $i < 50; $i++){
            $studyProgram = StudyProgram::all()->random();
            $rand_numb    = array_rand($random);

            // if($studyProgram->kd_jurusan == setting('app_department_id')) {
            //     $ikatan = ['Dosen Tetap PS'];
            // } else {
            //     $ikatan = ['Dosen Tidak Tetap','Dosen Tetap PT'];
            // }

            if($rand_numb == '1') {
                $sertifikat = 'hehe';
            } else {
                $sertifikat = null;
            }

            DB::table('teachers')->insert([
                'nidn'                  => rand(000000000, 999999999),
                'kd_prodi'              => $studyProgram->kd_prodi,
                'nip'                   => rand(197201011982010101, 199001012000010101),
                'nama'                  => $faker->name,
                'jk'                    => $jk[array_rand($jk)],
                'agama'                 => $agama[array_rand($agama)],
                'tpt_lhr'               => $faker->city,
                'tgl_lhr'               => $faker->date($format = 'Y-m-d', $max = '1990-12-31'),
                'alamat'                => $faker->address,
                'no_telp'               => $faker->phoneNumber,
                'email'                 => $faker->email,
                'pend_terakhir_jenjang' => $pend[array_rand($pend)],
                'pend_terakhir_jurusan' => $jurusan[array_rand($jurusan)],
                'bidang_ahli'           => json_encode($bidang_ahli),
                'ikatan_kerja'          => $ikatan[array_rand($ikatan)],
                'jabatan_akademik'      => $jabatan[array_rand($jabatan)],
                'sertifikat_pendidik'   => $sertifikat,
                'sesuai_bidang_ps'      => $sesuai[array_rand($sesuai)],
                'created_at'            => now()
            ]);
        }
    }
}
