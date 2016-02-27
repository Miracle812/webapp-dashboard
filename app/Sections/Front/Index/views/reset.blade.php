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
        <form action="/reset" id="reset_pass_form" method="post" autocomplete="off">
            <input type="hidden" value="{{$token}}" name="token">
            <h3>Reset Password</h3>
            <div class="list-group">
                <div class="list-group-item">
                    <input type="password" name="password" placeholder="Enter New Password" class="form-control no-border">
                </div>
                <div class="list-group-item">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control no-border">
                </div>
            </div>
            <button type="submit" class="btn btn-lg btn-green btn-block">Continue</button>
        </form>
    </section>
</div>
@endsection