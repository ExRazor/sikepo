<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Teacher;
use App\Models\AcademicYear;

class TeacherAchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $prestasi = [
                    'Menjadi Staf Ahli/Tenaga Ahli/Narasumber',
                    'Menjadi visiting lecturer/visiting scholar di PT berakreditasi A',
                    'Menjadi invited speaker di pertemuan ilmiah',
                    'Menjadi editor atau mitra bestari pada jurnal terkareditasi'
                ];

        $tingkat = [
                    'Wilayah',
                    'Nasional',
                    'Internasional',
                ];

        for($i = 0; $i < 20; $i++){
            $nidn = Teacher::all()->random()->nidn;
            for($j = 0; $j < 5; $j++){
                DB::table('teacher_achievements')->insert([
                    'nidn'              => $nidn,
                    'id_ta'             => AcademicYear::all()->random()->id,
                    'prestasi'          => $prestasi[array_rand($prestasi)],
                    'tingkat_prestasi'  => $tingkat[array_rand($tingkat)],
                    // 'tanggal'               => $faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now', $timezone = 'Asia/Singapore'),
                    'bukti_nama'        => 'Sertifikat',
                    'bukti_file'        => 'hehe.jpg',
                    'created_at'        => now()
                ]);
            }
        }
    }
}
