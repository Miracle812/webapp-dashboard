@extends('_panel.layouts.panel')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to("/new-cleaning-schedule")}}"><i class="material-icons">search</i> {{Lang::get('common/general.calendar')}} </a>
           <a class="btn btn-green" href="{{URL::to("/new-cleaning-schedule/submitted")}}"><i class="material-icons">list</i> {{Lang::get('common/general.submitted')}} </a>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <table class="table table-striped m-b-none">
                    <thead>
                    <tr><th class="h3">Select cleaning schedule task from list below:</th></tr>
                    </thead>
                    <tbody>
                    @include('_default.partials.forms_groups_list',['forms' => $unitForms,'generic'=>false])
                    @if($navitasForms->count())
                        <tr><td><a href="#generic_forms" data-toggle="collapse" ><div class="h3 text-primary font-bold">Navitas Generic Forms</div></a></td></tr>
                        <tr>
                            <td>
                                <div id="generic_forms" class="@if($unitForms->count()) collapsed collapse @endif">
                                    <table class="table table-striped">
                                        @include('_default.partials.forms_groups_list',['forms' => $navitasForms,'generic'=>true])
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection