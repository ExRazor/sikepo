@extends('report.pdf.template',compact('keterangan'))

@section('title','Data '.$keterangan['jenis'].' Periode '.$keterangan['periode'].' ('.$keterangan['disahkan'].')')

@section('header')
<h1 class="title">Data {{$keterangan['jenis']}}</h1>
<table style="width:100%">
    <tr>
        <td width="50%">
            <table>
                <tr>
                    <td>Laporan</td>
                    <td width="10">:</td>
                    <td>{{$keterangan['jenis']}}</td>
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
