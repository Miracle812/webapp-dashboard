@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w400
@endsection
@section('content')
    <div class="panel-default">
        <form class="bs-example form-horizontal" data-action="{{URL::to('/outstanding-tasks/resolve/'.$item->id)}}">
            <div class="form-group">
                <label class="col-sm-8 control-label">{{\Lang::get('/common/general.is_resolved')}}</label>
                <div class="col-sm-4">
                    <label class="switch">
                        <input value="1" @if($item->status) checked @endif  name="status" type="checkbox">
                        <span></span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-8 control-label">{{\Lang::get('/common/general.send_message')}} {{\Lang::get('/common/general.to')}} {{\Lang::get('/common/general.supplier')}}</label>
                <div class="col-sm-4">
                    <label class="switch">
                        <input value="1" name="send_message" type="checkbox">
                        <span></span>
                    </label>
                </div>
            </div>

            <div class="form-group" id="message_area">

                <label class="col-sm-12">{{Lang::get('common/general.thread')}} & {{Lang::get('common/general.message')}} {{Lang::get('common/general.title')}}</label>
                <div class="col-sm-12">
                    <input name="title" class="form-control" placeholder="{{Lang::get('common/general.title')}}" value="{{Lang::get('common/general.unit')}} {{$item->units->name}} :: {{Lang::get('common/sections.'.$target->getTable().'.messages.outstanding_tasks')}}">
                </div>
                <label class="col-sm-12">{{Lang::get('common/general.message')}}</label>
                <div class="col-sm-12">
                    <textarea name="message" rows="6" class="form-control" placeholder="{{Lang::get('common/general.message')}}">
                        {{$target->getAllNonCompliant()}},
                        {{Lang::get('common/general.products').': '.$target->products_name}},
                        {{Lang::get('common/general.temperature').': '.$target->temperature}}&#x2103,
                        {{Lang::get('common/general.invoice_number').': '.$target->invoice_number}}
                        -----------------------------------
                        {{Lang::get('common/general.unit').': '.$item->units->name}},
                    </textarea>
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
                    <button class="btn col-sm-12 btn-success">{{\Lang::get('/common/button.submit')}}</button>
                </div>
            </div>

        </form>
    </div>
@endsection
@section('css')
    <style>
        .w400{width:400px}
    </style>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            $('select[name=non-compliant-trend]').change(function(){
                $("#resolve_comment").toggle((($(this).val() == 'other') ? true : false));
            });
            var messageArea = $('#message_area');
            messageArea.hide();
            $('input[name=send_message]').on('change',function() {
                messageArea.toggle();
            });
            $(".modal form").on('submit', function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
                var form = $(this);
                data = form.serialize();
                url = form.data('action');
                $.ajax({
                    context: { element: form },
                    url: url,
                    type: "post",
                    dataType: "json",
                    data:data,
                    success:function(msg) {
                        if(msg.type == 'success'){
                            $('#dataTable').DataTable().ajax.reload();
                            $('#ajaxModal').modal('hide');

                        }
                    }
                });
            });
        });
    </script>
@endsection