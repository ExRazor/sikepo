@extends('report.pdf.template')

@section('title','Data Penelitian Periode '.$keterangan['periode'].' ('.$keterangan['disahkan'].')')

@section('header')
<h1 class="title">Data {{$keterangan['jenis']}}</h1>
<div class="d-flex justify-content-around description">
    <div>
        <table>
            <tr>
                <td>Laporan</td>
                <td>:</td>
                <td>Penelitian</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{$keterangan['periode']}}</td>
            </tr>
        </table>
    </div>
    <div>
        <table>
            <tr>
                <td>Fakultas</td>
                <td>:</td>
                <td>{{$keterangan['fakultas']}}</td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td>:</td>
                <td>{{$keterangan['jurusan']}}</td>
            </tr>
            @if($keterangan['prodi'])
            <tr>
                <td>Program Studi</td>
                <td>:</td>
                <td>{{$keterangan['prodi']}}</td>
            </tr>
            @endif
        </table>
    </div>
</div>
@endsection

@section('content')
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="align-middle text-center" width="45">No</th>
            <th class="align-middle text-center" width="230">Nama Dosen</th>
            @if(!$keterangan['prodi'])
            <th class="align-middle text-center" width="150">Homebase</th>
            @endif
            <th class="align-middle text-center">Judul Penelitian</th>
            <th class="align-middle text-center" width="125">Tahun</th>
            <th class="align-middle text-center" width="190">Sumber Biaya</th>
            <th class="align-middle text-center" width="130">Biaya</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        <tr>
            <td class='text-center'>{{ $loop->iteration }}</td>
            <td>
                {{ $d->researchKetua->teacher->nama }}<br>
                <small class="text-muted">NIDN.{{ $d->researchKetua->teacher->nidn }}</small>
            </td>
            @if(!$keterangan['prodi'])
            <td>{{ $d->researchKetua->teacher->latestStatus->studyProgram->nama }}</td>
            @endif
            <td>{{ $d->judul_penelitian }}</td>
            <td>{{ $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester }}</td>
            <td>{{ $d->sumber_biaya ?? '-' }}</td>
            <td>{{ rupiah($d->jumlah_biaya) ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('footer')
<div class="d-flex justify-content-between description sign">

    <div>
        @if($keterangan['prodi'])
        <br>
        Ketua Program Studi
        <br><br><br><br><br><br>
        <u>{{$ttd['kaprodi']['nama']}}</u><br>
        NIP. {{$ttd['kaprodi']['nip']}}
        @endif
    </div>
    <div>
        Gorontalo, {{$keterangan['disahkan']}}
        <br>
        Ketua Jurusan
        <br><br><br><br><br><br>
        <u>{{$ttd['kajur']['nama']}}</u><br>
        NIP. {{$ttd['kajur']['nip']}}
    </div>
</div>
@endsection

@push('custom-css')
<style type="text/css">

</style>
@endpush

