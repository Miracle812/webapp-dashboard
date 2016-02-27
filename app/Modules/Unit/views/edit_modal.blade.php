@extends('newlayout.modals.modal')
@section('title')
@parent
Edit unit
@endsection
@section('content')
    <?php $unit = \Auth::user()->unit();?>
    <form id="units-form" data-action="{{URL::to("/unit/edit")}}">
        <div class="uk-form-row">
            <label>{{\Lang::get('/common/general.name')}}</label>
            <input value="{{$unit->name}}" type="text" name="name" class="md-input">
        </div>

        <div class="uk-form-row">
            <div class="uk-grid">
                <div class="uk-form-item uk-width-medium-1-2">
                    <label>{{\Lang::get('/common/general.email')}}</label>
                    <input value="{{$unit->email}}" type="text" name="email" placeholder="{{\Lang::get('/common/general.email')}}" class="md-input" >
                </div>
                <div class="uk-form-item uk-width-medium-1-2">
                    <label>{{\Lang::get('/common/general.phone')}}</label>
                    <input value="{{$unit->phone}}" type="text" name="phone" placeholder="{{\Lang::get('/common/general.phone')}}" class="md-input" >
                </div>
            </div>
        </div>

        <div class="uk-form-row">
            <div class="uk-grid">
                <div class="uk-form-item uk-width-medium-1-3">
                    <label>{{\Lang::get('/common/general.post_code')}}</label>
                    <input value="{{$unit->post_code}}" type="text" name="post_code" class="md-input gmaploc">
                </div>
                <div class="uk-form-item uk-width-medium-1-3">
                    <label>{{\Lang::get('/common/general.city')}}</label>
                    <input value="{{$unit->city}}" type="text" name="city" class="md-input gmaploc">
                </div>
                <div class="uk-form-item uk-width-medium-1-3">
                    <label>{{\Lang::get('/common/general.street_number')}}</label>
                    <input value="{{$unit->street_number}}" type="text" name="street_number" class="md-input gmaploc">
                </div>
            </div>
        </div>

        <div class="uk-form-row">
            <section
                id="gmap_geocoding_modal"
                style="width: 100%;height: 200px"
                data-gmaplat="{{$unit->gmap_lat}}"
                data-gmaplng="{{$unit->gmap_lng}}"
                data-gmapzoom="{{$unit->gmap_zoom}}">
            </section>

            <input type="hidden" name="gmap_lat" value="{{$unit->gmap_lat}}">
            <input type="hidden" name="gmap_lng" value="{{$unit->gmap_lng}}">
            <input type="hidden" name="gmap_zoom" value="{{$unit->gmap_zoom}}">
        </div>

        <div class="uk-form-row">
            <button class="md-btn md-btn-fullwidth md-btn-success" type="submit">{{\Lang::get('/common/button.update')}}</button>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        function MapApiLoaded() {
            $.ajaxSetup({async: false});
                $.getScript("{{ asset('newassets/packages/gmaps/gmaps.min.js') }}");
                $.getScript("{{ asset('newassets/js/custom/form.gmap.init.js') }}");
            $.ajaxSetup({async: true});
        }
        $.getScript("https://maps.google.com/maps/api/js?sensor=false&async=2&callback=MapApiLoaded", function () {});
        $(document).ready(function()
        {
            $("#units-form").on('submit', function(e){
                if(e.handled == 1)
                {
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
        })
    </script>
@endsection