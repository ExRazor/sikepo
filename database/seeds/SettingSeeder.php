<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
        [
            'name'   => 'app_name',
            'value'  => 'Sistem Informasi Kinerja Program Studi',
            'created_at' => now()
        ],
        [
            'name'   => 'app_short',
            'value'  => 'SIKEPO',
            'created_at' => now()
        ],
        [
            'name'   => 'app_description',
            'value'  => 'Sebuah aplikasi sistem informasi yang dirancang untuk memenuhi kebutuhan data jurusan terkait data-data kinerja untuk keperluan borang akreditasi.',
            'created_at' => now()
        ],
        [
            'name'   => 'app_department_id',
            'value'  => '57401',
            'created_at' => now()
        ],
        [
            'name'   => 'app_department_name',
            'value'  => 'Teknik Informatika',
            'created_at' => now()
        ],
        [
            'name'   => 'app_faculty_id',
            'value'  => '5',
            'created_at' => now()
        ],
        [
            'name'   => 'app_faculty_name',
            'value'  => 'Fakultas Teknik',
            'created_at' => now()
        ],
        [
            'name'   => 'research_ratio_chief',
            'value'  => '60',
            'created_at' => now()
        ],
        [
            'name'   => 'research_ratio_members',
            'value'  => '40',
            'created_at' => now()
        ],
        [
            'name'   => 'service_ratio_chief',
            'value'  => '60',
            'created_at' => now()
        ],
        [
            'name'   => 'service_ratio_members',
            'value'  => '40',
            'created_at' => now()
        ],
        ]);
    }
}
