<?php
$backgrounds = Config::get('app.backgrounds');
$background  = $backgrounds[mt_rand(0, (count($backgrounds)-1))];

?>
<!DOCTYPE html>

<html lang="en">
<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        @section('title')
            Navitas under construction
        @show</title>
    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Short explanation about this website">
    <!-- END META -->

    <!-- BEGIN STYLESHEETS -->
    {{ Basset::show('error_layout.css') }}
    <link rel="icon" type="image/png" href="{{URL::to('/assets/images/favicon.ico')}}" />

    <!-- END STYLESHEETS -->
    {{ HTML::script('/assets/js/jquery.min.js') }}
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="http://www.codecovers.eu/assets/js/modules/boostbox/libs/utils/html5shiv.js?1401441990"></script>
    <script type="text/javascript" src="http://www.codecovers.eu/assets/js/modules/boostbox/libs/utils/respond.min.js?1401441990"></script>
    <![endif]-->
    <style>
        .login-body{
            background: url("{{URL::to($background)}}")  no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        .oops{
            font-size:60px;
            font-weight:900;
            padding-left:50px;
        }
        .box-head{
            top:0;
        }
    </style>
    <script>
        var $horg,$top;
        $resisi = function(){
            $line1 = $('#line1').outerHeight();
            $line2 = $('#line2').outerHeight();
            $line3 = $('#line3').outerHeight();
            $sum = $line1+$line2+$line3;
            if($sum>180){$('.box-body').height(($horg + ($sum-180)));}
            else{$('.box-body').height($horg);}
            $top = $('.box-body').offset().top;
            $('.box-head').css('top',$top-80);
        };
        $(document).ready(function(){
            $horg = $('.box-body').height();
            $top = $('.box-body').offset().top;
            $resisi();
            $(window).resize(function(){ $resisi();});
        });
    </script>
</head>
<body class="login-body">
<!-- START LOGIN BOX -->
<div class="box-type-login">
    <div class="box text-center">
        <div class="box-head">
            <h2 class="text-light text-white"><img width="200" src="{{URL::to('/assets/images/full-logo.png')}}"/></h2>
        </div>
        <div class="box-body box-centered style-inverse m-t">
            <h1 id="line1" class="text-light text-left oops uppercase alternate_gothic sm-fontsize-37">
                <div style="color:#f79546; margin-left: 10px;">under<span style="color:#FFFFFF; margin-left: 10px;"> construction! </span></div>
            </h1 >
            <p id="line2" class="text" style="color:#df8943;font-size: 20px;padding:0 50px;">The page that you are trying to access is <br>Under Construction</p>
            <p id="line3" class="text" style="margin-top:22px;font-size: 20px;padding:0 50px;">"Rome wasn't built in a day"</p>
        </div>
    </div>
</div>
</body>
</html>