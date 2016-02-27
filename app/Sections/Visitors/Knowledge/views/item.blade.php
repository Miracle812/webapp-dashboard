@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}} - {{$knowledge->title()}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}

    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-navitas">{{$knowledge->title()}}
                    <span class="pull-right">
                       <a class="btn btn-orange inline" href="{{URL::to("/knowledge/pdf/$knowledge->id")}}"><i class="fa fa-file-pdf-o"></i> {{Lang::get('common/button.pdf')}} </a>
                    </span>
                </h4>
            </div>
            <?php $childrens = $knowledge->childrens() ?>
            @if($childrens->count())
            <div class="panel panel-body  m-b-n">
                <h4>{{$knowledge->title()}} Areas:</h4>
                <div class="col-sm-12">
                    <?php foreach($childrens as $item):?>
                    <div class="m"><a href='{{URL::to("/knowledge/item/$item->id")}}' class="btn btn-block btn-green btn-lg">{{$item->title()}}</a></div>
                    <?php endforeach ?>
                </div>
            </div>
            @endif

            <?php $data = ['content_one','content_two']; ?>
            @if($knowledge->target_type == 'specific' || $knowledge->target_type == 'individual')
            <?php $data = ['content_one']; ?>
            @endif
            <div class="panel-body">
                <div id="accordion2" class="panel-group m-b">
                    @foreach($data as $field)
                        @if($knowledge->target_type == 'specific' || $knowledge->target_type == 'individual')
                            @if(strip_tags($knowledge->$field))
                                <h3>{{Lang::get('common/general.'.$knowledge->target_type.'.'.$field)}}</h3>
                                <div class="panel panel-default">
                                    <div class="panel-body text-sm">
                                        {{$knowledge->$field}}
                                    </div>
                                </div>
                            @endif
                        @else
                            @if(strip_tags($knowledge->$field))
                                <div class="panel panel-default">
                                    <a href="#field-{{$field}}" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle">
                                    <div class="alert alert-orange h3 font-bold">
                                        {{Lang::get('common/general.'.$knowledge->target_type.'.'.$field)}}
                                    </div>
                                    </a>
                                    <div class="panel-collapse collapse in" id="field-{{$field}}">
                                        <div class="panel-body text-sm">
                                            {{$knowledge->$field}}
                                        </div>
                                    </div>
                                </div>
                            @endif
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
