<?php

use Illuminate\Database\Seeder;
use App\AcademicYear;

class FundingSeeder extends Seeder
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

        $academicYear = AcademicYear::where('semester','Ganjil')->where('tahun_akademik','>','2013')->get();

        foreach($academicYear as $ay) {
            for($i = 0; $i < count($jenis_parent); $i++){
                if($i<3){
                    $jenis_biaya = 'Operasional Akademik';
                } else if($i<4) {
                    $jenis_biaya = 'Pengabdian kepada Masyarakat';
                } else {
                    $jenis_biaya = 'Sarana dan Prasarana';
                }

                if($i == 1) {
                    $dana = '';
                } else {
                    $dana = rand(1000000,100000000);
                }

                DB::table('fundings')->insert([
                    'id_ta'         => $ay->id,
                    'jenis_biaya'   => $jenis_biaya,
                    'penggunaan'    => $jenis_parent[$i],
                    'alokasi_upps'  => $dana,
                    'alokasi_prodi' => $dana,
                    'created_at'    => now()
                ]);

                if($i == 1) {
                    for($j = 0; $j < count($jenis_child); $j++){
                        DB::table('fundings')->insert([
                            'id_ta'         => $ay->id,
                            'id_parent'     => $i,
                            'penggunaan'    => $jenis_child[$j],
                            'deskripsi'     => $jenis_child_desc[$j],
                            'alokasi_upps'  => rand(1000000,100000000),
                            'alokasi_prodi' => rand(1000000,100000000),
                            'created_at'    => now(),
                        ]);
                    }
                }
            }
        }
    }
}
