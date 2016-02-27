@extends('emails.layout')
@section('content')
    <font style="font-family: Oswald, Verdana, Geneva, sans-serif; color:#f37c1e; font-size:18px; line-height:20px; text-transform:uppercase">
        Visitor Access Link
    </font><br />
    <font style="font-family: Oswald; color:#ffffff; font-size:14px;">
        <p>Hi {{$user->fullname()}} !</p>
        <p>You have granted access to unit: <span style="font-weight:bold;color:#f37c1e;">{{$user->unit()->name}}</span> as visitor role.</p>
        <p>Your access expire at <span style="font-weight:bold;color:#f37c1e;">{{$user -> expiry_date()}}</span> !</p>
        <p></p>
        <p>Login into navitas via url:</p>
        <a style="font-family: Oswald; color:#f37c1e; font-size:14px;" href='{{ URL::to('token-access', array($user->username,$user->confirmation_code)) }}'>
            {{ URL::to('token-access', array($user->username,$user->confirmation_code)) }}
        </a>
        <p>{{ Lang::get('confide::confide.email.account_confirmation.farewell') }}</p>
    </font>
@endsection