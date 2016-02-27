@extends('_default.modals.modal')
@section('title')
@parent
{{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
w500
@endsection
@section('content')
    <div class="panel-default">
        <form method="post" class="form-horizontal" data-action="{{URL::to('/outstanding-tasks/resolve-all')}}">
            <div class="form-group">
                <div class="col-sm-12">
                    <label class="col-sm-12">{{Lang::get('common/general.select_section')}}:</label>
                    <div class="col-sm-12">
                        @foreach ($targetTypes as $target)
                            <?php $targets[$target] = Lang::get("common/sections.$target.title"); ?>
                        @endforeach
                        {{Form::select('select_section', ([Lang::get('common/general.select_section')]+$targets), Input::old('target_type', null), array('class'=>'form-control m-b'))}}
                    </div>
                </div>
            </div>
            <div id="section_inputs" class="padder"></div>
        </form>
    </div>
@endsection
@section('css')
<style>
    .w500{width:500px}
</style>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            var selected = 0;
            var sectionSelector = $('select[name="select_section"]');
            function getInputs(){
                $.get('/outstanding-tasks/load-inputs/'+selected,function(data){
                    if(data){
                        $('#section_inputs').html(data);
                    }
                })
            }
            sectionSelector.on('change', function(){
                selected = this.value;
                if(selected!=0)
                    getInputs();
                else
                    $('#section_inputs').html('');
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
                            $('#section_inputs').html('');
                            sectionSelector.find('option:selected').remove();
                            sectionSelector.val(0);
                        }
                    }
                });
            });
        });
    </script>
@endsection