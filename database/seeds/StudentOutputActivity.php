<?php

use Illuminate\Database\Seeder;
use App\Models\OutputActivityCategory;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\AcademicYear;

class StudentOutputActivity extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $tipe        = ['Penelitian','Pengabdian'];
        $tipe        = ['Penelitian','Pengabdian','Lainnya'];
        $pemilik     = ['Dosen','Mahasiswa'];
        $tahun       = ['2013','2014','2015','2016','2017','2018','2019'];
        $jenis       = ['Buku','Jurnal','HKI','HKI Paten'];


        for($i=0;$i<100;$i++) {
            $kategori   = OutputActivityCategory::all()->random()->id;
            $thn_luaran = $tahun[array_rand($tahun)];
            $student    = Student::whereHas(
                                        'studyProgram', function($q) {
                                            $q->where('kd_jurusan',setting('app_department_id'));
                                        }
                                    )
                                    ->inRandomOrder()
                                    ->first();

            $kegiatan = $tipe[array_rand($tipe)];
            $pilihPemilik = $pemilik[array_rand($pemilik)];

            $hal_1 = rand(0,350);
            $hal_2 = rand($hal_1,$hal_1+rand(0,15));
            $halaman = $hal_1.'-'.$hal_2;

            $pilihJenis = $jenis[array_rand($jenis)];


            if($pilihJenis == 'Buku')
            {
                DB::table('student_output_activities')->insert([
                    'kegiatan'          => $kegiatan,
                    'nm_kegiatan'       => 'Ini adalah nama kegiatan '.$kegiatan,
                    'nim'               => $student->nim,
                    'id_kategori'       => $kategori,
                    'judul_luaran'      => 'Ini adalah judul luaran',
                    'id_ta'             => AcademicYear::all()->random()->id,
                    // 'thn_luaran'        => $thn_luaran,
                    'jenis_luaran'      => $pilihJenis,
                    'nama_karya'        => 'Ini adalah judul buku',
                    'issn'              => rand(1000,9999).'-'.rand(1000,9999),
                    'penerbit'          => 'Penerbit Karawang Indah',
                    'url'               => 'http://contohurl.com',
                    'keterangan'        => 'Isi keterangan di sini',
                    'created_at'        => now()
                ]);
            }
            elseif ($pilihJenis == 'Jurnal')
            {
                DB::table('student_output_activities')->insert([
                    'kegiatan'          => $kegiatan,
                    'nm_kegiatan'       => 'Ini adalah nama kegiatan '.$kegiatan,
                    'nim'               => $student->nim,
                    'id_kategori'       => $kategori,
                    'judul_luaran'      => 'Ini adalah judul luaran',
                    'id_ta'             => AcademicYear::all()->random()->id,
                    // 'thn_luaran'        => $thn_luaran,
                    'jenis_luaran'      => $pilihJenis,
                    'nama_karya'        => 'Ini adalah judul jurnal',
                    'issn'              => rand(1000,9999).'-'.rand(1000,9999),
                    'penyelenggara'     => 'Ini adalah nama penyelenggara atau penerbit jurnal',
                    'url'               => 'http://contohurl.com',
                    'keterangan'        => 'Vol. '.rand(0,15).', No. '.rand(0,99).', Hal. '.$halaman,
                    'created_at'        => now()
                ]);
            }
            elseif ($pilihJenis == 'HKI')
            {
                DB::table('student_output_activities')->insert([
                    'kegiatan'          => $kegiatan,
                    'nm_kegiatan'       => 'Ini adalah nama kegiatan '.$kegiatan,
                    'nim'               => $student->nim,
                    'id_kategori'       => $kategori,
                    'judul_luaran'      => 'Ini adalah judul luaran',
                    'id_ta'             => AcademicYear::all()->random()->id,
                    // 'thn_luaran'        => $thn_luaran,
                    'jenis_luaran'      => $pilihJenis,
                    'nama_karya'        => 'Ini adalah judul karya HKI',
                    'jenis_karya'       => 'Ini adalah jenis karya HKI',
                    'no_permohonan'     => rand(1000,99999),
                    'tgl_permohonan'    => rand(2010,2020).'/'.rand(1,12).'/'.rand(1,28),
                    'keterangan'        => "Ini adalah keterangan",
                    'created_at'        => now()
                ]);
            }
            else
            {
                DB::table('student_output_activities')->insert([
                    'kegiatan'          => $kegiatan,
                    'nm_kegiatan'       => 'Ini adalah nama kegiatan '.$kegiatan,
                    'nim'               => $student->nim,
                    'id_kategori'       => $kategori,
                    'judul_luaran'      => 'Ini adalah judul luaran',
                    'id_ta'             => AcademicYear::all()->random()->id,
                    // 'thn_luaran'        => $thn_luaran,
                    'jenis_luaran'      => $pilihJenis,
                    'nama_karya'        => 'Ini adalah judul karya HKI Paten',
                    'jenis_karya'       => 'Ini adalah jenis karya HKI Paten',
                    'no_paten'          => rand(1000,99999),
                    'tgl_sah'           => rand(2010,2020).'/'.rand(1,12).'/'.rand(1,28),
                    'keterangan'        => "Ini adalah keterangan",
                    'created_at'        => now()
                ]);
            }
        }
    }
}
