<?php

use Illuminate\Database\Seeder;
use App\Research;
use App\Teacher;

class ResearchTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $research = Research::all();

        foreach($research as $r) {
            $anggota = rand(1,5);
            for($i=0;$i<$anggota;$i++) {

                if($i==0) {
                    $status = 'Ketua';
                } else {
                    $status = 'Anggota';
                }

                if($status=='Ketua' && $anggota==1) {
                    $sks = $r->sks_penelitian;
                } elseif($status=='Ketua' && $anggota>1) {
                    $sks = $r->sks_penelitian * (60/100);
                } else {
                    $sks = $r->sks_penelitian * (40/100);
                }

                DB::table('research_teachers')->insert([
                    'id_penelitian' => $r->id,
                    'nidn'          => Teacher::all()->random()->nidn,
                    'status'        => $status,
                    'sks'           => $sks
                ]);
            }
        }
    }
}
