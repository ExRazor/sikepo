<?php

use Illuminate\Database\Seeder;
use App\Imports\CurriculumImport;
use Maatwebsite\Excel\Facades\Excel;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new CurriculumImport, public_path('import_seed/matakuliah_2020.xlsx'));
    }
}
