<?php
$form = $answer -> form_log;
?>
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
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/check-list/submitted")}}"><i class="material-icons">list</i> {{Lang::get('common/general.submitted')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            @include('Sections\LocalManagers\FormsManager::common.partials.details_action')
        
        </div>
    </div>
@endsection
@section('css')
    <style>
        .tooltip-inner{
            width:250px;
        }
    </style>
@endsection

@section('scripts')

{{ Basset::show('package_gallery.js') }}
<script>
    $(document).ready(function(){
        $( 'a.form-file-display' ).imageLightbox();
    });
</script>

@endsection