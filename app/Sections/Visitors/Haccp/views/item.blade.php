@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}} - {{$haccp->title()}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}</h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-navitas">{{$haccp->title()}}</h4>
            </div>
            <?php $childrens = $haccp->childrens() ?>
            @if($childrens->count())
            <div class="panel panel-body  m-b-n">
                <h4>{{$haccp->title()}} Areas:</h4>
                <div class="col-sm-12">
                    <?php foreach($childrens as $item):?>
                    <div class="m"><a href='{{URL::to("/haccp/item/$item->id")}}' class="btn btn-block btn-green btn-lg">{{$item->title()}}</a></div>
                    <?php endforeach ?>
                </div>
            </div>
            @endif

            <?php $data = ['content','hazards','control','monitoring','corrective_action']; ?>

            <div class="panel-body">
                <div id="accordion2" class="panel-group m-b">
                    @foreach($data as $field)

                        @if($haccp->$field)
                            <div class="panel panel-default">
                                <a href="#field-{{$field}}" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle">
                                <div class="alert alert-orange h3 font-bold">
                                    {{Lang::get('common/general.'.$field)}}
                                </div>
                                </a>
                                <div class="panel-collapse collapse in" id="field-{{$field}}">
                                    <div class="panel-body text-sm">
                                        {{$haccp->$field}}
                                    </div>
                                </div>
                            </div>
                        @endif

                    @endforeach
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
@section('js')
{{ Basset::show('package_gallery.js') }}
<script>
$(document).ready(function(){
    $( '.thumbnail a' ).imageLightbox();
})
</script>
@endsection
