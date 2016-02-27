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

                    <form role="form" method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>
                        
                        <h3 class="heading-b">
                            {{$sectionName}} - {{$actionName}}
                        </h3>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">

                                <label>{{\Lang::get('/common/general.group_name')}}</label>
                                <select name="group" data-md-selectize>
                                    @foreach($groups as $group)
                                    <option value="{{$group}}">{{ucfirst($group)}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="uk-grid" id="area-selector" data-url="{{URL::to('/temperatures-alert-box/load-areas')}}/">
                            <div class="uk-width-1-1">

                                <label>{{\Lang::get('/common/general.area_name')}}</label>
                                <div id="area-data"></div>

                            </div>
                        </div>

                        <div class="uk-grid {{{ $errors->has('name') ? 'error' : '' }}}">
                            <div class="uk-width-1-1">

                                <label>{{\Lang::get('/common/general.user_area_name')}}</label>
                                <input type="text" name="name" value="{{{ Input::old('name', null) }}}" class="md-input">
                                @if($errors->has('name'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                @endif

                            </div>
                        </div>

                        <hr class="md-hr" />
                        <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">{{\Lang::get('/common/button.create')}}</button>
                    </form>
            </div>
          </section>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function(){
    var selected = 1;
    var areaSelector = $('#area-selector');
    function getAreas(){
        $.get(areaSelector.data('url')+selected,function(data){
            $('#area-data').html(data);
            $('#area-data select').selectize();
            $(areaSelector).show();
        })
    }
    $('select[name=group]').on('change', function(){
        selected = this.value;
        getAreas();
    });
    getAreas();
})
</script>
@endsection