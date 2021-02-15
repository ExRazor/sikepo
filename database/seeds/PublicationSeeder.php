<?php

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\PublicationCategory;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teacher     = Teacher::all();
        $jenis       = PublicationCategory::all();

        $sumber_biaya = [
            'Perguruan Tinggi',
            'Mandiri',
            'Lembaga Dalam Negeri',
            'Lembaga Luar Negeri'
        ];

        $judul = [
            'Jurnalisme Kontemporer',
            'The Influence of Islamic Marketing Mix Towards The Enhancement of Muzakki Amountin RZ Bandung-Antapani Branch Office (A Survey Study in Muzakki Kecamatan 0n Antapani)',
            'Jurnalisme Sastra',
            'Menulis Feature',
            '“Call Center” sebagai Alat Komunikasi Pemasaran di Abad ke-21',
            'Pemasaran bagi Petualang sebagai Kegiatan Komunikasi Pemasaran',
            'Metodologi Penelitian Bisnis: Dengan Aplikasi SPSS (CD)',
            'PENGEMBANGAN PRODUK BARU BATIK KONTEMPORER STUDI KASUS BELAJAR DARI HASAN BATIK BANDUNG',
            'Pelatihan Pemasaran Online untuk Meningkatkan Volume Penjualan Pengrajin Sentra Kaos Sablonan di Daerah Suci Bandung',
            'KAJIAN KEMITRAAN KAMPOENG BNI DENGAN USAHATANI JAGUNG MANIS DI KECAMATAN PANUMBANGAN KABUPATEN CIAMIS',
            'The Relationship Marketing’s Influence Toward Customers Loyality (Survey on Customers of A-Trinity Ciwalk Bandung)',
            'Pengaruh Kepercayaan terhadap Keputusan Pembelian di Instagram Faithfootball',
            'The Effect of Brand Personality on Customer Loyalty of Warung Nasi Bancakan Bandung',
            'Pengaruh Bauran Pemasaran Islami (Islamic Marketing Mix) terhadap Peningkatan Jumlah Muzakki pada RZ Kantor Cabang Bandung Antapani'
        ];

        $penerbit = [
            'Published by Canadian Center of Science and Education',
            'Yayasan Obor Indonesia',
            'Depdikbud',
            'Penerbit Bani Quraisy (PBQ)',
            'Materials Research Bulletin',
            'Solid State Communications',
            'Humaniora Utama Press',
            'Remaja Karya',
            'Remaja Rosdakarya',
            'Pustaka Setia',
            'Mediator: Jurnal Komunikasi',
        ];

        $tahun = ['2013', '2014', '2015', '2016', '2017', '2018', '2019'];

        $sesuai = [0, 1];
        $rand_sesuai = $sesuai[array_rand($sesuai)];

        foreach ($teacher as $t) {
            for ($i = 0; $i < 5; $i++) {
                $sitasi = [null, rand(1, 5)];
                DB::table('teacher_publications')->insert([
                    // 'nidn'              => $t->nidn,
                    'jenis_publikasi'   => $jenis->random()->id,
                    'judul'             => $judul[array_rand($judul)],
                    'penerbit'          => $penerbit[array_rand($penerbit)],
                    'id_ta'             => AcademicYear::all()->random()->id,
                    // 'tahun'             => $tahun[array_rand($tahun)],
                    'sitasi'            => $sitasi[array_rand($sitasi)],
                    'sesuai_prodi'      => $rand_sesuai == '1' ? $rand_sesuai : null,
                    'created_at'        => now()
                ]);
            }
        }
    }
}
