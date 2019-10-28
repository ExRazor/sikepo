<div class="tab-pane fade profil-detail" id="profile">
    <div class="profil-judul">
        Profil Detail
    </div>

    <div class="profil-row d-flex">
        <div class="profil-nama">NIM</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{$data->nim}}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Nama Lengkap</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{$data->nama}}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Tempat Lahir</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{$data->tpt_lhr}}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Tanggal Lahir</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ date('d-m-Y', strtotime($data->tgl_lhr))}}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Jenis Kelamin</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->jk }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Agama</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->agama }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Alamat</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->alamat }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Kewarganegaraan</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->kewarganegaraan }}</div>
    </div>

    <div class="profil-judul mg-t-30">
        Akademik Kampus
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Program Studi</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->studyProgram->nama }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Kelas Mahasiswa</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->kelas }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Tipe Mahasiswa</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->tipe }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Program Akademik</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->program }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Seleksi Masuk</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->seleksi_jenis }} - {{ $data->seleksi_jalur }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Status Awal Masuk</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->masuk_status }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Tahun Awal Masuk</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->academicYear->tahun_akademik }} - {{ $data->academicYear->semester }}</div>
    </div>
    <div class="profil-row d-flex">
        <div class="profil-nama">Angkatan</div>
        <div class="profil-isi"><span class="pr-2">:</span>{{ $data->angkatan }}</div>
    </div>
</div>
