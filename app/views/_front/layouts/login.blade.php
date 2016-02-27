<?php
$backgrounds = Config::get('app.backgrounds');
$background  = $backgrounds[mt_rand(0, (count($backgrounds)-1))];
?>
<!DOCTYPE html>
<?php $isMobile = (Agent::isMobile() || Agent::isTablet()); ?>

<html lang="en" @if( !$isMobile ) class="app" @endif>

<head>
    <meta charset="utf-8" />
    <title>Navitas Login</title>
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="description" content="navitas login" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="{{URL::to('/assets/images/favicon.ico')}}" />

    {{ Basset::show('default_layout_default.css') }}
    @yield('css')
    <!--[if lt IE 9]>
    {{ Basset::show('default_layout_default_ie.css') }}
    <![endif]-->
    @if( $isMobile )
        <style>
            section#content.login {margin: 0 auto 0;}
            .m-t-lg {margin-top: 0px}
        </style>
    @endif
    <style>
        .login-body{
            background: url("{{URL::to($background)}}")  no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    </style>
</head>
<body class="login-body">
<section id="content" class="login wrapper-md animated panel fadeInUp">
    @yield('content')
</section>
<!-- footer -->
<footer id="footer">
    <div class="text-center padder">
        <p>
            <small class="text-white">Navitas Digital Food Safety &copy; {{Carbon::now()->format('Y')}}</small>
        </p>
    </div>
</footer>
<input type="hidden" name="_token" value="<?= csrf_token() ?>"/>
{{ Basset::show('default_layout_default.js') }}
@yield('js')
</body>
</html>