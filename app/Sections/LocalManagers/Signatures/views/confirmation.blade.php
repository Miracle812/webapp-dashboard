@extends('_default.modals.modal')

@section('title')
Authorization Signature
@endsection

@section('content')
<section class="panel panel-default" id="signature-section">
    <header class="panel-heading-navitas">
        <?=$data[0]?>
    </header>
    <div class="panel-body">
        <?=$data[1]?>
    </div>
</section>
@endsection
