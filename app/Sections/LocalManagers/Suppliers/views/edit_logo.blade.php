@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/suppliers')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <form id="frm_probe_devices_edit"  method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <div class="uk-grid">
                            <div class="uk-width-1-4">
                                <input type="file" class="uploadfile" id="uploadfile"/>
                                <div class="newupload m-b">{{Lang::get('common/general.upload_logo')}} </div>
                                <select id="cropMask" class="form-control m-b uk-hidden">
                                    <option value="320x80">3:1</option>
                                    <option value="320x160" selected>2:1</option>
                                    <option value="320x320">1:1</option>
                                </select>
                                @if($supplier->logo)
                                <div class="logo"><img src="{{$supplier->logo}}" width="100%"></div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-width-3-4">
                            <div class="avatar-data center" data-avatar='{{URL::to("/suppliers/edit/logo/$supplier->id")}}'  data-height="240" data-section="supplier" data-type="logo">
                                <div class="example"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
                
        </div>
    </div>
@endsection
@section('styles')
<link href="{{ asset('newassets/js/custom/imagecrop/crop.css') }}" rel="stylesheet">
<link href="{{ asset('newassets/js/custom/imagecrop/canvas_example/example.css') }}" rel="stylesheet">
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('newassets/js/custom/imagecrop/crop.js') }}"></script>
<script type="text/javascript" src="{{ asset('newassets/js/custom/imagecrop/imagecrop_init.js') }}"></script>
@endsection