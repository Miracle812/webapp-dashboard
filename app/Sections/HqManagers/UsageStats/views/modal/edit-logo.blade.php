@extends('_default.modals.modal')
@section('title')
@parent
{{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="panel-default">
<!--logo tab-->
    <div id="logo" class="tab-pane fade active in">
        <div class="col-sm-12 supplier-avatar center">
            <input type="file" class="uploadfile" id="uploadfile" value="">
            <div class="newupload m-b">Upload new Logo?</div>

            <select id="cropMask" class="form-control m-b hide">
                <option value="320x80">3:1</option>
                <option value="320x160" selected>2:1</option>
                <option value="320x320">1:1</option>
            </select>
            @if($unit->logo)
            <div class="logo"><img src="{{$unit->logo}}" width="100%"></div>
            @endif
        </div>
        <div class="col-sm-12 center">
            <div class="avatar-data center" data-avatar='{{URL::to('/units/edit/logo/'.$unit->id)}}' data-width="400" data-height="240" data-section="supplier" data-type="logo">
                <div class="example"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
<!--contact tab-->
<div class="clearfix"></div>
</div>
@endsection
@section('css')
{{ Basset::show('package_imagecrop.css') }}
<style>
.modal-dialog {width:465px;}
</style>
@endsection
@section('js')
{{ Basset::show('package_imagecrop.js') }}
@endsection