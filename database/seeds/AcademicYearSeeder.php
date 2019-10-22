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
            DB::table('academic_years')->insert([
                [
                    'tahun_akademik' => $i,
                    'semester' => 'Ganjil',
                    'status' => 'Tidak Aktif',
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
