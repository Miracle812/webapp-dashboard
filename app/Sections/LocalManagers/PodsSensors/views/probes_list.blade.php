@extends('_panel.layouts.panel')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <div class="pull-right "><i class="fa fa-info btn-sm btn-green m-r"></i> <span class="small">{{\Lang::get('/common/general.unit_ident')}}:</span> <span class="font-bold text-success">{{$currentUnit->identifier}}</span></div>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])

<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">{{$sectionName}} - {{$actionName}}</header>
            <table class="table table-striped m-b-none">
                <thead>
                <tr>
                    <th width="40">{{\Lang::get('/common/general.id')}}</th>
                    <th>{{\Lang::get('/common/general.name')}}</th>
                    <th>{{\Lang::get('/common/general.added')}}</th>
                    <th class="text-center">{{\Lang::get('/common/general.status')}}</th>
                    <th class="text-center">{{\Lang::get('/common/general.enabled')}}</th>
                    <th width="114" class="text-center">{{\Lang::get('/common/general.action')}}</th>
                </tr>
                </thead>
                <tbody>
                <?php if($probes->count()): $i=1; foreach ($probes as $probe):?>
                    <tr>
                        <td><span class="label bg-light">{{$i}}</span></td>
                        <td>{{$probe->name}}</td>
                        <td>{{$probe->date()}}</td>
                        <td class="text-center"><i class=" fa @if($probe->status=='create') fa-gears text-danger @else  fa-check text-success @endif" data-toggle="tooltip" title="@if($probe->status=='create') {{\Lang::get('/common/general.connection_process')}} @else {{\Lang::get('/common/general.ready_to_work')}} @endif"></i></td>
                        <td class="text-center">
                            <a class="btn btn-xs  btn-icon tooltip-link @if($probe->active) bg-success @else bg-danger @endif"
                                data-original-title="@if($probe->active) {{\Lang::get('/common/general.enabled')}} @else {{\Lang::get('/common/general.disabled')}} @endif"
                                data-toggle="ajaxActivate"
                                href="{{URL::to("/pods/sensors/edit/activate/$probe->id")}}">
                                <i class="fa @if($probe->active) fa-check @else fa-times @endif "></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="">

                                <a class="btn btn-rounded btn-sm btn-icon btn-default tooltip-link font-bold" data-toggle="ajaxModal" title="Edit" href="{{URL::to("/pods/sensors/edit/$probe->id")}}">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                {{\HTML::mdOwnButton($probe->id,'pods/sensors','delete','clear','btn-danger')}}
                            </div>
                        </td>
                    </tr>
                    <?php $i++; endforeach; else: ?>
                    <tr>
                        <td colspan="3">
                            @include('_default.no_data')
                        </td>
                    </tr>
                <?php endif;?>
                </tbody>
            </table>
        </section>
    </div>
</div>
@endsection
@section('js')
<script> $('.tooltip-link').tooltip(); </script>
@endsection