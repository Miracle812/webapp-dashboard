@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <div class="panel-action"><i class="material-icons md-color-green-600">&#xe88f;</i> <span class="small">{{\Lang::get('/common/general.unit_ident')}}:</span> <span class="font-bold text-success">{{$currentUnit->identifier}}</span></div>
            </h2>
            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>
                <div class="md-card-content">
                    <table class="uk-table uk-table-valign-middle">
                        <thead>
                        <tr>
                            <th width="30">{{\Lang::get('/common/general.id')}}</th>
                            <th>{{\Lang::get('/common/general.name')}}</th>
                            <th>{{\Lang::get('/common/general.added')}}</th>
                            <th class="uk-text-center">{{\Lang::get('/common/general.status')}}</th>
                            <th class="uk-text-center">{{\Lang::get('/common/general.enabled')}}</th>
                            <th width="114" class="uk-text-center">{{\Lang::get('/common/general.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($probes->count()): $i=1; foreach ($probes as $probe):?>
                            <tr>
                                <td><span class="label bg-light">{{$i}}</span></td>
                                <td>{{$probe->name}}</td>
                                <td>{{$probe->date()}}</td>
                                <td class="uk-text-center">
                                    @if($probe->status=='create') 
                                    <i class="material-icons uk-text-danger" data-toggle="tooltip" title="{{\Lang::get('/common/general.connection_process')}}">settings</i>
                                    <span class="uk-text-danger font-bold">PIN: {{$probe->pin}} </span>
                                    @else
                                    <i class="material-icons uk-text-success" data-toggle="tooltip" title="{{\Lang::get('/common/general.ready_to_work')}}">check</i>
                                    @endif
                                </td>
                                <td class="uk-text-center">
                                    @if($probe->status!=='create')
                                    <a class="md-btn md-btn-small md-btn-action md-btn-wave-light waves-effect waves-button waves-light @if($probe->active) md-btn-success @else md-btn-danger @endif"
                                        data-original-title="@if($probe->active) {{\Lang::get('/common/general.enabled')}} @else {{\Lang::get('/common/general.disabled')}} @endif"
                                        data-toggle="ajaxActivate"
                                        href="{{URL::to("/probes/devices/edit/activate/$probe->id")}}">
                                        <i class="material-icons">@if($probe->active) check @else close @endif</i>
                                    </a>
                                    @endif
                                </td>
                                <td class="uk-text-center">
                                    <div class="">
                                        <a class="md-btn md-btn-small md-btn-action md-btn-wave-light waves-effect waves-button waves-light" data-uk-tooltip title="Edit" href="{{URL::to("probes/devices/edit/$probe->id")}}">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="javascript:;" data-modal="ajaxConfirmDelete" data-uk-tooltip title="Delete" class="md-btn md-btn-small md-btn-danger md-btn-action md-btn-wave-light waves-effect waves-button waves-light" data-action="{{URL::to("probes/devices/delete/$probe->id")}}">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++; endforeach; else: ?>
                            <tr>
                                <td colspan="6">
                                    @include('_default.no_data')
                                </td>
                            </tr>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection