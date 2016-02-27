@extends('emails.layout')
@section('content')
<font style="font-family: Oswald, Helvetica, Arial, serif; Verdana, Geneva, sans-serif; color:#ffffff; font-size:12px; line-height:20px; text-transform:uppercase">
    Password Reset </font><br />
<font style="font-family: Oswald; color:#ffffff;">
    To reset your password, complete this form: {{ URL::to('password/reset', array($token)) }}.
</font>
@endsection