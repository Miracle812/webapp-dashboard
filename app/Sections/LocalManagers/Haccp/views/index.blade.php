<?php
$datas = [];
if(count($generic))
    $datas = array_merge($datas,['generic'=>$generic]);
if(count($specific))
    $datas = array_merge($datas,['company'=>$specific]);
if(count($individual))
    $datas = array_merge($datas,['site'=>$individual]);
?>
@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}</h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default">
            <div class="panel-body">
                <div id="accordion2" class="panel-group m-b">
                @foreach($datas as $key => $data)
                    @if($data)
                    <div class="panel panel-default">
                        <a href="#field-{{$key}}" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle">
                            <div class="panel-heading">
                                <h4 class="text-navitas font-bold">{{Lang::get('common/general.haccp_'.$key)}}</h4>
                            </div>
                        </a>
                        <div class="panel-collapse collapse" id="field-{{$key}}">
                            <div class="">
                                <?php foreach($data as $item):?>
                                    <div class="m"><a href='{{URL::to('/haccp/storage/item/'.$item->id)}}' class="btn btn-block btn-green btn-lg">{{$item->title()}}</a></div>
                                <?php endforeach ?>
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