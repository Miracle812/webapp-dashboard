@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to('/trainings/')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <div class="panel-body">
                    <div class="panel-default">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="trainings" class="panel-group m-b">
                                    <div class="panel panel-default">
                                        @if($staff)
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="m-b">
                                                    <span class="avatar">
                                                        <img src="{{$staff->avatar()}}" class="" alt=" {{$staff->first_name}} {{$staff->surname}}">
                                                    </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <span class="h2 m-t">{{$staff->first_name}} {{$staff->surname}} </span>
                                                        <span class="text-muted block m-b m-t font-bold">{{Lang::get('common/general.position')}}: {{$staff->role}} </span>
                                                        <span class="text-muted block">{{Lang::get('common/general.email')}}: {{$staff->email}} </span>
                                                        <span class="text-muted block">{{Lang::get('common/general.phone')}}: {{$staff->phone}} </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if($training)
                                            <div class="padder b-t">
                                                <h4 class="text-muted">{{Lang::get('common/general.details')}}:</h4>
                                                <div class="row">
                                                    <div class="col-sm-8 b-r">
                                                        <span class="m-r"></span>
                                                        <span class="font-bold">{{$training -> name}}</span>
                                                        @if($training -> comments)
                                                            <div class="panel-body text-sm">{{$training -> comments}}</div>
                                                        @endif
                                                        <hr>
                                                        <div class="col-sm-12 h4 m-b">{{Lang::get('common/general.files')}}:</div>
                                                        <div class="panel-body">
                                                            {{HTML::FilesUploader('training_records',$training->id)}}
                                                        </div>
                                                    </div>
                                                    <?php $dateRefresh = strtotime ($training -> date_refresh);
                                                    $dateNow = strtotime ('now'); ?>
                                                    <div class="col-sm-4">
                                                        <div class="tooltip-link" data-placement="left" title="{{Lang::get('common/general.date_start')}}">
                                                            {{Lang::get('common/general.date_start')}}: <span class="font-bold">{{date('Y-m-d', strtotime($training->date_start))}}</span>
                                                        </div>
                                                        <div class="tooltip-link " data-placement="left"  title="{{Lang::get('common/general.date_finish')}}">
                                                            {{Lang::get('common/general.date_finish')}}: <span class="font-bold">{{date('Y-m-d', $dateRefresh)}}</span>
                                                        </div>
                                                        <div class="tooltip-link " data-placement="left"  title="{{Lang::get('common/general.date_refresh')}}">
                                                        <span class="<?= $dateRefresh <= $dateNow ? 'text-danger' : 'text-success';?>">
                                                            {{Lang::get('common/general.date_refresh')}}: <span class="font-bold">{{date('Y-m-d', $dateRefresh)}} {{ $dateRefresh <= $dateNow ?  Lang::get('common/general.expired') : ''}}</span>
                                                        </span>
                                                        </div>
                                                        <div class="tooltip-link clear" data-placement="left"  title="{{Lang::get('common/general.date_expire')}}">
                                                            <?php $expDet = $training -> to_expire(); $expBullets = $training->expireBullets(); ?>
                                                            @if(is_array($expBullets))
                                                                <div>
                                                                    @foreach($expBullets as $bullet)
                                                                        <i class="fa fa-circle text-{{$bullet}}"></i>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                            @if(is_array($expDet))
                                                                <div class="text-muted text-xs">
                                                                    {{Lang::get('common/general.to_expire')}}: @if($expDet['weeks']) {{$expDet['weeks']}} {{Lang::get('common/general.weeks')}} / @endif  {{$expDet['days']}} {{Lang::get('common/general.days')}}.
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </section>
        </div>
    </div>
@endsection