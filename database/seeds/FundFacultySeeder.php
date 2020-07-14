<?php

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use App\Models\FundingCategory;
use App\Models\AcademicYear;

class FundFacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $academicYear = AcademicYear::where('semester','Ganjil')->where('tahun_akademik','>','2013')->get();
        $category     = FundingCategory::all();
        $faculty      = Faculty::where('id',setting('app_faculty_id'))->first()->id;

        foreach($academicYear as $ay) {
            foreach($category as $c) {
                if(!$c->children->count()) {
                    $nominal = rand(10000, 50000).'000';
                    DB::table('funding_faculties')->insert([
                        'id_fakultas'   => $faculty,
                        'kd_dana'       => 'fak'.$faculty.'_thn'.$ay->id,
                        'id_ta'         => $ay->id,
                        'id_kategori'   => $c->id,
                        'nominal'       => $nominal,
                        'created_at'    => now()
                    ]);
                }
            }
        }
    }
}
