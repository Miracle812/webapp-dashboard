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
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/trainings/create")}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/trainings/list")}}"><i class="material-icons">search</i> {{Lang::get('common/general.datatable')}} </a>
                </span>
            </h2>

            <div class="md-card">
                <div class="md-card-content">
                    <div class="uk-grid">

                    @if($staff)
                        <?php $limit = 4; ?>
                        <?php $sum = $staff->count() ?>
                        <?php
                        $rows = $sum/$limit;
                        $rows = (($rows * $limit) < $sum) ? ($rows +1) : $rows;
                        ?>
                        @for($i=0;$i<$rows;$i++)

                            @foreach($staff->skip($i * $limit)->take($limit)->get() as $row)
                                <div class="uk-width-medium-1-4 m-b">
                                    <div class="avatar">
                                        <a href="{{URL::to("/trainings/list/$row->id")}}">
                                            <img alt="{{$row->name}} {{$row->surname}}" class="" src="{{$row->avatar()}}" >
                                        </a>
                                        <span style="display: inline-block;" class="uk-badge uk-badge-notification uk-badge-danger">{{$row->trainingsRecords()->count()}}</span>
                                    </div>
                                    <p class="uk-text-center">
                                        <a href="{{URL::to("/trainings/list/$row->id")}}">
                                            <strong class="font-bold text-lt"> {{$row->first_name}} {{$row->surname}}</strong>
                                        </a>
                                        <span class="text-muted block">Role:  {{$row->role}}</span>
                                    </p>
                                </div>
                            @endforeach

                        @endfor
                    @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection