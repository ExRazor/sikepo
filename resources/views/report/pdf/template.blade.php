<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="{{ asset ('assets/lib') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset ('assets') }}/report.css" rel="stylesheet">
    @stack('custom-css')
</head>
<body id="cetak" onload="window.print()">
    <div class="row kop">
        <div class="col-12">
            @include('report.pdf.kop')
        </div>
    </div>
    <div class="row header">
        <div class="col-12">
            @yield('header')
        </div>
    </div>
    <div class="row content">
        <div class="col-12">
            @yield('content')
        </div>
    </div>
    <div class="row footer">
        <div class="col-12">
            <table style="width:100%">
                <tr>
                    <td align="center" nowrap="" width="50%">
                        @if($keterangan['prodi'])
                        <br>
                            @if($ttd['kaprodi']['exist'])
                            Ketua Program Studi
                            <br><br><br><br><br><br>
                            <u>{{$ttd['kaprodi']['nama']}}</u><br>
                            {{$ttd['kaprodi']['nip']}}
                            @endif
                        @endif
                    </td>
                    <td align="center" nowrap="">
                        Gorontalo, {{$keterangan['disahkan']}}
                        <br>
                        @if($ttd['kajur']['exist'])
                        Ketua Jurusan
                        <br><br><br><br><br><br>
                        <u>{{$ttd['kajur']['nama']}}</u><br>
                        {{$ttd['kajur']['nip']}}
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
