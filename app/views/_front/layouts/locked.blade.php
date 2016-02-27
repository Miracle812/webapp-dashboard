<!DOCTYPE html>
<html lang="en" class="app">
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
</head>
<body class="">
    @yield('content')

<!-- footer -->
<footer id="footer">
    <div class="text-center padder">
        <p>
            <small class="text-white">Navitas Digital Food Safety &copy; 2014</small>
        </p>
    </div>
</footer>
<input type="hidden" name="_token" value="<?= csrf_token() ?>"/>
{{ Basset::show('default_layout_default.js') }}
@yield('js')
</body>
</html>