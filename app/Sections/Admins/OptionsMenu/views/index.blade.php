@extends('_admin.layouts.admin')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/options-menu/create/group')}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.create-group')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                {{$sectionName}} - {{$actionName}}
            </header>
            <div class="padder m-t">
                <!-- .nav-justified -->
                <section class="panel panel-default">
                    <header class="panel-heading bg-light">
                        <ul class="nav nav-tabs nav-justified">
                            <?php $i = 0; ?>
                            @foreach($threads as $thread)
                                <li @if($i == 0) class="active" @endif><a href="#{{$thread->identifier}}" data-toggle="tab">{{$thread->name}}</a></li>
                                <?php $i++; ?>
                            @endforeach
                        </ul>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <?php $i = 0; ?>
                            @foreach($threads as $thread)
                                <div class="tab-pane @if($i == 0) active @endif" id="{{$thread->identifier}}">
                                    @include('Sections\Admins\OptionsMenu::partials.items-list',compact('thread'))
                                </div>
                                <?php $i ++; ?>
                            @endforeach
                        </div>
                    </div>
                </section>
                <!-- / .nav-justified -->
                <div class="clearfix"></div>
            </div>
        </section>
    </div>
</div>
@endsection