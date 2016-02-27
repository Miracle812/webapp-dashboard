<?php
$form = $answer -> form_log;
?>
@extends('_panel.layouts.panel')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}} </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    @include('Sections\Admins\FormsManager::common.partials.details_action')
    <div class="clearfix"></div>
@endsection
@section('css')
    <style>
        .tooltip-inner{
            width:250px;
        }
    </style>
@endsection

@section('js')

{{ Basset::show('package_gallery.js') }}
<script>
    $(document).ready(function(){
        $( 'a.form-file-display' ).imageLightbox();
    });
</script>

@endsection