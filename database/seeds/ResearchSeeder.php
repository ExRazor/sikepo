<?php

use App\AcademicYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Teacher;

class ResearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teacher     = Teacher::all();
        $sumber_biaya = [
            'Perguruan Tinggi',
            'Mandiri',
            'Lembaga Dalam Negeri',
            'Lembaga Luar Negeri'
        ];

        $judul_penelitian =[
            'Perbandingan Atmosfir Pendidikan Dengan Nilai Modul Awal Pada Mahasiswa Semester Dua Fakultas Kedokteran',
            'Analisa Dan Pembuatan Aplikasi Perangkat Lunak Perhitungan Percepatan Titik Sendi Penghubung Mekanisme Engkol Peluncur Pada Sepeda Motor Suzuki Smash 110 Cc',
            'Analisis Faktor-Faktor Yang Mempengaruhi Kepuasan Pelanggan Bengkel Otomotif Roda Dua Dengan Metode Important Performance Analysis',
            'Sistem Pendeteksi Plagiarisme Pada Dokumen Tugas Mahasiswa',
            'Pengaruh Beban Angin Terhadap Distribusi Displacement Pada Berbagai Tinggi Bejana Tekan Untuk Kawasan Industri Bitung',
            'Analisis Kualitas Layanan Di Pusat Perawatan Dan Perbaikan Kendaraan Roda Dua Dengan Metode Servperf – Sixsigma',
            'Robot Pintar Pengukur Kepuasan Konsumen Pada Pusat Perbelanjaan',
            'Perencanaan Lansekap Kawasan Agrowisata Terpadu Desa Batu Kecamatan Likupang Selatan  Kabupaten Minahasa Utara',
            'Potensi Sumber Energi Terbarukan Di Desa Kilometer Tiga Kabupaten Minahasa Selatan',
            'Tinjauan Laju Pendinginan Pada Mesin Pendingin Lucas Nulle Type RCC2 Dengan Menggunakan Bahan Pendingin Alamiah (Hidrocarbon) Dan Bahan Pendingin Sintetik (Halocarbon)',
            'Perubahan Head Suction Terhadap Head Discharge Dan Kapsitas Pompa Hidram',
            'Perancangan Aplikasi Video Streaming Terkompresi',
            'Kajian Peluang Konservasi Energi Listrik Pada Konsumen Komersial',
            'Analisa Konsumsi Bahan Bakar Campuran Brown Gas Dan Bensin Pada Motor Suzuki Smash 110 Cc',
            'Perancangan Sistem Informasi Administrasi Terpadu Untuk Meningkatkan Efisiensi Pengelolahan Dokumen',
            'Dna Barcoding Jagung Lokal Sulawesi Utara Berdasarkan Gen Matk'
        ];

        $tingkat = [
            'Internasional',
            'Nasional',
            'Lokal',
        ];

        $sks_penelitian = [3,4,5,6];

        $sesuai = [0,1];

        foreach($teacher as $t) {
            for($i=0;$i<5;$i++) {
                $random_sumber = $sumber_biaya[array_rand($sumber_biaya)];

                if($random_sumber === 'Lembaga Dalam Negeri' || $random_sumber === 'Lembaga Luar Negeri') {
                    $nama_lembaga = 'Bank Indonesia';
                } else {
                    $nama_lembaga = '';
                }

                $rand_sesuai = $sesuai[array_rand($sesuai)];

                $nominal = rand(1000, 50000).'000';
                DB::table('researches')->insert([
                    'id_ta'             => AcademicYear::all()->random()->id,
                    'judul_penelitian'  => $judul_penelitian[array_rand($judul_penelitian)],
                    'tema_penelitian'   => 'Analisis dan Perancangan',
                    'tingkat_penelitian'=> $tingkat[array_rand($tingkat)],
                    'sesuai_prodi'      => $rand_sesuai=='1' ? $rand_sesuai : null,
                    'sks_penelitian'    => $sks_penelitian[array_rand($sks_penelitian)],
                    'sumber_biaya'      => $sumber_biaya[array_rand($sumber_biaya)],
                    'sumber_biaya_nama' => $nama_lembaga,
                    'jumlah_biaya'      => $nominal,
                    'created_at'        => now()
                ]);
            }
        }
    }
}
