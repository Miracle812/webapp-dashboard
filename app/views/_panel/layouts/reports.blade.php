<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> Navitas Report</title>
    <meta name="author" content="Navitas" />
    {{ Basset::show('default_layout_reports.css') }}
    <style>
        .box-shadow{background-color: #FFFFFF}
        .container{padding-top: 10px; padding-bottom: 15px;}
        .text-navitas{color: #f79546}
        table thead, footer {background-color: #f79546; color: #fff}
        .page-break {page-break-before: always;}
    </style>
</head>
<body>
    <header class="box-shadow">
        <section class="container">
            <table style="width: 100%">
                <tbody>
                <tr>
                    <td width="33%"><img src="{{URL::to('assets/images/logo.png')}}" ></td>
                    <td width="33%"><span style="font-weight:bold; font-size:38px">Report</span></td>
                    <td width="33%"><div style="text-align: right" class="text-navitas"> Date: <?=date('Y-m-d H:i', strtotime('now'))?></div></td>
                </tr>
                </tbody>
            </table>
        </section>
    </header>
    @yield('first-page')
    <section class="container">
        <section id="content">
            @yield('content')
        </section>
    </section>
    <footer>
        <section class="container">NAVITAS Digital Food Safety</section>
    </footer>
</body>
</html>