@extends('_default.modals.locked')
@section('content')

    <div class="thumb-md"><img src="{{$user->avatar()}}" class="img-circle b-a b-light b-3x"></div>
    <p class="text-white h4 m-t m-b">{{$user->fullname()}}</p>
    <form action="/lock-me" id="locked" autocomplete="off">
    <div class="input-group">
          <input type="password" name="password" class="form-control text-sm btn-rounded" placeholder="Enter pwd to continue">
          <span class="input-group-btn">
            <button class="btn btn-success btn-rounded" type="submit"><i class="fa fa-arrow-right"></i></button>
          </span>
    </div>
    </form>
    <div class="m-t text-white">
    Unlock or <a class="text-default" href="/logout">Logout</a>
    </div>

@endsection
@section('js')
<script>
$(document).ready(function(){

    $('form#locked').on('submit', function(e){

        var $form = $(this);
        $.ajax({
            url:$form.attr('action'),
            data:$form.serialize(),
            type:'post',
            dataType: 'json',
            success:function($data){
                if($data.type == 'success'){
                    $(document).find('.modal-backdrop, #ajaxModal').fadeOut(function(){
                        $(this).remove();
                    });
                }
            }
        });
        return false;
    })
})
</script>
@endsection