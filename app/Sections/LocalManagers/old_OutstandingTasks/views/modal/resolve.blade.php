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
                <label class="col-sm-4 control-label">{{\Lang::get('/common/general.is_resolved')}}</label>
                <div class="col-sm-8">
                    <label class="switch">
                        <input value="1" @if($item->status) checked @endif  name="status" type="checkbox">
                        <span></span>
                    </label>
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