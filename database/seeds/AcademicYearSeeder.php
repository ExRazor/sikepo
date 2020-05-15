<?php

use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=2010;$i<=date('Y');$i++) {
            if($i==date('Y')) {
                $status = 'Aktif';
            } else {
                $status = 'Tidak Aktif';
            }

            DB::table('academic_years')->insert([
                [
                    'tahun_akademik' => $i,
                    'semester' => 'Ganjil',
                    'status' => $status,
                    'created_at' => now()
                ],
                [
                    'tahun_akademik' => $i,
                    'semester' => 'Genap',
                    'status' => 'Tidak Aktif',
                    'created_at' => now()
                ]
            ]);
        }
    }
}
