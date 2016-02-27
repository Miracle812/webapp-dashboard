<?php $form = $answer -> form_log; ?>
@if(!$type == 'render')
    @section('title') Form :: {{$form->name}} :: @parent @stop
    @section('content')

    <div id="page_content">
        <div id="page_content_inner">
@endif
            <div class="md-card">
                <div class="md-card-content" id="my-id">
                    <h3 class="heading_a uk-margin-bottom">
                        {{$form->name}}
                        <span class="sub-heading">{{$form->description}}</span>
                    </h3>
                    {{$html}}
                </div>
            </div>
@if(!$type == 'render')
            </div>
        </div>
    @endsection
@endif