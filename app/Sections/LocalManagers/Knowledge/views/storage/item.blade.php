@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}</h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card" id="item_content">

                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a href="{{URL::to("/knowledge/storage/pdf/$knowledge->id")}}">{{Lang::get('common/button.pdf')}}</a>
                    </div>
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$knowledge->title()}}
                    </h3>
                </div>

                <div class="md-card-content">
                    <?php $childrens = $knowledge->childrens() ?>
                    @if($childrens->count())
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <h4>{{$knowledge->title()}} Areas:</h4>
                            <?php foreach($childrens as $item):?>
                            <div class="m-t"><a href='{{URL::to("/knowledge/storage/item/$item->id")}}' class="btn btn-block btn-green btn-lg">{{$item->title()}}</a></div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    @endif

                    <?php $data = ['content_one','content_two']; ?>
                    @if($knowledge->target_type == 'specific' || $knowledge->target_type == 'individual')
                    <?php $data = ['content_one']; ?>
                    @endif

                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <div class="uk-accordion" data-uk-accordion>
                            @foreach($data as $field)
                                @if($knowledge->target_type == 'specific' || $knowledge->target_type == 'individual')
                                    @if($knowledge->$field)
                                        <h3 class="uk-accordion-title">{{Lang::get('common/general.'.$knowledge->target_type.'.'.$field)}}</h3>
                                        <div class="uk-accordion-content">
                                            {{$knowledge->$field}}
                                        </div>
                                    @endif
                                @else
                                    @if($knowledge->$field)
                                        <h3 class="uk-accordion-title">{{Lang::get('common/general.'.$knowledge->target_type.'.'.$field)}}</h3>
                                        <div class="uk-accordion-content">
                                            {{$knowledge->$field}}
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
