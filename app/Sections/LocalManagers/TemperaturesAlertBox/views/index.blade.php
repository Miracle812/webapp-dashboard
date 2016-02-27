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
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/temperatures-alert-box/create/folder")}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}}</a>
                </span>

            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
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
                                        <th>{{Lang::get('common/general.folder_name')}}</th>
                                        <th>{{Lang::get('common/general.areas')}}</th>
                                        <th width="130" class="uk-text-center">{{Lang::get('common/general.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($folders->count()): $i=1; foreach ($folders as $folder):?>
                                <?php $childs = $folder->childs()->count(); ?>
                                    <tr>
                                        <td><span class="label bg-light">{{$i}}</span></td>
                                        <td>{{$folder->date()}}</td>
                                        <td>

                                        <a href="{{URL::to("/temperatures-alert-box/folder/$folder->id")}}" class="md-btn md-bg-grey-800 md-color-white md-btn-fullwidth md-btn-small md-btn-wave-light waves-effect waves-button waves-light">
                                            <span class="uk-text-left"><i class="fa fa-link m-r"></i> {{$folder->name}}</span>
                                        </a>

                                        </td>
                                        <td class="">
                                            <a class="md-btn md-bg-grey-800 md-color-white uk-width-1-1 md-btn-small md-btn-action md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/temperatures-alert-box/folder/$folder->id")}}">
                                                {{$childs}}
                                            </a>
                                        </td>
                                        <td class="uk-text-center">
                                            <div class="">
                                                {{ \HTML::mdOwnButton($folder->id,'temperatures-alert-box','create/area','add','md-btn-primary md-btn-small md-btn-action', 'Add Alert to this Folder'); }}
                                                {{ \HTML::mdOwnButton($folder->id,'temperatures-alert-box','edit','edit','md-btn-small md-btn-action', 'Edit Folder'); }}
                                                {{ \HTML::mdOwnButton($folder->id,'temperatures-alert-box','delete/folder','clear','md-btn-danger md-btn-small md-btn-action', 'Delete'); }}
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

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection