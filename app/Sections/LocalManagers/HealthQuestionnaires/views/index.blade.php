@extends('newlayout.base')
@section('title')
    @parent :: {{$sectionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/health-questionnaires/forms-list')}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/health-questionnaires/submitted')}}"><i class="material-icons">search</i> {{Lang::get('common/general.datatable')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">
                <div class="md-card-content">
                    <div class="uk-grid">
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
                                    <a href="{{URL::to('/health-questionnaires/staff/'.$row->id.'/list')}}">
                                        <img alt="{{$row->name}} {{$row->surname}}" class="" src="{{$row->avatar()}}" >
                                    </a>
                                    <span class="uk-badge uk-badge-notification uk-badge-danger">{{$row->health_questionnaires->count()}}</span>
                                </div>
                                <p class="uk-text-center">
                                    <a href="{{URL::to('/health-questionnaires/staff/'.$row->id.'/list')}}">
                                        <strong class="font-bold text-lt"> {{$row->first_name}} {{$row->surname}}</strong>
                                    </a><br />
                                    <span class="text-muted block">{{Lang::get('common/general.role')}}:  {{$row->role}}</span>
                                </p>
                            </div>
                        @endforeach
                    
                    @endfor
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection