@extends('newlayout.base')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/trainings/create")}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/trainings/')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
                </span>
            </h2>

            <div class="md-card">
                <div class="md-card-content">

                    @if($staff)
                    <div class="uk-grid">
                        <div class="uk-width-1-3">
                            <span class="avatar">
                                <img src="{{$staff->avatar()}}" class="" alt=" {{$staff->first_name}} {{$staff->surname}}">
                            </span>
                        </div>
                        <div class="uk-width-2-3">
                            <h3>{{$staff->first_name}} {{$staff->surname}} </h3>
                            <p class="uk-text-muted font-bold">{{Lang::get('common/general.position')}}: {{$staff->role}} </p>
                            <p class="uk-text-muted">{{Lang::get('common/general.email')}}: {{$staff->email}} </p>
                            <p class="uk-text-muted">{{Lang::get('common/general.phone')}}: {{$staff->phone}} </p>
                        </div>
                    </div>
                    @endif
                
                </div>

            </div>

            @if($training)
                
            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{Lang::get('common/general.details')}}:
                    </h3>
                </div>

                <div class="md-card-content">
                    <div class="uk-grid">
                        <div class="uk-width-2-3">

                            <h3 class="font-bold">{{$training -> name}}</h3>
                            @if($training -> comments)
                                <div class="panel-body uk-text-sm">{{$training -> comments}}</div>
                            @endif
                            <hr class="md-hr" />
                            <h3>{{Lang::get('common/general.files')}}:</h3>
                            <div class="panel-body">
                                {{HTML::FilesUploader('training_records',$training->id)}}
                            </div>

                        </div>

                        <div class="uk-width-1-3">
                            <?php $dateRefresh = strtotime ($training -> date_refresh);
                                  $dateNow = strtotime ('now'); ?>
                            <div class="uk-margin-small-bottom" data-uk-tooltip="{pos:'left'}" data-placement="left" title="{{Lang::get('common/general.date_start')}}">
                                {{Lang::get('common/general.date_start')}}: <span class="font-bold">{{\Carbon::createFromFormat('Y-m-d H:i:s', $training->date_start,'UTC')->timezone(\Auth::user()->timezone)->format('Y-m-d')}}</span>
                            </div>
                            <div class="uk-margin-small-bottom" data-uk-tooltip="{pos:'left'}" title="{{Lang::get('common/general.date_finish')}}">
                                    {{Lang::get('common/general.date_finish')}}: <span class="font-bold">{{\Carbon::createFromFormat('Y-m-d H:i:s', $training->date_finish,'UTC')->timezone(\Auth::user()->timezone)->format('Y-m-d')}}</span>
                            </div>
                            <div class="uk-margin-small-bottom" data-uk-tooltip="{pos:'left'}" title="{{Lang::get('common/general.date_refresh')}}">
                                <span class="<?= $dateRefresh <= $dateNow ? 'uk-text-danger' : 'uk-text-success';?>">
                                    {{Lang::get('common/general.date_refresh')}}: <span class="font-bold">{{\Carbon::createFromFormat('Y-m-d H:i:s', $training->date_refresh,'UTC')->timezone(\Auth::user()->timezone)->format('Y-m-d')}} {{ $dateRefresh <= $dateNow ?  Lang::get('common/general.expired') : ''}}</span>
                                </span>
                            </div>
                            <div class="uk-margin-small-bottom" data-uk-tooltip="{pos:'left'}" title="{{Lang::get('common/general.date_expire')}}">
                            <?php
                                $expDet = $training -> repository() -> toExpire();
                                $expBullets = $training-> repository() -> expireBullets();
                                ?>
                                @if(is_array($expBullets))
                                    <div>
                                    @foreach($expBullets as $bullet)
                                    <i class="material-icons uk-text-{{$bullet}}">lens</i>
                                    @endforeach
                                    </div>
                                @endif
                                @if(is_array($expDet))
                                <div class="uk-margin-small-top" class="uk-text-muted uk-text-xs">
                                {{Lang::get('common/general.to_expire')}}: @if($expDet['weeks']) {{$expDet['weeks']}} {{Lang::get('common/general.weeks')}} / @endif  {{$expDet['days']}} {{Lang::get('common/general.days')}}.
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            @endif

        </div>

    </div>
@endsection