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
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$haccp->title()}}
                    </h3>
                </div>

                <div class="md-card-content">
                    <?php $childrens = $haccp->childrens() ?>
                    @if($childrens->count())
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <h4>{{$haccp->title()}} Areas:</h4>
                            <?php foreach($childrens as $item):?>
                            <div class="m-t"><a href='{{URL::to("/haccp/storage/item/$item->id")}}' class="btn btn-block btn-green btn-lg">{{$item->title()}}</a></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    @endif

                    <?php $data = ['content','hazards','control','monitoring','corrective_action']; ?>

                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <div class="uk-accordion" data-uk-accordion>
                                @foreach($data as $field)

                                    @if($haccp->$field)
                                        <h3 class="uk-accordion-title">{{Lang::get('common/general.'.$field)}}</h3>
                                        <div class="uk-accordion-content">
                                            {{$haccp->$field}}
                                        </div>
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

