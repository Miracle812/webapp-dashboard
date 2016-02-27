<?php $diary = $diary->target();?>
@extends('_default.modals.modal')
@section('title')
{{$sectionName}} - {{ ucfirst( str_replace('_', ' ', $diary->getTable()))}}
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="col-sm-12">
                    <?php $types = ['high'=>'danger','medium'=>'default','low'=>'success']?>
                    <span class="h4 text-<?=$types[$diary->priority];?>">{{Lang::get('common/general.'.$diary->priority)}}</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label class="no-padder">{{Lang::get('common/general.title')}}:</label>
                    <span class="h4">{{$diary->name}}</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12 m-b">
                    <label class="col-sm-12 no-padder">{{Lang::get('common/general.description')}}:</label>
                    <span class="">{{$diary->description}}</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label>{{Lang::get('common/general.start')}}:</label>
                    <span class="h4">{{$diary->start}}</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label>{{Lang::get('common/general.end')}}:</label>
                    <span class="h4">{{$diary->end}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn btn-default" id="resetButton" data-dismiss="modal">{{Lang::get('common/button.close')}}</button>
    </div>
@endsection
@section('css')
<style>
    .modal-dialog {width: 400px}
</style>
@endsection