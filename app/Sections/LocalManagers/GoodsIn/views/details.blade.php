@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom clearfix">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{ URL::previous() }}"><i class="fa fa-backward"></i> {{Lang::get('common/button.back')}} </a>
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/goods-in/create')}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a href="{{URL::to('/goods-in/edit/'.$item->id)}}"><i class="md-icon material-icons">edit</i></a>
                    </div>
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <div class="uk-grid">
                        <div class="uk-width-1-3">
                            <label>{{Lang::get('common/general.created')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->date_time()}}</span>
                        </div>

                        <div class="uk-width-2-3">
                            <label>{{Lang::get('common/general.supplier')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->supplier_name}}</span>
                        </div>
                    </div>
                    
                    <div class="uk-grid">
                        <div class="uk-width-1-3">
                            <label>{{Lang::get('common/general.device')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->device_name}}</span>
                        </div>

                        <div class="uk-width-1-3">
                            <label>{{Lang::get('common/general.identifier')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->device_identifier}}</span>
                        </div>

                        <div class="uk-width-1-3">
                            <label>{{Lang::get('common/general.staff')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->staff_name}}</span>
                        </div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-2-3">
                            <label>{{Lang::get('common/general.products')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->products_name}}</span>
                        </div>

                        <div class="uk-width-1-3">
                            <label>{{Lang::get('common/general.temperature')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->temperature()}}</span>
                        </div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-1-4">
                            <label>{{Lang::get('common/general.invoice_number')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->invoice_number}}</span>
                        </div>

                        <div class="uk-width-1-4">
                            <label>Job number:</label>
                            <span class="uk-text-primary font-bold">{{$item->job_number}}</span>
                        </div>

                        <div class="uk-width-1-4">
                            <label>{{Lang::get('common/general.date_code_valid')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->date_code_valid()}}</span>
                        </div>

                        <div class="uk-width-1-4">
                            <label>{{Lang::get('common/general.package_accept')}}:</label>
                            <span class="uk-text-primary font-bold">{{$item->package_accept()}}</span>
                        </div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-2-3">
                            <label>{{Lang::get('common/general.action')}}:</label>
                            @if($item->action_todo)
                            <div class="uk-text-primary">{{$item->action_todo}}</div>
                            @else
                            <span class="uk-text-warning">{{Lang::get('common/general.not_set')}}</span>
                            @endif
                        </div>
                        <div class="uk-width-1-3">
                            <label>{{Lang::get('common/general.compliant')}}:</label>
                            <span class="font-bold">{{$item->compliant()}}</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection