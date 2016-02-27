@extends('_default.modals.modal')
@section('title')
@parent
{{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="panel-default">
    <form class="bs-example form-horizontal" id="form-googlemap" data-action="{{URL::to('/headquarter/edit')}}">
        <div class="form-group">
            <div class="col-sm-12">
            <label>Unit name</label>
                <input value="{{$headquarter->name}}" type="text"  name="name" placeholder="Unit Name" class="form-control">
            </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>
        <div class="form-group">
            <div class="col-sm-3">
            <label>Post code</label>
                <input value="{{$headquarter->post_code}}" type="text"  name="post_code" placeholder="Post Code" class="form-control gmaploc">
            </div>
            <div class="col-sm-4">
            <label>City</label>
                <input value="{{$headquarter->city}}" type="text"  name="city" placeholder="City" class="form-control gmaploc" >
            </div>
            <div class="col-sm-5">
            <label>Street, number</label>
                <input value="{{$headquarter->street_number}}" type="text"  name="street_number" placeholder="Street" class="form-control gmaploc" >
            </div>
        </div>
        <div class="form-group">
            <section
                    id="gmap_geocoding_modal"
                    style="height:308px;"
                    class="m-b"
                    data-gmaplat="{{$headquarter->gmap_lat}}"
                    data-gmaplng="{{$headquarter->gmap_lng}}"
                    data-gmapzoom="{{$headquarter->gmap_zoom}}">
            </section>

            <input type="hidden" name="gmap_lat" value="{{$headquarter->gmap_lat}}">
            <input type="hidden" name="gmap_lng" value="{{$headquarter->gmap_lng}}">
            <input type="hidden" name="gmap_zoom" value="{{$headquarter->gmap_zoom}}">
        </div>

        <div class="form-group">
            <div class="col-sm-6">
            <label>E-mail</label>
            <input value="{{$headquarter->email}}" type="text" name="email" placeholder="Email" class="form-control" ></div>
            <div class="col-sm-6">
            <label>Telephone</label>
            <input value="{{$headquarter->phone}}" type="text" name="phone" placeholder="Telephone" class="form-control" ></div>
        </div>
        <div class="panel-default form-group">
            <div class="col-sm-12">
                <button class="btn col-sm-12 btn-success">Update</button>
            </div>
        </div>
    </form>
</div>
@endsection
@section('js')
    {{ Basset::show('package_googlemap.js') }}
<script>
$(document).ready(function(){
    $(".modal form").on('submit', function(e){
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
            data:data
        });
    });
})
</script>
@endsection
@section('css')
<style>
    .modal-dialog{width:500px;}
</style>
@endsection