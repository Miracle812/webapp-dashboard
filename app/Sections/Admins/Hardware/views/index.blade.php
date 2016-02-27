@extends('_admin.layouts.admin')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>Hardware
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">

        <section class="panel panel-default">

            <div class="panel-body text-center">
                <form role="form" method="post" action="/hardware/set-client" class="form-inline">
                    <div class="checkbox m-l m-r-xs">
                        <label class="i-checks">
                            Select client/site:
                        </label>
                    </div>
                    <div class="form-group">
                        {{Form::select('client',(['all'=>'All Clients']+\Model\Headquarters::where('active',1)->lists('name','id')), \Session::get('selected-client'), ['class'=>'form-control','id'=>'select-clients'])}}
                    </div>
                    <div class="form-group" id="select-sites">
                        {{Form::select('site',(['all'=>'All Sites']), null, ['class'=>'form-control'])}}
                    </div>
                    <button class="btn btn-primary" type="submit">Select</button>
                </form>
            </div>

            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true">All Hardware</a></li>
                    <li class=""><a data-toggle="tab" href="#pods" aria-expanded="false">Pods</a></li>
                    <li class=""><a data-toggle="tab" href="#probes" aria-expanded="false">Probes</a></li>
                    <li class=""><a data-toggle="tab" href="#tablets" aria-expanded="false">Tablets</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div id="all" class="tab-pane active">
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                All Hardware
                            </header>
                            <div class="table-responsive">
                                <table class="table table-striped m-b-none dataTable small" id="dataTable" date-filter="false"  data-source="{{URL::to('/hardware/datatable/all')}}">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{Lang::get('common/general.name')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.client')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.site')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.id')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.voltage')}}/Lvl</th>
                                        <th class="text-center">Latest software</th>
                                        <th class="text-center">Temperature</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="clearfix"></div>
                            </div>
                        </section>
                    </div>
                    <div id="pods" class="tab-pane">
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                All Hardware
                            </header>
                            <div class="table-responsive">
                                <table class="table table-striped m-b-none dataTable small" id="dataTable" date-filter="false"  data-source="{{URL::to('/hardware/datatable/pods')}}">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{Lang::get('common/general.name')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.client')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.site')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.id')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.voltage')}}/Lvl</th>
                                        <th class="text-center">Latest software</th>
                                        <th class="text-center">Temperature</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="clearfix"></div>
                            </div>
                        </section>
                    </div>
                    <div id="probes" class="tab-pane">
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                All Hardware
                            </header>
                            <div class="table-responsive">
                                <table class="table table-striped m-b-none dataTable small" id="dataTable" date-filter="false"  data-source="{{URL::to('/hardware/datatable/probes')}}">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{Lang::get('common/general.name')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.client')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.site')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.id')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.voltage')}}/Lvl</th>
                                        <th class="text-center">Latest software</th>
                                        <th class="text-center">Temperature</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="clearfix"></div>
                            </div>
                        </section>
                    </div>
                    <div id="tablets" class="tab-pane ">
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                All Hardware
                            </header>
                            <div class="table-responsive">
                                <table class="table table-striped m-b-none dataTable small" id="dataTable" date-filter="false"  data-source="{{URL::to('/hardware/datatable/tablets')}}">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{Lang::get('common/general.name')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.client')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.site')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.id')}}</th>
                                        <th class="text-center">{{Lang::get('common/general.voltage')}}/Lvl</th>
                                        <th class="text-center">Latest software</th>
                                        <th class="text-center">Temperature</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="clearfix"></div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
{{ Basset::show('package_datatables.js') }}
    <script>
        var uploadSites = function($id){
            $html = $.get('/hardware/sites/'+$id, function(data){
                $('#select-sites').html(data);
            })
        };
        $(document).ready(function(){
            uploadSites($('#select-clients').val());
            $('#select-clients').on('change',function(e){
                e.preventDefault();
                uploadSites($(this).val());
            });
        })
    </script>
@endsection
@section('css')
{{ Basset::show('package_datatables.css') }}
@endsection
