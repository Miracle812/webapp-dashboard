@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    <div class="panel-default">
        <form class="bs-example form-horizontal" data-action="{{URL::to('/form-processor/edit/answer/'.$target->id)}}">
            <div class="form-group">
                <div class="col-sm-12">
                    <h4>Form: <span class="font-bold">{{$target->formLog->name}}</span></h4>
                    <small>{{$target->formLog->description}}</small>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    {{Form::editComplaints($target)}}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Confirm changes by signature:</label>
                    <div class="center" style="max-width:560px;margin:0 auto">
                        <div class="panel-body">
                            <div id="signature-pad" class="m-signature-pad">
                                <div class="m-signature-pad--body">
                                    <?php $name = 'signature[' . $target->formLog->id . '][' . $item->id . ']'; ?>
                                    <canvas id = "{{$name.'[sign]'}}" ></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-center m-t">
                    <button class="btn btn-default" data-action="clear" buttonId="{{$name.'[sign]'}}">{{Lang::get('/common/button.clear_sign')}}</button>
                </div>
            </div>
            <?php $trends = \Model\NonCompliantTrends::orderBy('sort')->get()?>
            <?php if($trends->count()):?>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Non Compliant Trends:</label>
                    {{Form::select('non-compliant-trend',['other'=>'Other']+$trends->lists('name','id'),null, ['class'=>'form-control'])}}
                </div>
            </div>
            <?php endif?>
            <div class="form-group" id="resolve_comment">
                <div class="col-sm-12">
                    <label>Resolve comment</label>
                    <textarea name="action_todo" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button class="btn col-sm-12 btn-success">{{\Lang::get('/common/button.update')}} / {{\Lang::get('/common/button.resolve')}}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('css')
    <style>
        .w400{width:600px}
    </style>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            $('select[name=non-compliant-trend]').change(function(){
                $("#resolve_comment").toggle((($(this).val() == 'other') ? true : false));
            });
        });
    </script>
@endsection