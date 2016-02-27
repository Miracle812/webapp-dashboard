@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}</h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">
                <div class="md-card-content">
                    <form method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <h3 class="heading_a">{{$staff->fullname()}} - {{$actionName}}</h3>

                        <div class="uk-grid">
                            <div class="uk-width-1-4">
                                <input type="file" class="uploadfile" id="uploadfile"/>
                                <div class="newupload m-b">{{Lang::get('common/general.upload_avatar')}}?</div>
                                @if($staff->avatar)
                                    <div class="avatar"><img src="{{$staff->avatar}}"></div>
                                @endif
                            </div>
                            <div class="uk-width-3-4">
                                <div class="avatar-data center" data-avatar='{{URL::to("/staff/edit/avatar/$staff->id")}}' data-width="320" data-height="320" data-section="staff" data-type="avatar">
                                    <div class="example"></div>
                                </div>
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