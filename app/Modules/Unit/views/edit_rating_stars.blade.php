@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w650
@endsection
@section('content')
    <div class="panel-default">
        <form class="bs-example form-horizontal" data-action="{{URL::to('/unit/edit/rating-stars')}}" autocomplete="off">
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Rating Stars</label>
                    <div class="col-sm-12"><input name="stars" id="input-id" type="number" class="rating" min=0 max=5 step=1 data-size="lg"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>{{\Lang::get('/common/general.description')}}</label>
                    <textarea name="description" placeholder="{{\Lang::get('/common/general.description')}}" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">

                <div class="row padder">
                    <?php
                    $targetType = 'units_rating_stars';
                    $options = \Config::get('files_uploader.'.$targetType);
                    $target = [
                            'target_type' => $targetType,
                            'target_id' => 'create.'.\Auth::user()->id
                    ];
                    ?>
                    {{Form::FilesUploader($options,$target)}}
                </div>

            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button class="btn col-sm-12 btn-success">{{\Lang::get('/common/button.update')}}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('css')
    <style>
        .w650{width:650px;}
    </style>
@endsection
@section('js')
    <script>
        var rating = "{{$scores}}";
        $(document).ready(function(){
            $('#input-id').rating({
                containerClass:'inline',
                step:1,
                disabled: false,
                showClear: false}).
                    rating('update',rating).
                    on('rating.change',function(e,value){
                        rating = value;
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
                            $('#unit-rating-stars').rating('update',rating);
                            $('#ajaxModal').modal('hide');
                        }
                    }
                });
            });
        });
    </script>
@endsection