<?php

use App\CommunityService;
use App\Teacher;
use Illuminate\Database\Seeder;

class CommunityServiceTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $community_services = CommunityService::all();

        foreach($community_services as $cs) {
            $anggota = rand(1,5);
            for($i=0;$i<$anggota;$i++) {

                if($i==0) {
                    $status = 'Ketua';
                } else {
                    $status = 'Anggota';
                }

                if($status=='Ketua' && $anggota==1) {
                    $sks = $cs->sks_pengabdian;
                } elseif($status=='Ketua' && $anggota>1) {
                    $sks = $cs->sks_pengabdian * (60/100);
                } else {
                    $sks = $cs->sks_pengabdian * (40/100);
                }

                DB::table('community_service_teachers')->insert([
                    'id_pengabdian' => $cs->id,
                    'nidn'          => Teacher::all()->random()->nidn,
                    'status'        => $status,
                    'sks'           => $sks
                ]);
            }
        }
    }
}
