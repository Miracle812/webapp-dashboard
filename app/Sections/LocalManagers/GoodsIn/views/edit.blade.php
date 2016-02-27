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
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/goods-in/')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

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

                </div>
            </div>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">
                    <form method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>
                        <div class="uk-grid">
                            <div class="uk-width-medium-2-3">
                                <label>{{Lang::get('common/general.action')}}:</label>
                                <textarea name="action_todo" rows="5" class="md-input">{{Input::old('action', $item->action_todo)}}</textarea>
                            </div>
                            <div class="uk-width-medium-1-3">
                                <p>{{Lang::get('common/general.compliant')}}?</p>
                                <div class="uk-input-group">
                                    <span class="icheck-inline">
                                        <input type="radio" name="compliant" id="compliant_0" data-md-icheck @if(Input::old('compliant', $item->compliant)==0) checked @endif value="0"> <label for="compliant_0" class="inline-label">{{\Lang::get('/common/general.not_compliant')}}</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="compliant" id="compliant_1" data-md-icheck @if(Input::old('compliant', $item->compliant)==1) checked @endif value="1"> <label for="compliant_1" class="inline-label">{{\Lang::get('/common/general.compliant')}}</label>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1">
                                <div class="uk-text-right">
                                    <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light">{{\Lang::get('/common/button.update')}}</button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection