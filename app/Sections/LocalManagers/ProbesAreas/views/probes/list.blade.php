@extends('_panel.layouts.panel')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
           <!--<a class="btn btn-green" href="{{URL::to("/probes/areas/list")}}"><i class="material-icons">search</i> {{Lang::get('common/general.datatable')}} </a>-->
        </span>
        </h3>
    </div>
@include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <table class="table table-striped m-b-none">
                    <thead>
                        <tr>
                            <th width="40">{{\Lang::get('/common/general.id')}}</th>
                            <th>{{\Lang::get('/common/general.name')}}</th>
                            <th width="114" class="text-center">{{\Lang::get('/common/general.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php if($areas->count()): $i=1; foreach ($areas as $area):?>
                    <tr>
                        <td>
                            <span class="label bg-light">{{$i}}</span>
                        </td>
                        <td>
                            {{$area->name}}
                        </td>
                        <td class="text-right text-center">
                            <div class="btn-group">
                                <span>
                                <a class="btn btn-rounded btn-sm btn-icon btn-default tooltip-link font-bold" href="{{URL::to('/probes/areas/edit/'.$area->id)}}">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                </span>

                            </div>
                        </td>
                    </tr>
                <?php $i++; endforeach; else: ?>
                        <tr>
                            <td colspan="3">
                                @include('Sections\LocalManagers\ProbesAreas::partials.no_data')
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