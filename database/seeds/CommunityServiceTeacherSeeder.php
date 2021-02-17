<?php

use App\Models\CommunityService;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommunityServiceTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $community_services = CommunityService::all();

        foreach ($community_services as $cs) {
            $anggota = rand(1, 5);
            for ($i = 0; $i < $anggota; $i++) {

                if ($i == 0) {
                    $status = 'Ketua';
                } else {
                    $status = 'Anggota';
                }

                if ($status == 'Ketua' && $anggota == 1) {
                    $sks = $cs->sks_pengabdian;
                } elseif ($status == 'Ketua' && $anggota > 1) {
                    $sks = $cs->sks_pengabdian * (setting('service_ratio_chief') / 100);
                } else {
                    $sks = $cs->sks_pengabdian * ((setting('service_ratio_members') / ($anggota - 1)) / 100);
                }

                $asal       = ['dalam', 'luar'];
                $rand_asal  = $asal[array_rand($asal)];
                $nidn       = Teacher::all()->random()->nidn;

                if ($rand_asal == 'dalam') {
                    $nidn       = Teacher::all()->random()->nidn;
                    $nama_lain  = null;
                    $asal_lain  = null;
                } else {
                    $nidn       = null;
                    $nama_lain  = $faker->name;
                    $asal_lain  = 'Luar program studi';
                }

                DB::table('community_service_teachers')->insert([
                    'id_pengabdian' => $cs->id,
                    'nidn'          => $nidn,
                    'nama'          => $nama_lain,
                    'asal'          => $asal_lain,
                    'status'        => $status,
                    'sks'           => $sks
                ]);
            }
        }
    }
}
