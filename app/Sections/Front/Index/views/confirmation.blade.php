@extends('_front.layouts.login')
@section('content')

<div class="container aside-xl m-t-lg">
    <a class="block" href="/">
        <img alt="Navitas" style="width:100%" src="{{URL::to('assets/images/logo.png')}}">
    </a>
    <section class="">
        <header class="wrapper text-center">
        @if ( $type=='success' )
        <h4>Hello {{$user->fullname()}} !</h4>
        @endif
            <div class="alert @if ( $type=='error' ) alert-danger @else alert-success @endif">{{ $msg }}</div>
            @if ( $type=='success' )
            <a href="/login"><button class="btn btn-green ">Login</button></a>
            @endif
        </header>
    </section>
</div>
@endsection
