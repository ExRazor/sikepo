<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Research;
use App\Models\Teacher;

class ResearchTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $research = Research::all();

        foreach ($research as $r) {
            $anggota = rand(1, 5);
            for ($i = 0; $i < $anggota; $i++) {

                if ($i == 0) {
                    $status = 'Ketua';
                } else {
                    $status = 'Anggota';
                }

                if ($status == 'Ketua' && $anggota == 1) {
                    $sks = $r->sks_penelitian;
                } elseif ($status == 'Ketua' && $anggota > 1) {
                    $sks = $r->sks_penelitian * (setting('research_ratio_chief') / 100);
                } else {
                    $sks = $r->sks_penelitian * ((setting('research_ratio_members') / ($anggota - 1)) / 100);
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

                DB::table('research_teachers')->insert([
                    'id_penelitian' => $r->id,
                    'nidn'          => $nidn,
                    'nama'     => $nama_lain,
                    'asal'     => $asal_lain,
                    'status'        => $status,
                    'sks'           => $sks
                ]);
            }
        }
    }
}
