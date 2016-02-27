@extends('emails.layout')
@section('content')
    <font style="font-family: Oswald, Verdana, Geneva, sans-serif; color:#ffffff; font-size:14px; line-height:20px; text-transform:uppercase">
        {{$title}}</font>
    <br />
    <font style="font-family: Oswald, Verdana, Geneva; color:#ffffff;">{{$messages}}</font>
@endsection



