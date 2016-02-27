@extends('_default.modals.modal')
@section('title')
@parent
{{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
w400
@endsection
@section('content')
<div class="panel-default">
    <form class="bs-example form-horizontal" id="ajax-form" data-action="{{URL::to('/profile/edit/password')}}" autocomplete="off">
        <div class="form-group">
            <div class="col-sm-12 padder">
                <label>{{\Lang::get('/common/general.current_password')}}</label>
                <input type="password" name="current_password" placeholder="{{\Lang::get('/common/general.current_password')}}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <label>{{\Lang::get('/common/general.password')}}</label>
                <input type="password" name="password" placeholder="{{\Lang::get('/common/general.password')}}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <label>{{\Lang::get('/common/general.password_confirmation')}}</label>
                <input type="password" name="password_confirmation" placeholder="{{\Lang::get('/common/general.password_confirmation')}}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <button class="btn col-sm-12 btn-success">{{\Lang::get('/common/button.update')}}</button>
            </div>
        </div>
    </form>
</div>
@endsection
@section('css')
<style>
.w400{width:400px}
</style>
@endsection
@section('js')
    @include('Sections\AreaManagers\Profile::profile-partials.js_for_modal_edit')
@endsection