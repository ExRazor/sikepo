@extends('report.pdf.template')

@section('title','Data Penelitian Periode '.$keterangan['periode'].' ('.$keterangan['disahkan'].')')

@section('header')
<h1 class="title">Data {{$keterangan['jenis']}}</h1>
<table width="100%">
    <tr>
        <td align="left" width="50%">
            <table>
                <tr>
                    <td>Laporan</td>
                    <td width="10">:</td>
                    <td>Penelitian</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>:</td>
                    <td>{{$keterangan['periode']}}</td>
                </tr>
            </table>
        </td>
        <td align="right">
            <table>
                <tr>
                    <td>Fakultas</td>
                    <td width="10">:</td>
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
        </td>
    </tr>
</table>
@endsection

@section('content')
<table width="100%" class="table-data" border="1">
    <thead>
        <tr>
            <th class="align-middle text-center" width="45">No</th>
            <th class="align-middle text-center" width="210">Nama Dosen</th>
            @if(!$keterangan['prodi'])
            <th class="align-middle text-center" width="125">Program Studi</th>
            @endif
            <th class="align-middle text-center">Judul Penelitian</th>
            <th class="align-middle text-center" width="100">Tahun</th>
            <th class="align-middle text-center" width="120">Sumber Biaya</th>
            <th class="align-middle text-center" width="100">Biaya</th>
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
            <td class="align-middle text-center">{{ $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester }}</td>
            <td>{{ $d->sumber_biaya ?? '-' }}</td>
            <td>{{ rupiah($d->jumlah_biaya) ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('footer')
<table width="100%">
    <tr>
        <td align="center" nowrap="" width="50%">
            @if($keterangan['prodi'])
            <br>
            Ketua Program Studi
            <br><br><br><br><br><br>
            <u>{{$ttd['kaprodi']['nama']}}</u><br>
            NIP. {{$ttd['kaprodi']['nip']}}
            @endif
        </td>
        <td align="center" nowrap="">
            Gorontalo, {{$keterangan['disahkan']}}
            <br>
            Ketua Jurusan
            <br><br><br><br><br><br>
            <u>{{$ttd['kajur']['nama']}}</u><br>
            NIP. {{$ttd['kajur']['nip']}}
        </td>
    </tr>
</table>
@endsection

@push('custom-css')
@endpush

