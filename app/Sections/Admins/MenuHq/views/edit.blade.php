@extends('_admin.layouts.admin')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/menu-hq/')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>
           <a class="btn btn-green inline" href="{{URL::to('/menu-hq/create')}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.create')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])

<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading font-bold">
                {{$sectionName}} - {{$actionName}}
            </header>
            <div class="panel-body">
                <form class="form-horizontal m-t" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{\Lang::get('/common/general.title')}}</label>
                        <div class="col-sm-10 {{{ $errors -> has('title') ? 'has-error' : '' }}}">
                            <input name="title" type="text" class="form-control" placeholder="{{Lang::get('common/general.title')}}" value="{{Input::old('title', $structure->title)}}">
                            @if($errors->has('title'))
                                <div class="text-danger">{{ Lang::get($errors->first('title')) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{\Lang::get('/common/general.menu_title')}}</label>
                        <div class="col-sm-10 {{{ $errors -> has('menu_title') ? 'has-error' : '' }}}">
                            <input name="menu_title" type="text" class="form-control" placeholder="{{Lang::get('common/general.menu_title')}}" value="{{Input::old('menu_title', $structure->menu_title)}}">
                            @if($errors->has('menu_title'))
                                <div class="text-danger">{{ Lang::get($errors->first('menu_title')) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Type:</label>
                        <div class="col-sm-10 {{{ $errors -> has('type') ? 'has-error' : '' }}}">
                            {{Form::select('type', array_merge(array('' => 'Please Select'),$types),  isset($structure) ? $structure->type : null, array('id'=>'type', 'class'=>'form-control m-b'))}}
                            @if($errors->has('type'))
                                <div class="text-danger">{{ Lang::get($errors->first('type')) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group type_options" id="opt_module">
                        <label class="col-sm-2 control-label">Route path:</label>
                        <div class="col-sm-10 {{{ $errors -> has('route_path') ? 'has-error' : '' }}}">
                            <input class="form-control" type="text" name="route_path" id="title" value="{{{ Input::old('route-path', isset($structure) ? $structure->route_path : null) }}}" />
                            @if($errors->has('route_path'))
                                <div class="text-danger">{{ Lang::get($errors->first('route_path')) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group type_options" id="opt_link">
                        <label class="col-sm-2 control-label">Redirect Address:</label>
                        <div class="col-sm-10 {{{ $errors -> has('link') ? 'has-error' : '' }}}">
                            <input class="form-control" type="text" name="link" id="module_link" value="{{{ Input::old('link', isset($structure) ? $structure->link : null) }}}" />
                            @if($errors->has('link'))
                                <div class="text-danger">{{ Lang::get($errors->first('link')) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Icon:</label>
                        <div class="col-sm-10 {{{ $errors -> has('ico') ? 'has-error' : '' }}}">
                            <div class="col-sm-2 fa-5x text-navitas">
                                <i id="icon-view" class="{{$structure->ico}}"></i>
                                <input id="icon-input" type="hidden" name="ico"  value="{{{ Input::old('ico', isset($structure) ? $structure->ico : null) }}}" />
                            </div>
                            <div class="col-sm-3">
                                <a  href="{{URL::to('/menu-hq/icons')}}" data-toggle="ajaxModal" class="btn btn-primary"> Set Ico</a>
                            </div>
                            @if($errors->has('ico'))
                                <div class="text-danger">{{ Lang::get($errors->first('ico')) }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <div class="modal-footer">
                                <button class="btn btn-green" type="submit">{{Lang::get('common/button.update')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection

@section('js')
<script>
    $('.type_options').hide();
    $('#type').change(function(){
        $(".type_options").fadeOut('fast');
        $('#opt_'+$(this).val()).fadeIn();
    });
    $('#type').trigger('change');
    $(document).on('click', '.modal #list-page-icons div', function(e) {
        var $form = $(document).find('form');
        $ico = $(this).children('i');
        if(e.handled != 1){
            e.handled = 1;
            if($ico.length){
                $className = $ico.attr('class');
                $ico = $form.find('i#icon-view');
                $ico.attr('class',$className);
                $icoInput = $form.find('input#icon-input');
                $icoInput.attr('value',$className);
                $(document).find('.modal, .modal-backdrop').remove();
            }
        }
    });
</script>
@endsection