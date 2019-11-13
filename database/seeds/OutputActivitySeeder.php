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

        for($i=0;$i<100;$i++) {
            $penelitian = Research::all()->random()->id;
            $pengabdian = CommunityService::all()->random()->id;
            $kategori   = OutputActivityCategory::all()->random()->id;

            $kegiatan = $tipe[array_rand($tipe)];
            $tahun = ['2013','2014','2015','2016','2017','2018','2019'];

            DB::table('output_activities')->insert([
                'id_kategori'       => $kategori,
                'id_penelitian'     => ($kegiatan=='Penelitian' ? $penelitian : null),
                'id_pengabdian'     => ($kegiatan=='Pengabdian' ? $pengabdian : null),
                'kegiatan'          => $kegiatan,
                'judul_luaran'      => 'Ini adalah judul luaran',
                'tahun_luaran'      => $tahun[array_rand($tahun)],
                'keterangan'        => '',
                'created_at'        => now()
            ]);
        }
    }
}
