<?php

use Illuminate\Database\Seeder;
use App\Research;
use App\CommunityService;
use App\Curriculum;
use App\Teacher;
use App\AcademicYear;

class CurriculumIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $academicYear = AcademicYear::where('tahun_akademik','>','2015')->get();

        foreach($academicYear as $ay) {
            for($j = 0; $j < rand(0,9); $j++){
                $jenis      = ['Penelitian','Pengabdian'];
                $integrasi  = ['Materi Kuliah','Bab dalam Buku Ajar','Materi Praktikum'];
                $penelitian = Research::all()->random()->id;
                $pengabdian = CommunityService::all()->random()->id;

                $kegiatan = $jenis[array_rand($jenis)];

                DB::table('curriculum_integrations')->insert([
                    'id_ta'             => $ay->id,
                    'id_penelitian'     => ($kegiatan=='Penelitian' ? $penelitian : null),
                    'id_pengabdian'     => ($kegiatan=='Pengabdian' ? $pengabdian : null),
                    'kegiatan'          => $kegiatan,
                    'nidn'              => Teacher::all()->random()->nidn,
                    'kd_matkul'         => Curriculum::all()->random()->kd_matkul,
                    'bentuk_integrasi'  => $integrasi[array_rand($integrasi)],
                    'created_at'        => now()
                ]);

            }
        }
    }
}
