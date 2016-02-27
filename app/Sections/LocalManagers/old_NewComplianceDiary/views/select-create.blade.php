@extends('newlayout.modals.modal')
@section('title')
    Create task:
@endsection
@section('content')
    <div class="uk-form-row">
        <?php $path = 'create?dates='.\Input::get('dates'); ?>
        <button class="md-btn md-btn-primary md-btn-block" data-toggle="ajaxModal" href="/new-compliance-diary/{{$path}}">Compilance diary</button>
    </div>
    <div class="uk-form-row">
        <button class="md-btn md-btn-success md-btn-block" data-toggle="ajaxModal" href="/check-list/{{$path}}">Check list</button>
    </div>
@endsection
@section('styles')
    <style>
        .uk-modal-dialog {max-width: 400px}
    </style>
@endsection
