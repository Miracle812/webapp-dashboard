@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
            <a class="btn btn-green" href="{{URL::to("/health-questionnaires/submitted-list")}}"><i class="fa fa-search"></i> {{Lang::get('common/general.datatable')}} </a>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <div class="panel-body">
                    <?php $limit = 4; ?>
                    <?php $sum = $staff->count() ?>
                    <?php
                    $rows = $sum/$limit;
                    $rows = (($rows * $limit) < $sum) ? ($rows +1) : $rows;
                    ?>
                    @for($i=0;$i<$rows;$i++)
                        <div class="row padder-v">
                            @foreach($staff->skip($i * $limit)->take($limit)->get() as $row)
                                <div class="col-sm-3 pull-left m-b">
                                    <a href="{{URL::to('/health-questionnaires/staff-list/'.$row->id)}}">
                                <span class="avatar">
                                    <img alt="{{$row->name}} {{$row->surname}}" class="" src="{{$row->avatar()}}" >
                                    <span style="display: inline-block;" class="badge badge-sm up bg-danger count h3">{{$row->health_questionnaires->count()}}</span>
                                </span>
                                <span class="block m-t text-center ">
                                    <strong class="font-bold text-lt"> {{$row->first_name}} {{$row->surname}}</strong>
                                    <span class="text-muted block">{{Lang::get('common/general.role')}}:  {{$row->role}}</span>
                                </span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endfor
                    <div class="clearfix"></div>
                </div>
            </section>
        </div>
    </div>
@endsection