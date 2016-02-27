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
    <form class="bs-example form-horizontal" id="ajax-form" data-action="{{URL::to('/profile/edit/general')}}" autocomplete="off">
        <div class="form-group">
            <div class="col-sm-4">
                <label>{{\Lang::get('/common/general.timezone')}}</label>
            </div>
            <div class="col-sm-8">
                {{Form::select('timezone', $timezonesArray, $currentUser->timezone, ['class'=>'form-control'])}}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4">
                <label>{{\Lang::get('/common/general.username')}}</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="username" placeholder="{{\Lang::get('/common/general.username')}}" class="form-control" value="{{$currentUser->username}}" >
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-4">
               <label>{{\Lang::get('/common/general.first_name')}}</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="first_name" placeholder="{{\Lang::get('/common/general.first_name')}}" class="form-control" value="{{$currentUser->first_name}}" >
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label>{{\Lang::get('/common/general.surname')}}</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="surname" placeholder="{{\Lang::get('/common/general.surname')}}" class="form-control" value="{{$currentUser->surname}}" >
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4">
                <label>{{\Lang::get('/common/general.email')}}</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="email" placeholder="{{\Lang::get('/common/general.email')}}" class="form-control" value="{{$currentUser->email}}" >
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4">
                <label>{{\Lang::get('/common/general.phone')}}</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="phone" placeholder="{{\Lang::get('/common/general.phone')}}" class="form-control" value="{{$currentUser->phone}}" >
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
@include('Sections\Admins\Profile::partials.js_for_modal_edit')
@endsection