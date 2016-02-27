@extends('_default.modals.modal')
@section('header')
    Cleaning schedule task
@endsection
@section('title')
    <div class="text-center">
        <div class="text-navitas">{{$submitted->title}}</div>
        <div class="h5 m-t-xs">{{$submitted->description}}</div>
    </div>
@endsection
@section('content')
    <div class="row padder">
        @if($submitted->staff_name)
            <div class="text-sm">Staff: <span class="font-bold text-navitas">{{$submitted->staff_name}}</span></div>
        @endif
        @if($submitted->formAnswer)
                <div class="col-sm-12 text-center">
                    <a href="/forms-manager/show-answer/{{$submitted->form_answer_id}}" data-toggle="ajaxModal" class="h4 font-bold text-navitas">Form: {{$submitted->form_name}} </a>
                </div>
        @endif
    </div>
    <div class="row m-t">
        <div class="col-sm-12 text-center">
            @if($submitted)
                <div class="h4">The task has been marked as</div>
                @if($submitted->completed) <div class="h4 font-bold text-success"> COMPLETED </div> @else <div class="h4 font-bold text-danger"> NOT completed! </div> @endif
            @endif
        </div>
        <div class="col-sm-12 text-center">
            @if($submitted->summary)
                <div class="h4 m-t">Summary / comment:</div>
                <div class="h4 font-bold"> {{$submitted->summary}} </div>
            @endif
        </div>
    </div>
@endsection

@section('footer')
    <div class="inline col-sm-12">

    </div>
@endsection

@section('css')
    <style>
        .modal-dialog {width: 400px}
    </style>
@endsection