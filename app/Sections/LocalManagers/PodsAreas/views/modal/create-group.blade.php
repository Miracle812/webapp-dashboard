@extends('_default.modals.modal')
@section('title')
@parent
Add Group
@endsection
@section('class_modal')
w250
@endsection
@section('content')
    <div class="panel-default">
        <form class="bs-example form-horizontal" data-action="{{URL::to('/pods/areas/create/group')}}">
            <div class="form-group">
                <div class="col-sm-12">
                    <label>{{\Lang::get('/common/general.name')}}</label>
                    <input type="text" class="form-control" placeholder="{{Lang::get('common/general.name')}}" value="" name="name">
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
    .w250{width:250px}
</style>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
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
                            $.get('/pods/areas/refresh',function(data){
                                $('#nestable > .dd-list').html(data);
                                $('#nestable').nestable();
                                $('a.editable').editable();
                            });
                            $('#ajaxModal').modal('hide');
                        }
                    }
                });
            });
        });
    </script>
@endsection