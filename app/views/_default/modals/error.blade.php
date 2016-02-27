@extends('_default.modals.modal')

@section('title')
    {{$error_title}}
@endsection

@section('content')
    <div class="alert alert-danger">
       <h4>{{$error_message}}</h4>
    </div>
@endsection
