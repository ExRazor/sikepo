<?php

use Illuminate\Database\Seeder;
use App\Teacher;
use App\Student;
use App\AcademicYear;
use App\Minithesis;

class MinithesisSeeder extends Seeder
{
    public function run()
    {

        $judul = [
            'Perancangan Sistem Informasi Penjualan Batik Berbasis Web pada Gerai Adhiwastra Jakarta',
            'Perancangan Sistem Informasi Manajemen Bimbingan Skripsi Berbasis Web',
            'Perancangan Sistem Administrasi Sekolah Dengan SMS Gateway Berbasis Web Menggunakan Gammu Pada SMK LPI Semarang',
            'Pengembangan Sistem Informasi Bursa Kerja Khusus (Bkk) Berbasis Web Dengan Php Dan Mysql Di Smk Negeri 2 Wonosari',
            'Aplikasi Pelayanan Dan Pengelolaan Data Bengkel Secara Elektronik Berbasis Web',
            'Sistem Informasi Geografis Pemetaan Penyakit Kronis dan Demam Berdarah di Puskesmas 1 Baturiti Berbasis Website',
            'Perancangan sistem informasi rumah kost berbasis web dan short message service (sms) menggunakan php dan mysql',
            'Sistem Informasi Berbasis Web Pada Desa Tresnomaju Kecamatan Negerikaton Kab. Pesawaran',
            'Implementasi Framework Codeignter Untuk Pengembangan Website Pada Dinas Perkebunan Provinsi Kalimantan Tengah',
            'Portal Wedding Organizer Menggunakan Sistem Informasi Geografis Berbasis Website Di Kabupaten Kudus',
        ];

        for($j = 0; $j < 50; $j++){

            $pembimbing_utama = Teacher::whereHas(
                                            'studyProgram', function($q) {
                                                $q->where('kd_jurusan',setting('app_department_id'));
                                            }
                                        )
                                        ->inRandomOrder()
                                        ->first();

            $pembimbing_pendamping = Teacher::where('nidn','!=',$pembimbing_utama->nidn)
                                    ->inRandomOrder()
                                    ->first();

            $student = Student::all()->random();

            $tahun_akademik = AcademicYear::all()->random();

            Minithesis::updateOrCreate(
                [
                    'nim'      => $student->nim,
                    'id_ta'    => $tahun_akademik->id,
                ],
                [
                    'pembimbing_utama'     => $pembimbing_utama->nidn,
                    'pembimbing_pendamping'=> $pembimbing_pendamping->nidn,
                    'judul'                => $judul[array_rand($judul)],
                    'created_at'           => now()
                ]
            );

        }
    }
}
