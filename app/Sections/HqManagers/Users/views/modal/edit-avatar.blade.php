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
    <div class="col-sm-12 users-avatar center">
        <input type="file" class="uploadfile" id="uploadfile"/>
        <div class="newupload m-b">{{\Lang::get('/common/general.upload_avatar')}}</div>
        <div class="avatar"><img src="{{$user->avatar()}}"></div>
    </div>
    <div class="col-sm-12 center">
        <div class="avatar-data center" data-avatar='{{URL::to('/users/edit/avatar/'.$user->id)}}' data-width="320" data-height="320" data-section="users" data-type="avatar">
        <div class="example"></div>
    </div>
</div>
<div class="clearfix"></div>
@endsection
@section('css')
<style>
    .w400{width:400px}
</style>
{{ Basset::show('package_imagecrop.css') }}
@endsection
@section('js')
{{ Basset::show('package_imagecrop.js') }}
@endsection