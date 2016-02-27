@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    @include('Sections\Admins\FormsManager::common.partials.create_action')
    <div class="clearfix"></div>
@endsection
@section('css')
    @include('Sections\Admins\FormsManager::common.partials.create_css')
@endsection
@section('js')
    @include('Sections\Admins\FormsManager::common.partials.create_js')
@endsection