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
                $status = true;
            } else {
                $status = false;
            }

            DB::table('academic_years')->insert([
                [
                    'tahun_akademik' => $i,
                    'semester' => 'Ganjil',
                    'status' => false,
                    'created_at' => now()
                ],
                [
                    'tahun_akademik' => $i,
                    'semester' => 'Genap',
                    'status' => $status,
                    'created_at' => now()
                ]
            ]);
        }
    }
}
