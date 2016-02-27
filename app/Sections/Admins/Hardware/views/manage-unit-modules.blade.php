<?php $modulesArr = is_array($modules) ? $modules : []; ?>
@extends('_admin.layouts.admin')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>Clients</h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Clients: {{$hq->name}}
                </header>
                <div class="panel-body">
                    <h4>Client Modules List: @if(!is_array($modules)) not created (uses all available modules) @else created @endif </h4>
                    <form class="form-horizontal" method="post">
                        @if($perms = $role->perms)
                        @foreach($perms as $perm)
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label class="switch switch-reverse">
                                        <input type="checkbox" @if(in_array($perm->id,$modulesArr)) checked @endif name="modules[]" value="{{$perm->id}}" >
                                        <span></span>
                                    </label>
                                </div>
                                <label class="col-sm-10">{{$perm->display_name}}</label>
                            </div>
                        @endforeach
                        @endif

                            <div class="form-group">
                                <div class="modal-footer">
                                    @if(is_array($modules))
                                        <a href="{{URL::to('/headquarters/delete/'.$hq->id.'/modules-list')}}" class="btn btn-lg btn-success">{{\Lang::get('/common/button.delete')}} </a>
                                    @endif
                                    <button class="btn btn-lg btn-success">
                                        @if(is_array($modules)) {{\Lang::get('/common/button.update')}}
                                        @else {{\Lang::get('/common/button.create')}}
                                        @endif
                                    </button>
                                </div>
                            </div>
                    </form>
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