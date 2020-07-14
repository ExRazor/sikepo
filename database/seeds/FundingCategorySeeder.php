<?php

use Illuminate\Database\Seeder;
use App\Models\FundingCategory;

class FundingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis_parent = [
            'Biaya Operasional Pendidikan',
            'Biaya Operasional Kemahasiswaan',
            'Biaya Penelitian',
            'Biaya PkM',
            'Biaya Investasi SDM',
            'Biaya Investasi Sarana',
            'Biaya Investasi Prasarana',
        ];

        $jenis_child = [
            'Biaya Dosen',
            'Biaya Tenaga Kependidikan',
            'Biaya Operasional Pembelajaran',
            'Biaya Operasional Tidak Langsung'
        ];

        $jenis_child_desc = [
            'Gaji, Honor',
            'Gaji, Honor',
            'Bahan dan Peralatan Habis Pakai',
            'Listrik, Gas, Air, Pemeliharaan Gedung, Pemeliharaan Sarana, Uang Lembur, Telekomunikasi, Konsumsi, Transport Lokal, Pajak, Asuransi, dll'
        ];


        foreach($jenis_parent as $i => $value){
            if($i<2){
                $jenis_biaya = 'Operasional';
            } else if($i<4) {
                $jenis_biaya = 'Penelitian dan Pengabdian';
            } else {
                $jenis_biaya = 'Investasi dan Sarana';
            }

            DB::table('funding_categories')->insert([
                'nama'   => $value,
                'jenis'  => $jenis_biaya,
                'created_at'    => now()
            ]);
        }

        foreach($jenis_child as $i => $value){
            DB::table('funding_categories')->insert([
                'id_parent'  => FundingCategory::where('nama','Biaya Operasional Pendidikan')->first()->id,
                'nama'       => $value,
                'jenis'      => FundingCategory::where('nama','Biaya Operasional Pendidikan')->first()->jenis,
                'deskripsi'  => $jenis_child_desc[$i],
                'created_at' => now()
            ]);
        }
    }
}
