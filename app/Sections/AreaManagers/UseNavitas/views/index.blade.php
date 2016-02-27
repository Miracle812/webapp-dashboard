@extends('_manager.layouts.manager')
<?php $dateArray = ['today', 'last-week', 'last-month']; ?>

@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}

        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                        <i class=" fa fa-exchange "></i>
                        <span class="font-bold">Use Navitas As:</span>
                </header>

                <section class="panel panel-default">
                    <div class="panel-body">
                        <?php $limit = 4; ?>
                        <?php $sum = $users->count() ?>
                        <?php
                        $rows = $sum/$limit;
                        $rows = (($rows * $limit) < $sum) ? ($rows +1) : $rows;
                        ?>
                        @for($i=0;$i<$rows;$i++)
                            <div class="row padder-v">
                                @foreach($users->skip($i * $limit)->take($limit)->get() as $row)
                                    <div class="col-sm-3 pull-left m-b">
                                        <a href="{{URL::to('/usenavitas/log-as/'.$row->id)}}">
                                            <span class="avatar">
                                                <img alt="{{$row->name}} {{$row->surname}}" class="" src="{{$row->avatar()}}" >
                                            </span>
                                            <span class="block m-t text-center ">
                                                <strong class="font-bold text-lt"> {{$row->first_name}} {{$row->surname}}</strong>
                                                <span class="text-muted block">{{Lang::get('common/general.role')}}:  {{Lang::get('common/roles.'.$row->role()->name)}}</span>
                                                <span class="text-muted block">{{Lang::get('common/general.unit')}}:  {{$row->unit()->name}}</span>
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endfor
                        <div class="clearfix"></div>
                    </div>
                </section>
            </section>
        </div>
    </div>
@endsection
