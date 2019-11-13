<?php

use Illuminate\Database\Seeder;
use App\Teacher;

class CommunityServiceSeeder extends Seeder
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

        $lembaga = ['Bank Indonesia','Bank MEGA','BPJS','Facebook','Google','Cisco'];

        $judul =[
            'Pengembangan Usaha Makanan Kecil di Kota Payakumbuh',
            'Pelatihan Pembelajaran Bahasa Inggris Berbasis Pariwisata Bagi Kelompok Sadar
            Wisata(Pokdarwis)Carocok Langkisau dan Koperasi Pedagang Kaki Lima Painan',
            'TEKNOLOGI PEMBENIHAN IKAN GURAMI (Osphronemus gouramy Lac) DALAM UPAYAMENINGKATKAN PENDAPATAN PELAKU PERIKANAN',
            'TEKNOLOGI PEMBENIHAN IKAN GURAMI (Osphronemus gouramy Lac) DALAM UPAYAMENINGKATKAN PENDAPATAN PELAKU PERIKANAN',
            'Petik Mas (Penetasan ternak itik untuk mensejahterakan masyarakat) di Kelompok Tani Jambak Saiyo dan Sinar Baru Kota Padang',
            'Desa Pulau Gadang dalam Memanfaatkan Biji Karet dan Limbah Ikan Patin',
            'PENYULUHAN DAN PELATIHAN INSTALASI MOTOR UNTUK PEMUDA KARANG TARUNAKELURAHAN SRI MERANTI KECAMATAN RUMBAI',
            'Pemberdayaan Masyarakat sekitar PT.Inti Indosawit Subur Provinsi Riau',
            'Meningkatkan Ketersediaan Pangan Berbasis Rumah Tangga Di Lahan Rawa Gambut Dengan Budidaya Palawija dan Umbi-umbian',
            'Program Pendampingan Implementasi Sistem Pencatatan Akuntansi dan Pengendalian Internal bagi Unit Usaha Laundry di Kota Batam',
            'Pengelolaan dan Pengembangan Usaha Budidaya Jamur Tiram di Desa Bangun Jaya Kecamatan Tambusai Utara Kabupaten Rokan Hulu',
            'Pengelolaan dan Pengembangan Usaha Budidaya Jamur Tiram di Desa Bangun Jaya Kecamatan Tambusai Utara Kabupaten Rokan Hulu',
            'Panti Asuhan di Kota Padang',
            'Pendampingan ASI ekslusif dan makanan pendamping ASI'
        ];

        $tahun = ['2013','2014','2015','2016','2017','2018','2019'];

        foreach($teacher as $t) {
            for($i=0;$i<5;$i++) {
                $random_sumber = $sumber_biaya[array_rand($sumber_biaya)];

                if($random_sumber === 'Lembaga Dalam Negeri' || $random_sumber === 'Lembaga Luar Negeri') {
                    $nama_lembaga = $lembaga[array_rand($lembaga)];
                } else {
                    $nama_lembaga = '';
                }

                $nominal = rand(1000, 50000).'000';

                DB::table('community_services')->insert([
                    'nidn'              => $t->nidn,
                    'tema_pengabdian'   => 'Analisis dan Perancangan',
                    'judul_pengabdian'  => $judul[array_rand($judul)],
                    'tahun_pengabdian'  => $tahun[array_rand($tahun)],
                    'sumber_biaya'      => $sumber_biaya[array_rand($sumber_biaya)],
                    'sumber_biaya_nama' => $nama_lembaga,
                    'jumlah_biaya'      => $nominal,
                    'created_at'        => now()
                ]);
            }
        }
    }
}
