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
    <form class="bs-example form-horizontal" id="ajax-form" data-action="{{URL::to('/users/edit/password/'.$user->id)}}" autocomplete="off">

        <div class="form-group">
            <div class="col-sm-12">
                <label>{{\Lang::get('/common/general.new_password')}}</label>
                <input type="password" name="password" placeholder="{{\Lang::get('/common/general.new_password')}}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <label>{{\Lang::get('/common/general.password_confirmation')}}</label>
                <input type="password" name="password_confirmation" placeholder="{{\Lang::get('/common/general.password_confirmation')}}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <div class="checkbox i-checks col-sm-12">
                <label>
                    <input id="show-hide-pass" type="checkbox" >
                    <i></i>
                    Show/Hide Password
                </label>
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
    <script>
        $(document).ready(function() {
            if (showHidePass = $('#show-hide-pass')) {
                showHidePass.on('change', function () {
                    $passinputs = $('[name=password], [name=password_confirmation]');
                    if ($(this).is(':checked')) {
                        $passinputs.attr('type', 'text');
                    } else {
                        $passinputs.attr('type', 'password');
                    }
                });
            }
        });

    </script>
    @include('Sections\AreaManagers\Users::modal.partials.js_for_modal_edit')
@endsection