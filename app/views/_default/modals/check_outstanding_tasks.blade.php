@extends('_default.modals.modal')
@section('title')
<span class="pull-left thumb-sm text-center"><i class="fa fa-info text-danger"></i></span> Delete Confirmation
@endsection
@section('content')
<h3 class="text-danger text-center m-t-n">Are you sure to delete this ? </h3>
<div class="text-danger text-center font-bold ">All data will be lost and not be able to recovery.</div>
<div class="modal-footer">
    <div class="row text-center">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <a href="/logout" class="btn btn-danger button-delete">Logout</a>
    </div>
</div>
@endsection
<style>
.modal-dialog {width:400px;}
</style>
@section('js')
@endsection