@extends('newlayout.modals.modal')
@section('title')
@parent
Edit data
@endsection
@section('content')
    <form class="bs-example form-horizontal" id="ajax-form" data-action="{{URL::to('/profile/edit/general')}}" autocomplete="off">
        <div class="uk-form-row">
            <label>{{\Lang::get('/common/general.timezone')}}</label>
            {{Form::select('timezone', $timezonesArray, \Auth::user()->timezone, ['data-md-selectize'])}}
        </div>
        <div class="uk-form-row">
            <label for="username">{{\Lang::get('/common/general.username')}}</label>
            <input type="text" name="username" class="md-input" value="{{\Auth::user()->username}}" >
        </div>

        <div class="uk-form-row">
            <label>{{\Lang::get('/common/general.first_name')}}</label>
            <input type="text" name="first_name" class="md-input" value="{{\Auth::user()->first_name}}" >
        </div>
        <div class="uk-form-row">
            <label>{{\Lang::get('/common/general.surname')}}</label>
            <input type="text" name="surname" class="md-input" value="{{\Auth::user()->surname}}" >
        </div>
        <div class="uk-form-row">
            <label>{{\Lang::get('/common/general.email')}}</label>
            <input type="text" name="email" class="md-input" value="{{\Auth::user()->email}}" >
        </div>
        <div class="uk-form-row">
            <label>{{\Lang::get('/common/general.phone')}}</label>
            <input type="text" name="phone" class="md-input" value="{{\Auth::user()->phone}}" >
        </div>
        <div class="uk-form-row">
            <button class="md-btn md-btn-fullwidth md-btn-success" type="submit">{{\Lang::get('/common/button.update')}}</button>
        </div>
    </form>
@endsection
@section('styles')
<style>
    .w400{width:400px}
</style>
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