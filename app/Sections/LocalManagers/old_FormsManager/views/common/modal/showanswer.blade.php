<?php
$form = $answer -> form_log;
?>
@extends('_default.modals.modal')
@section('title')
    {{$form->name}}
    @parent
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    @include('Sections\LocalManagers\FormsManager::common.partials.details_action')
    <div class="clearfix"></div>
@endsection
@section('css')
    <style>
        .w600{
            width:600px;
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