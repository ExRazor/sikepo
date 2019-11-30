<?php

use Illuminate\Database\Seeder;

class SatisfactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $kat_pend = [
            'Keandalan',
            'Daya Tanggap',
            'Kepastian',
            'Empati',
            'Tangible',
        ];

        $alias_pend = [
            'Reliability',
            'Responsiveness',
            'Assurance',
            'Empathy',
            'Tangible'
        ];

        $desc_pend = [
            'Kemampuan dosen, tenaga kependidikan, dan pengelola dalam memberikan pelayanan.',
            'Kemauan dari dosen, tenaga kependidikan, dan pengelola dalam membantu mahasiswa dan memberikan jasa dengan cepat.',
            'Kemampuan dosen, tenaga kependidikan, dan pengelola untuk memberikan keyakinan kepada mahasiswa bahwa pelayanan yang diberikan telah sesuai dengan ketentuan.',
            'Kesediaan/Kepedulian dosen, tenaga kependidikan, dan pengelola untuk memberi perhatian kepada mahasiswa.',
            'Penilaian mahasiswa terhadap kecukupan, aksesibilitas, kualitas sarana dan prasarana.'
        ];

        foreach($kat_pend as $i => $val) {
            DB::table('satisfaction_categories')->insert([
                'jenis'         => 'Akademik',
                'nama'          => $val,
                'alias'         => $alias_pend[$i],
                'deskripsi'     => $desc_pend[$i],
                'created_at'    => now()
            ]);
        }

        $kat_alumni = [
            'Etika',
            'Keahlian Bidang Ilmu (Kompetensi Utama)',
            'Kemampuan Berbahasa Asing',
            'Penggunaan Teknologi Informasi',
            'Kemampuan Berkomunikasi',
            'Kerja Sama Tim',
            'Pengembangan Diri',
        ];

        foreach($kat_alumni as $i => $val) {
            DB::table('satisfaction_categories')->insert([
                'jenis'         => 'Alumni',
                'nama'          => $val,
                'created_at'    => now()
            ]);
        }
    }
}
