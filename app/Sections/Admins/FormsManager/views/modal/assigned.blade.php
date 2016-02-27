@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w500
@endsection
@section('content')
    <section class="panel panel-default">
        <?php

        $assigned = $form -> assigned;
        $generic = false;
        if($assigned){
            $assigned = $assigned->data;
            $generic = $assigned == 'generic';
            if(!$generic){
                $data = unserialize($assigned);
            }
        }
        ?>
        <div class="panel-body">
            <form role="form" data-action="{{URL::to('/forms-manager/form/'.$form->id.'/assigned')}}">
                <div class="col-sm-12 radio h4 m-b">
                    <label class="m-r">
                        <input type="radio" @if($generic) checked="" @endif value="generic" name="custom_generic">
                        Generic
                    </label>
                    <label>
                        <input type="radio" @if(!$generic) checked="" @endif value="custom" name="custom_generic">
                        Custom
                    </label>
                </div>
                <div id="selector_custom" class="col-sm-12 @if($generic) hide @endif clear">
                    @foreach($hqs as $hq)
                    <div class="form-group">
                        <div class="col-sm-12 checkbox">
                            <label class="m-r">
                                <input type="checkbox" @if(isset($data[$hq->id])) checked @endif value="{{$hq->id}}" name="hq[{{$hq->id}}]">
                                <span class="font-bold">{{$hq->name}}</span>
                                <label class="m-r @if(!isset($data[$hq->id])) hide @endif">
                                    <input type="checkbox" @if( isset($data[$hq->id])  && $data[$hq->id]=='all')  checked @endif  value="all" id="all_units" name="units[{{$hq->id}}][]">
                                    <span class="font-bold">All Units</span>
                                </label>
                            </label>
                        </div>
                        <div class="col-sm-12  @if( ( isset($data[$hq->id])  && $data[$hq->id]=='all' ) || !isset($data[$hq->id]) ) hide @endif clear " id="hq_{{$hq->id}}_units">
                            <select class="col-sm-6 form-control" multiple name="units[{{$hq->id}}][]">
                                @foreach($hq->units as $unit)
                                    <?php $inUnits = ( isset($data[$hq->id]) && is_array($data[$hq->id]) && in_array($unit->id, $data[$hq->id]) ); ?>
                                    <option @if($inUnits) selected @endif value="{{$unit->id}}">{{$unit->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="btn btn-sm btn-success m-t" type="submit">Save</button>
            </form>
        </div>
    </section>
    <div class="clearfix"></div>
@endsection
@section('css')
    <style>
        .w500{max-width:500px}
    </style>
@endsection
@section('js')
    <script>
        //$('input[id=all_units]').parent().addClass('hide');
        $(document).ready(function()
        {
            $('input[name=custom_generic]').on('change',function()
            {
                if($(this).val()=='generic'){
                    $('#selector_custom').addClass('hide');
                }
                else{
                    $('#selector_custom').removeClass('hide');
                }
            });
            $('input[id=all_units]').on('change',function()
            {
                $id = $(this).attr('name').match(/\[([^[d]*)\]/)[1];
                if($(this).is(':checked')){
                    $('div#hq_'+$id+'_units').addClass('hide');
                }
                else{
                    $('div#hq_'+$id+'_units').removeClass('hide');
                }
            });
            $('input[name^="hq["]').on('change',function()
            {
                $hqid = $(this).val();
                $selectUnits = $('input[name="units['+$hqid+'][]"]#all_units');
                if($(this).is(':checked')){
                    $selectUnits.prop('checked', true).parent().removeClass('hide');
                }
                else{
                    $selectUnits.prop('checked', false).parent().addClass('hide');
                    $('div#hq_'+$hqid+'_units').addClass('hide');
                }
            });


            $(".modal form").on('submit', function(e)
            {
                e.preventDefault();
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
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
                            $datatable = $(document).find('#dataTable');
                            if($datatable.length){
                                $datatable.DataTable().ajax.reload();
                            }
                            $('#ajaxModal').modal('hide');
                        }
                    }
                });
            });
        });
    </script>
@endsection