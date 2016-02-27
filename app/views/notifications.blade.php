<?php /* ?>
@if($errors->any())
    <div class="alert alert-danger">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <i class="fa fa-ban-circle"></i><span class="h4"><strong>Oh no!</strong> {{$errors->first()}}</span>
    </div>
@endif
<?php */ ?>
@if (count($errors->all()) > 0)
<?php /* ?>
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Error</h4>
	Please check the form below for errors
</div>
<?php */ ?>
@endif
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Success</h4>
    @if(is_array($message))
        @foreach ($message as $m)
            {{ $m }}
        @endforeach
    @else
        {{ $message }}
    @endif
</div>
@endif



@if ($message = $errors->all() ? : Session::get('errors') )
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4><strong>Oh no!</strong> Errors appears!</h4>
    @if(is_array($message))
    @foreach ($message as $m)
    <div class="padder"> {{ $m }} </div>
    @endforeach
    @else
    {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Warning</h4>
    @if(is_array($message))
    @foreach ($message as $m)
    {{ $m }}
    @endforeach
    @else
    {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Info</h4>
    @if(is_array($message))
    @foreach ($message as $m)
    {{ $m }}
    @endforeach
    @else
    {{ $message }}
    @endif
</div>
@endif
