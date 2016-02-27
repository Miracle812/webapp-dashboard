@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}

            <span class="pull-right">
           <a class="btn btn-default" href="{{ URL::previous() }}"><i class="fa fa-backward"></i> {{Lang::get('common/button.back')}} </a>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    {{$sectionName}} - {{$actionName}}
                    <span class="pull-right">
                </span>
                </header>
                <div class="panel-body">
                    <div class="row m-t b-b">
                        <div class="col-sm-4">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.created')}}:</label>
                                <span class="text-primary font-bold">{{$item->date_time()}}</span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.supplier')}}:</label>
                                <span class="text-primary font-bold">{{$item->supplier_name}}</span>
                            </div>
                        </div>

                    </div>

                    <div class="row m-t b-b">
                        <div class="col-sm-4">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.device')}}:</label>
                                <span class="text-primary font-bold">{{$item->device_name}}</span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.identifier')}}:</label>
                                <span class="text-primary font-bold">{{$item->device_identifier}}</span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.staff')}}:</label>
                                <span class="text-primary font-bold">{{$item->staff_name}}</span>
                            </div>
                        </div>

                    </div>
                    <div class="row m-t b-b">
                        <div class="col-sm-8">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.products')}}:</label>
                                <span class="text-primary font-bold">{{$item->products_name}}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.temperature')}}:</label>
                                <span class="text-primary font-bold">{{$item->temperature()}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t b-b">
                        <div class="col-sm-4">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.invoice_number')}}:</label>
                                <span class="text-primary font-bold">{{$item->invoice_number}}</span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.date_code_valid')}}:</label>
                                <span class="text-primary font-bold">{{$item->date_code_valid()}}</span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="row-2">
                                <label>{{Lang::get('common/general.package_accept')}}:</label>
                                <span class="text-primary font-bold">{{$item->package_accept()}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row m-t b-b">
                        <div class="col-sm-8">
                            <label>{{Lang::get('common/general.action')}}:</label>
                            @if($item->action_todo)
                                <h4>{{$item->action_todo}}</h4>
                            @else
                                <h4>{{Lang::get('common/general.not_set')}}</h4>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <label>{{Lang::get('common/general.compliant')}}:</label>
                            <span class="font-bold">{{$item->compliant()}}</span>

                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>
@endsection