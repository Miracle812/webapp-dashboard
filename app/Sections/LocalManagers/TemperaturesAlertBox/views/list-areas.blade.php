@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom clearfix">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/temperatures-alert-box/create/area/$parent->id")}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}}</a>
                </span>

            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} Folder - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <div class="uk-grid">
                        <div class="uk-width-1-1">

                            <table class="uk-table uk-table-valign-middle" id="dataTable">
                                <thead>
                                    <tr>
                                        <th width="40">{{Lang::get('common/general.id')}}</th>
                                        <th>{{Lang::get('common/general.created')}}</th>
                                        <th>{{Lang::get('common/general.area_name')}}</th>
                                        <th>{{Lang::get('common/general.group_area')}}</th>
                                        <th width="114" class="uk-text-center">{{Lang::get('common/general.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($areas->count()): $i=1; foreach ($areas as $area):?>
                                    <tr>
                                        <td><span class="label bg-light">{{$i}}</span></td>
                                        <td>{{$area->date()}}</td>
                                        <td>
                                        <span class="uk-text-left"> {{$area->name}}</span>
                                        </td>
                                        <td>
                                        @if($targetArea = $area->area)
                                                @if($targetArea->group =='probes')
                                                <span class="uk-text-left">{{ucfirst($targetArea->group)}} / {{$targetArea->name}}</span>
                                                @else
                                                <span class="uk-text-left">{{ucfirst($targetArea->group)}} / {{implode($targetArea->getParentsNames(),' / ')}} / {{$targetArea->name}}</span>
                                                @endif
                                        @endif
                                        </td>
                                        <td class="uk-text-center">
                                            <div class="">
                                                {{ \HTML::mdOwnButton($area->id,'temperatures-alert-box','edit','edit','md-btn-default md-btn-small md-btn-action'); }}
                                                {{ \HTML::mdOwnButton($area->id,'temperatures-alert-box','delete/area','delete','md-btn-danger md-btn-small md-btn-action'); }}
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

        </div>
    </div>
@endsection