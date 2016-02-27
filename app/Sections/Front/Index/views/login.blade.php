@extends('_front.layouts.login')
@section('content')

<div class="container aside-xl m-t-lg">
    <a class="block" href="/">
        <img alt="Navitas" style="width:100%" src="{{URL::to('assets/images/logo.png')}}">
    </a>
    <section class="">
        <header class="wrapper text-center">

            @if ( Session::get('error') )
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            @if ( Session::get('notice') )
            <div class="alert">{{ Session::get('notice') }}</div>
            @endif
        </header>
        <form action="/login" method="post">
            <input type="hidden" name="_token2" value="<?= csrf_token() ?>"/>
            <div class="list-group">
                <div class="list-group-item">
                    <input type="text" name="email" placeholder="Please enter email or username" class="form-control no-border">
                </div>
                <div class="list-group-item">
                    <input type="password" name="password" placeholder="Password" class="form-control no-border">
                </div>
                <div class="checkbox m-b">
                    <label>
                        <input type="hidden" name="remember" value="0">
                        <input tabindex="4" type="checkbox" name="remember" id="remember" value="1"> Remember me
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-lg btn-green btn-block">Sign in</button>
            <div class="text-center m-t"><a href="#forgot_password" data-toggle="class:show"><small>Forgot password?</small></a></div>
        </form>
        <div class=" m-t animated hide" id="forgot_password">
            <form action="/forgot-password" method="post" id="for_pass_form">
                <div class="list-group">
                    <div class="list-group-item">
                        <input type="text" name="email" placeholder="Please enter email" class="form-control no-border">
                    </div>
                </div>
                <button type="submit" id="forgot_password" class="btn btn-lg btn-primary btn-block">Reset password</button>
            </form>
        </div>
    </section>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function(){
        $('form#for_pass_form').on('submit', function(e){
            var $form = $(this);
            e.preventDefault();
            $url = $form.attr('action');
            form_data = $form.serialize();
            $.ajax({
                type: "POST",
                url: $url,
                data: form_data,
                success:function(msg) {
                    if(msg.type == 'success'){
                        $form.parent().removeClass('show');
                    }
                }
            });
        });
    });
</script>
@endsection