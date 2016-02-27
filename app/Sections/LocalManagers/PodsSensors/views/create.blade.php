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
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/pods/sensors')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <form id="frm_pods_sensors_create" method="post" action="{{URL::to("/pods/sensors/create")}}">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.identifier')}}:</label>
                                <input name="identifier" type="text" class="md-input" value="{{Input::old('identifier', null)}}">
                                <p class="small">Mac Address, ie.: <b>1A2B3C</b></p>
                                @if($errors->has('identifier'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('identifier')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <p>Monitored Area:</p>
                            </div>

                            <div class="uk-width-medium-1-2" id="group_selector" data-url="{{URL::to('/pods/sensors/load-areas')}}/">
                                
                                <input type="radio" name="area_group" data-md-icheck value="pods" @if(Input::old('area_group', 'pods')=='pods') checked @endif />
                                <label class="inline-label"> Pods Areas</label>

                                @if($errors->has('area_group'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('area_group')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div id="area_data">
                                </div>
                                @if($errors->has('area_id'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('area_id')) }}</div>
                                @endif
                            </div>
                            
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1 {{{ $errors->has('name') ? 'has-error' : '' }}}">
                                <label>{{\Lang::get('/common/general.name')}}:</label>
                                <input name="name" type="text" class="md-input" value="{{Input::old('name', null)}}">
                                @if($errors->has('name'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid {{{ $errors->has('description') ? 'has-error' : '' }}}">
                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.description')}}:</label>
                                <textarea name="description" class="md-input" rows="5">{{Input::old('description', null)}}</textarea>
                                @if($errors->has('description'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('description')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-text-right">
                                <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">{{Lang::get('common/button.create')}}</button>
                            </div>
                        </div>
                    </form>

                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            var groupSelector = $('#group_selector');
            var currentArea = {{{Input::old('area_id',0)}}}
            $.get(groupSelector.data('url')+currentArea,function(data){
                $('#area_data').html(data);
                $('#area_data select').selectize();
            })
        })
    </script>
@endsection