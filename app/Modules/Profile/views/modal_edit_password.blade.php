@extends('newlayout.modals.modal')
@section('title')
@parent
Edit password
@endsection
@section('content')

    <form class="bs-example form-horizontal" id="ajax-form" data-action="{{URL::to('/profile/edit/password')}}" autocomplete="off">
        <div class="uk-form-row">
            <label>{{\Lang::get('/common/general.current_password')}}</label>
            <input type="password" name="current_password" class="md-input">
        </div>
        <div class="uk-form-row">
            <label>{{\Lang::get('/common/general.password')}}</label>
            <input type="password" name="password" class="md-input">
        </div>
        <div class="uk-form-row">
            <label>{{\Lang::get('/common/general.password_confirmation')}}</label>
            <input type="password" name="password_confirmation" class="md-input">
        </div>
        <div class="uk-form-row">
            <button class="md-btn md-btn-fullwidth md-btn-success">{{\Lang::get('/common/button.update')}}</button>
        </div>
    </form>

@endsection
@section('styles')
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $("#ajax-form").on('submit', function(e){
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
                            $('.uk-modal').data('modal').hide();
                        }
                    }
                });
            });
        });
    </script>
@endsection