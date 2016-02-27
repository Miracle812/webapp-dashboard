<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> Navitas Report</title>
    <meta name="author" content="Navitas" />
    {{ Basset::show('default_layout_default.css') }}
</head>
<body>
<header class="box-shadow">
    <section class="container">
        <table style="width: 100%">
            <tbody>
            <tr>
                <td width="33%"><img src="<?= URL::to('assets/images/logo.png')?>" ></td>
                <td width="33%"></td>
                <td width="33%"><div style="text-align: right" class="text-navitas"> Date: <?=date('Y-m-d H:i', strtotime('now'))?></div></td>
            </tr>
            </tbody>
        </table>
    </section>
</header>
@yield('first-page')
<section class="container">
    <h4><span style="font-weight:bold; font-size:38px">Navichat Dialog</span></h4>
    <section id="content">
        <section class="w-f scrollable wrapper">
            <section class="chat-list">
                @if($messages && $messages->count())
                    @foreach($messages as $msg)
                        {{HTML::showNaviDialog($msg,false)}}
                    @endforeach
                @endif
            </section>
        </section>
    </section>
</section>
<footer>
    <section class="container">NAVITAS Digital Food Safety</section>
</footer>
</body>
</html>







