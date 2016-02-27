@extends('_panel.layouts.panel')
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3"></i> Temperatures</h3>
</div>
<ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
    <li class="active"> Temperatures</li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default">
            <div class="panel panel-body">
                    <div class="col-sm-12">
                        <div class="m"><a href='{{URL::to("/temperatures/pods")}}' class="btn btn-block btn-green btn-lg">Pods sensors temperatures</a></div>
                        <div class="m"><a href='{{URL::to("/temperatures/probes")}}' class="btn btn-block btn-green btn-lg">Smartprobes temperatures</a></div>
                    </div>
            </div>

        </section>
    </div>
</div>
@endsection
