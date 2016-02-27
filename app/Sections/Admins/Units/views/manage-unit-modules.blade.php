<?php $panelPerms = $panelRole->perms; ?>
<?php $modulesHqArr = is_array($modulesHq) ? $modulesHq : [] ?>
<?php $modulesUnitArr = is_array($modulesUnit) ? $modulesUnit : $modulesHqArr ?>
@extends('_admin.layouts.admin')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}</h3>

    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">
                    Unit: {{$unit->name}} / HQ: {{$unit->headquarter->name}}
                </header>
                <div class="panel-body">
                    <div class="col-sm-6">
                        <h4>Unit Modules List: @if(!is_array($modulesUnit)) not created (uses Hq list) @else created @endif </h4>

                        <form class="form-horizontal" method="post">
                            @foreach($panelPerms as $perm)
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label class="switch switch-reverse">
                                            <input type="checkbox" @if(in_array($perm->id,$modulesUnitArr)) checked @endif name="modules[]" value="{{$perm->id}}" >
                                            <span></span>
                                        </label>
                                    </div>
                                    <label class="col-sm-8">{{$perm->display_name}}</label>
                                </div>
                            @endforeach



                            <div class="form-group">
                                <div class="modal-footer">
                                    @if(is_array($modulesUnit))
                                        <a href="{{URL::to('/units/delete/'.$unit->id.'/modules-list')}}" class="btn btn-lg btn-success">{{\Lang::get('/common/button.delete')}} </a>
                                    @endif
                                    <button class="btn btn-lg btn-success">
                                        @if(is_array($modulesUnit)) {{\Lang::get('/common/button.update')}}
                                        @else {{\Lang::get('/common/button.create')}}
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-6">
                        <h4>Headquarter Modules List (<a href="/headquarters/manage-unit-modules/{{$unit->headquarter->id}}">Manage</a>)</h4>
                        @foreach($panelPerms as $perm)
                            <div class="@if(in_array($perm->id,$modulesHqArr)) text-danger @else text-success @endif"> {{$perm->display_name}}</div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('js')

@endsection
@section('css')
<style>
    .switch.switch-reverse input:checked + span {
        background-color: red;
    }
    .switch.switch-reverse span {
        background-color: #1aae88;
    }
</style>
@endsection