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
        $status = ['DT','DTT'];
        $sesuai = ['Ya','Tidak'];

    	for($i = 0; $i < 20; $i++){
                // insert data ke table pegawai menggunakan Faker
            DB::table('teachers')->insert([
                'nidn'                  => rand(000000000, 999999999),
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
                'bidang_ahli'           => 'Apa saja',
                'dosen_ps'              => StudyProgram::all()->random()->kd_prodi,
                'status_pengajar'       => $status[array_rand($status)],
                'jabatan_akademik'      => 'Dosen',
                'sertifikat_pendidik'   => 'hehe',
                'sesuai_bidang_ps'      => $sesuai[array_rand($sesuai)],
                ]);
        }
    }
}
