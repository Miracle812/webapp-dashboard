@extends('_front.layouts.locked')
@section('content')

<div class="modal-over">
    <div class="modal-center  fadeInUp text-center" style="width:200px;margin:-80px 0 0 -100px;">
        <div class="thumb-md"><img src="{{$user->avatar()}}" class="img-circle b-a b-light b-3x"></div>
        <p class="text-white h4 m-t m-b">{{$user->fullname()}}</p>
        <form action="/locked" id="locked" method="post" autocomplete="off" class="" style="display:block">
            <div class="input-group">
                  <input type="password" name="password" class="form-control text-sm btn-rounded" placeholder="Enter pwd to continue">
                  <span class="input-group-btn">
                    <button class="btn btn-success btn-rounded" type="submit"><i class="fa fa-arrow-right"></i></button>
                  </span>
            </div>
        </form>
        @if ($message = Session::get('error'))
        <span class="text-navitas">{{$message}}</span>
        @endif
        <div class="m-t text-white">
        Unlock or <a class="text-default" href="/logout">Logout</a>
        </div>
    </div>
</div>
@endsection
@section('css')
<style>
.modal-center{top:20%}
</style>
@endsection
