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
        // insert data ke table pegawai
        DB::table('academic_years')->insert([
            [
                'tahun_akademik' => 2017,
                'semester' => 'Ganjil',
                'status' => 'Aktif',
            ],
            [
                'tahun_akademik' => 2017,
                'semester' => 'Genap',
                'status' => 'Tidak Aktif',
            ]
        ]);
    }
}
