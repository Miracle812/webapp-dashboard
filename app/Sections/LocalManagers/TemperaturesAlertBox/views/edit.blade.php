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
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/temperatures-alert-box')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}}</a>
                </span>

            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">
                <div class="md-card-content">

                    <h3 class="heading-b">
                        {{$sectionName}} - {{$actionName}}
                    </h3>

                    <form role="form" method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>
                        
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/general.folder_name')}}</label>
                                <input type="text" name="name" value="{{{ Input::old('name', $item->name) }}}" class="md-input">
                                @if($errors->has('name'))
                                <div class="uk-text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">{{\Lang::get('/common/button.update')}}</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection