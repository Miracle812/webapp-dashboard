@extends('pdf.layout')
@section('content')
    <?php $thread = key($data);?>
    <?php $data = $data[$thread];?>
    @if($title = $data['title'])
        <h2>Section: {{\Lang::get('/common/sections.'.$thread.'.title')}}</h2>
        <h3>Title: <strong class="font-bold">{{$title}}</strong></h3>
    @endif
    @if($contents = $data['content'])
       <h3>Content:</h3>
       @foreach($contents as $key => $content)
           @if(!is_int($key) and $content)
           <h4>{{\Lang::get('/common/sections.'.$thread.'.columns.'.$key)}}</h4>
           @endif
           <div>{{$content}}</div>
       @endforeach
    @endif
@endsection
