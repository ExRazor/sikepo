@extends('report.pdf.template',compact('keterangan'))

@section('title','Data '.$keterangan['jenis'].' Periode '.$keterangan['periode'].' ('.$keterangan['disahkan'].')')

@section('header')
<h1 class="title">Data {{$keterangan['jenis']}}</h1>
@if($tampil['tipe'] == 'prodi')
<table style="width:100%">
    <tr>
        <td width="50%">
            <table>
                <tr>
                    <td>Laporan</td>
                    <td width="10">:</td>
                    <td>{{$keterangan['jenis'].' '.($keterangan['kelompok']=='Semua' ? null : $keterangan['kelompok'])}}</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>:</td>
                    <td>{{$keterangan['periode']}}</td>
                </tr>
            </table>
        </td>
        <td>
            <table align="right">
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
@else
<table style="width:100%">
    <tr>
        <td width="50%">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{$keterangan['nama_dosen']}}</td>
                </tr>
                <tr>
                    <td>NIDN</td>
                    <td>:</td>
                    <td>{{$keterangan['nidn_dosen']}}</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>:</td>
                    <td>{{$keterangan['periode']}}</td>
                </tr>
            </table>
        </td>
        <td>
            <table align="right">
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
                <tr>
                    <td>Program Studi</td>
                    <td>:</td>
                    <td>{{$keterangan['prodi']}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif
@endsection

@section('content')
    @switch($keterangan['jenis'])
        @case('Penelitian')
            @include('report.pdf.tridharma_dsn.penelitian')
        @break
        @case('Pengabdian')
            @include('report.pdf.tridharma_dsn.pengabdian')
        @break
        @case('Publikasi')
            @include('report.pdf.tridharma_dsn.publikasi')
        @break
        @case('Luaran')
            @include('report.pdf.tridharma_dsn.luaran')
        @break
    @endswitch
@endsection
