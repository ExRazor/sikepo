<?php

use Illuminate\Database\Seeder;
use App\Research;
use App\CommunityService;
use App\OutputActivityCategory;

class OutputActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipe        = ['Penelitian','Pengabdian'];
        // $tipe        = ['Penelitian','Pengabdian','Lainnya'];

        for($i=0;$i<100;$i++) {
            $penelitian = Research::all()->random()->id;
            $pengabdian = CommunityService::all()->random()->id;
            $kategori   = OutputActivityCategory::all()->random()->id;

            $kegiatan = $tipe[array_rand($tipe)];
            $tahun = ['2013','2014','2015','2016','2017','2018','2019'];
            $pembuat = ['Dosen','Mahasiswa'];
            $hal_1 = rand(0,350);
            $hal_2 = rand($hal_1,$hal_1+rand(0,15));
            $halaman = $hal_1.'-'.$hal_2;

            DB::table('output_activities')->insert([
                'id_kategori'       => $kategori,
                'pembuat_luaran'    => $pembuat[array_rand($pembuat)],
                'kegiatan'          => $kegiatan,
                'id_penelitian'     => ($kegiatan=='Penelitian' ? $penelitian : null),
                'id_pengabdian'     => ($kegiatan=='Pengabdian' ? $pengabdian : null),
                'lainnya'           => ($kegiatan=='Lainnya' ? 'Luaran lainnya' : null),
                'judul_luaran'      => 'Ini adalah judul luaran',
                'jurnal_luaran'     => 'Ini adalah judul jurnal luaran',
                'tahun_luaran'      => $tahun[array_rand($tahun)],
                'issn'              => rand(1000,9999).'-'.rand(1000,9999),
                'volume_hal'        => 'Vol. '.rand(0,15).', No. '.rand(0,99).', Hal. '.$halaman,
                'url'               => 'http://contohurl.com',
                'keterangan'        => 'Isi keterangan di sini',
                'created_at'        => now()
            ]);
        }
    }
}
