<?php

use Illuminate\Database\Seeder;

class PublicationCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis = [
            'Jurnal Nasional Tidak Terakreditasi',
            'Jurnal Nasional Terakreditasi',
            'Jurnal Internasional',
            'Jurnal Internasional Bereputasi',
            'Seminar Wilayah/Lokal/Perguruan Tinggi',
            'Seminar Nasional',
            'Seminar Internasional',
            'Tulisan di Media Massa Wilayah',
            'Tulisan di Media Massa Nasional',
            'Tulisan di Media Massa Internasional',
        ];

        foreach($jenis as $i => $value){

            DB::table('publication_categories')->insert([
                'nama'          => $value,
                'created_at'    => now()
            ]);
        }
    }
}
