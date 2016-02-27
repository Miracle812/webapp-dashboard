<!DOCTYPE html>
<html lang="en" class=" ">
<head>
    <meta charset="utf-8" />
    <title>
        @section('title')
        Navitas Manager
        @show
    </title>
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="keywords" content="@yield('keywords')" />
    <meta name="author" content="@yield('author')" />
    <meta name="description" content="@yield('description')" />
    <meta name="google-site-verification" content="">
    <meta name="DC.title" content="Project Name">
    <meta name="DC.subject" content="@yield('description')">
    <meta name="DC.creator" content="@yield('author')">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="{{URL::to('/assets/images/favicon.ico')}}" />
    <link rel="stylesheet" type="text/css" href="/newassets/packages/jquery-easyui-bower/themes/bootstrap/easyui.css">

    {{ Basset::show('default_layout_default.css') }}
    @yield('css')
    <!--[if lt IE 9]>
    {{ Basset::show('default_layout_default_ie.js') }}
    <![endif]-->
</head>
<body class="container">
    <section class="vbox">
     
        <section>
            <section class="hbox stretch">
                <!-- .aside -->
                <aside class="bg-dark aside-md hidden-print hidden-xs aside-minheight" id="nav">
                    @include('_admin.partials.left_side')
                </aside>
                <!-- /.aside -->
                <section id="content">
                	
                       <!-- /header -->
                        <header class="bg-navitas header header-md navbar navbar-fixed-top-xs box-shadow">
                            @include('_admin.partials.header')
                        </header>
                        <!-- header/ -->
                    
                
                    @include('_admin.partials.content')
                </section>
            </section>
        </section>
    </section>
    <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>
    {{ Basset::show('default_layout_default.js') }}
    @yield('js')
    <!--widgets files-->
    {{View::yieldContent('widgets-css')}}
    {{View::yieldContent('widgets-js')}}
    <!--/widgets files-->
    <script>
        $(document).ready(function(){
            navichatUpdater = setInterval(startNavichatUpdater, navichatInterval);
        });
    </script>
</body>
</html>