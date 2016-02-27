@extends('_visitor.layouts.visitor')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to('/navinotes')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="panel-default">

                    @if($navinote)
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php $dateNow = strtotime ('now'); ?>
                                    <div class="col-sm-12 text-muted small">
                                        <span class="tooltip-link" data-placement="top" title="{{Lang::get('common/general.date_start')}}">
                                            {{Lang::get('common/general.date_start')}}: <span class="">{{$navinote->start}}</span>
                                        </span>
                                        <span class="tooltip-link m-l" data-placement="top"  title="{{Lang::get('common/general.date_end')}}">
                                                {{Lang::get('common/general.date_end')}}: <span class="">{{$navinote->end}}</span>
                                        </span>
                                    </div>

                                    <H4 class="panel-body">{{$navinote -> name}}</H4>
                                    @if($navinote -> description)
                                        <div class="panel-body">{{$navinote -> description}}</div>
                                    @endif

                                    <?php $files = $navinote -> files?>

                                    <hr>
                                    <div class="col-sm-12 h4 m-b">{{Lang::get('common/general.files')}}:</div>
                                    <div class="panel-body">
                                        {{HTML::FilesUploader('navinotes',$navinote->id)}}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    @endif
                    </div>

                <div class="clearfix"></div>
        </section>
    </div>
</div>
@endsection