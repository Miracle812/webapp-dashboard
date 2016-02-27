@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w500
@endsection
@section('content')

<section class="panel panel-default wrapper">
    <form class="bs-example form-horizontal" data-action="{{URL::to('/outstanding-tasks/resolve/'.$item->id)}}">
        <?php $first = isset($items[0])?$items[0]:false;?>
        <header class="panel-heading">
            Invalid Temperatures Table
        </header>
        <div class="table-responsive scrollable pre-scrollable small">
            <table class="table table-striped b-t b-light">
                <thead>
                <tr>
                    <th width="20"><label class="checkbox m-l m-t-none m-b-none i-checks"><input id="all_temperatures" type="checkbox"><i></i></label></th>
                    <th>@if($first->area_name) Area @else Device @endif</th>
                    <th>Temperature</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $record)
                    <tr>
                        <td><label class="checkbox m-l m-t-none m-b-none i-checks"><input name="temperatures[]" value="{{$record->id}}" type="checkbox" class="toggle-state-switch"><i></i></label></td>
                        <td>@if($first->area_name) {{$record->area_name}} @else {{$record->device->name}} @endif</td>
                        <td>{{$record->temperature}} ℃</td>
                        <td>{{\Carbon::createFromFormat('Y-m-d H:i:s',$record->created_at)->format('d-m-Y H:i')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <label class="col-sm-6 control-label">{{\Lang::get('/common/general.is_resolved')}}</label>
            <div class="col-sm-6">
                <label class="switch">
                    <input value="1"  name="status" type="checkbox" >
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
</section>
@endsection
@section('css')
    <style>
        .w500{width:500px}
    </style>
@endsection
@section('js')
    <script>
 $(document).ready(function(){
     $('select[name=non-compliant-trend]').change(function(){
         $("#resolve_comment").toggle((($(this).val() == 'other') ? true : false));
     });
     $('#all_temperatures').change(function(){
         status = $(this).is(":checked") ? true : false;
         $(".toggle-state-switch").prop("checked",status);
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