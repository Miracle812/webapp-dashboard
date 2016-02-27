@extends('emails.layout')
@section('forward-link')
    {{URL::to($recipient_url)}}
@endsection
@section('content')
    <div style="font-family: Oswald, Verdana, Geneva, sans-serif; color:#ffffff; font-size:14px;">
        <div style="line-height:20px; text-transform:uppercase">
                {{\Lang::get('/common/general.hi')}} {{$recipient -> fullname()}} !
        </div>
        <div>
            You got new message from {{$author -> fullname()}}!
        </div>
        <div style="margin: 20px 0;">
            {{$msg}}
        </div>
        <hr>
        <div>
            <div style="margin: 10px 0 0; font-size:12px; color:#E8E8E8">
                You can display this message in Navitas system by  <a style="color:#f79546" href="{{URL::to($recipient_url)}}">click me!</a> ( This URL is available over next 7 days. )<br>
                This email has been sent automatically from Navitas system. Please don't reply to this email.
            </div>
        </div>
    </div>
@endsection