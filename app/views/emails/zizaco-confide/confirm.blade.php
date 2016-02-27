@extends('emails.layout')
@section('content')
    <font style="font-family: Oswald, Verdana, Geneva, sans-serif; color:#f37c1e; font-size:18px; line-height:20px; text-transform:uppercase">
        {{ Lang::get('confide::confide.email.account_confirmation.subject') }}
    </font><br />
    <font style="font-family: Oswald; color:#ffffff; font-size:14px; ">
        <?php $name  = ucfirst($user['first_name']).' '.ucfirst($user['surname']); ?>
        <p>{{ Lang::get('confide::confide.email.account_confirmation.greetings', array('name' => $name)) }},</p>
        <p>{{ Lang::get('confide::confide.email.account_confirmation.body') }}</p>
        <a style="font-family: Oswald; color:#f37c1e; font-size:14px;" href='{{{ URL::to("account-confirmation/{$user['confirmation_code']}") }}}'>
            {{{ URL::to("account-confirmation/{$user['confirmation_code']}") }}}
        </a>
        <p>{{ Lang::get('confide::confide.email.account_confirmation.farewell') }}</p>
    </font>
@endsection