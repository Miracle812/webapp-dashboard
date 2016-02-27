<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h4>
    {{\Lang::get('/common/general.hi')}}  {{$inputs['recipient']}}!
</h4>

    You has receive new message from <b>Navitas Digital Food Safety</b>, <br>form: {{$user['name']}} (e-mail: {{$user['email']}}).<br>
    Message applies created task in the check list section.<br><br>

<div>
    <section class="panel panel-default">
        <div class="panel-body">
            <h4>Message from user:</h4>
            {{$inputs['message']}}
            <hr>
            <h4>Task Details:</h4>
            Task Title:<br>
            <strong>{{$target->task->content}}</strong><br><br>
            Task Action:<br>
            {{$target->action_todo}}<br><br>
            Assigned:<br>
            {{$target->assigned}}<br><br>
            Compliant:<br>
            {{$target->status?'YES':'NO'}}<br><br>
            Expiry Date:<br>
            {{$target->expiry_date}}<br><br>
            <h4>Signed By:</h4>
            <?php $signature = $target->signature?>
            {{$signature->name}} ({{$signature->role}})<br>
            <img src="{{$signature->signature}}" width="200" height="100"/>
        </div>
    </section>
</div>
<hr>
<div style="margin-top: 20px;"> Thank you for using Navitas system</div>
</body>
</html>