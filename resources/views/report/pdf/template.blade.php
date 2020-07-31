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
<body>
    <table class="kop" align="center" width="100%">
        <tr>
            <td>
                @include('report.pdf.kop')
            </td>
        </tr>
    </table>
    <table class="header" width="100%">
        <tr>
            <td>
                @yield('header')
            </td>
        </tr>
    </table>
    <table class="content" width="100%">
        <tr>
            <td>
                @yield('content')
            </td>
        </tr>
    </table>
    <table class="footer" width="100%">
        <tr>
            <td>
                @yield('footer')
            </td>
        </tr>
    </table>
</body>
</html>
