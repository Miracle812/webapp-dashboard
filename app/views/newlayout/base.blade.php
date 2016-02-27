<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <title>@section('title') Navitas Digital Food Safety @show</title>
    @section('meta_keywords')
        <meta name="keywords" content="Navitas Digital Food Safety."/>
    @show
    @section('meta_author')
        <meta name="author" content="Maciej Niesterowicz"/>
    @show
    @section('meta_description')
        <meta name="description" content="Navitas Digital Food Safety"/>
    @show
    <link rel="icon" type="image/png" href="newassets/img/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="newassets/img/favicon-32x32.png" sizes="32x32">

    <link rel="shortcut icon" href="{{{ asset('/assets/images/favicon.ico') }}}">
    @yield('styles-top')
    <!-- uikit -->
    <link rel="stylesheet" href="{{ asset('newassets/packages/uikit/css/uikit.almost-flat.min.css')}}" media="all">
    <!--Bower-->
    <link href="{{ asset('newassets/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newassets/css/navitas_theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newassets/packages/weather-icons/css/weather-icons.min.css') }}" rel="stylesheet">
    <!-- matchMedia polyfill for testing media queries in JS -->
    <!--[if lte IE 9]>
    <script type="text/javascript" src="{{ asset('newassets/packages/matchMedia/matchMedia.js')}}')}}"></script>
    <script type="text/javascript" src="{{ asset('newassets/packages/matchMedia/matchMedia.addListener.js')}}')}}"></script>
    <![endif]-->
    @yield('styles')
</head>
<body class="sidebar_main_open sidebar_main_swipe navitas_theme @yield('body-class')">
@include('newlayout.partials.header_main')
@include('newlayout.partials.main_sidebar')

@include('newlayout.notifications')
@yield('content')
<footer id="footer">
    &copy; 2010-{{\Carbon::now()->year}} <a href="#">Navitas LTD</a>, All rights reserved.
</footer>
{{--
@include('partials.footer')
--}}
<script>
    WebFontConfig = {
        google: {
            families: [
                'Source+Code+Pro:400,700:latin',
                'Roboto:400,300,500,700,400italic:latin'
            ]
        }
    };
    (function() {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
        '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
    })();
</script>

<script src="/newassets/packages/handlebars/handlebars.min.js"></script>
<script src="/newassets/js/custom/handlebars_helpers.min.js"></script>
<script src="/newassets/js/custom/handlebars.js"></script>

<script src="{{ asset('newassets/js/common.min.js')}}"></script>
<!-- uikit functions -->
<script src="{{ asset('newassets/js/uikit_custom.min.js')}}"></script>
<!-- altair common functions/helpers -->
<script src="{{ asset('newassets/js/concat_common.js')}}"></script>
<!-- firebase -->
<script src="https://cdn.firebase.com/js/client/2.3.2/firebase.js"></script>
<script>
var _env = '{{App::environment()}}';
var firebase_env = new Firebase("https://navitest.firebaseio.com/"+_env+"/");
var auth_user_id = '{{\Auth::user()->id}}';
</script>
<script src="/newassets/js/custom/firebase.js"></script>

<!-- flag icons -->
<script>
    $(function() {
        altair_helpers.retina_images();
        if(Modernizr.touch) {
            FastClick.attach(document.body);
        }
    });
</script>
@yield('scripts')
<script id="append_threads_last_message" type="text/x-handlebars-template">
    <li thread_id="@{{thread_id}}">
        <div class="md-list-addon-element">
            <img alt="" src="@{{fbUserData user_id 'avatar'}}" class="md-user-image md-list-addon-avatar">
        </div>
        <div class="md-list-content">
            <span class="md-list-heading"><a href="/chat/@{{thread_id}}">@{{message}}</a></span>
            <span class="uk-text-small uk-text-muted">@{{created_at}}  @{{#ifIsMy user_id}} @{{else}} (@{{fbUserData user_id 'full_name'}}) @{{/ifIsMy}} </span>
        </div>
    </li>
</script>
</body>
</html>