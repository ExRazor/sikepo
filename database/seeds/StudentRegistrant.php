<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\AcademicYear;
use App\StudyProgram;

class StudentRegistrant extends Seeder
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
        $asal_sekolah = ['SMKN 1 Gorontalo','SMAN 1 Gorontalo','SMKN 1 Limboto','SMAN 3 Gorontalo','SMKN 3 Gorontalo'];
        $jalur_masuk = ['SNMPTN','SBMPTN','Mandiri','Lain-Lain'];
        $status = ['Lulus','Tidak Lulus'];

    	for($i = 0; $i < 50; $i++){
                // insert data ke table pegawai menggunakan Faker
            DB::table('student_registrants')->insert([
                'nisn'                  => rand(0000000000, 9999999999),
                'id_ta'                 => AcademicYear::all()->random()->id,
                'nama'                  => $faker->name,
                'jk'                    => $jk[array_rand($jk)],
                'agama'                 => $agama[array_rand($agama)],
                'tpt_lhr'               => $faker->city,
                'tgl_lhr'               => $faker->date($format = 'Y-m-d', $max = '2001-12-31'),
                'alamat'                => $faker->address,
                'no_telp'               => $faker->phoneNumber,
                'email'                 => $faker->email,
                'asal_sekolah'          => $asal_sekolah[array_rand($asal_sekolah)],
                'jalur_masuk'           => $jalur_masuk[array_rand($jalur_masuk)],
                'kd_prodi'              => StudyProgram::all()->random()->kd_prodi,
                'status_lulus'          => $status[array_rand($status)],
            ]);
        }
    }
}
