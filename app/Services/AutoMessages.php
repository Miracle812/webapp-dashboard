<?php
namespace Services;

class AutoMessages extends \BaseController
{
    public static function create($data)
    {
        $self = new self();
        $self -> createTasks($data);
    }

    public static function sendAutoMessages()
    {
        $self = new self();
        $self -> sendAutoMessagesTasks();
    }

    public function getTargetType($data)
    {
        switch ($data->getTable()){
            case 'users' : $target = 'creating_users'; break;
            case 'units' : $target = 'creating_units'; break;
            case 'temperatures_for_pods' : $target = 'pods_temps'; break;
        }
        return $target;
    }

    public function createTasks($data)
    {
        $now = \Carbon::now();
        $self = new self();
        $targetType = $self->getTargetType($data);
        $groups = \Model\AutoMessagesGroups::whereTargetType($targetType)->whereActive(1)->get();
        if(in_array($targetType,['creating_users','creating_units']))
        {
            foreach ($groups as $group){
                $delay = $triggerDate = null;
                if($group -> delay_type != 'none'){
                    $delay = $this->getDateByType($group->delay_type,$group->delay_value,$now);
                }
                $messages = $group -> messages->sortBy('sort');
                foreach ($messages as $message){
                    if(!$triggerDate)
                        $triggerDate = $delay ? $delay : $now;
                    else{
                        $triggerDate = $this->getDateByType($group->freq_type,$group->freq_value,$triggerDate);
                        $triggerDate = $this->checkWeekends($group->weekends,$triggerDate);
                        $timeExplode = explode(':',$group->send_hour);
                        $triggerDate = $triggerDate->setTime($timeExplode[0],$timeExplode[1]);
                    }
                    \Model\AutoMessagesTasks::create([
                        'message_id'   => $message->id,
                        'target_type'  => $data->getTable(),
                        'target_id'    => $data->id,
                        'trigger_date' => $triggerDate,
                        'on_email'     => $group->on_email,
                        'on_sms'       => $group->on_sms,
                    ]);
                }
            }
        }
        elseif(in_array($targetType,['pods_temps']))
        {
            foreach ($groups as $group){
                if($group->freq_type == 'amount_trigger') {
                    $amountVal = $group->freq_value;
                    $messages = $group->messages->sortBy('sort');
                    foreach ($messages as $message) {
                        $this->checkInvalidPodsTemps($amountVal,$data,$message);
                    }
                }
            }
        }
    }

    public function checkInvalidPodsTemps($amountVal,$temperature,$message)
    {
        $lastAmountTemps = \Model\TemperaturesForPods::
            whereUnitId($temperature->unit_id) ->
            whereAreaId($temperature->area_id) ->
            orderBy('id','DESC') ->
            take($amountVal) ->
            get();
        $log = $amount = null;
        if($lastAmountTemps->count() >= $amountVal){
            $filtered_collection = $lastAmountTemps->filter(function($item)
            {
                return $item->invalid_id;
            });
            if($filtered_collection->count() >= $amountVal){
                $amount = true;
                $logs = \Model\AutoMessagesLogs::
                    whereIn('target_id',$filtered_collection->lists('id'))->
                    whereTargetType($temperature->getTable())->
                    whereMessageId($message->id)->
                    get();
                $tasks = \Model\AutoMessagesTasks::
                    whereIn('target_id',$filtered_collection->lists('id'))->
                    whereTargetType($temperature->getTable())->
                    whereMessageId($message->id)->
                    get();
                $log = (($logs->count() > 0) || ($tasks->count() > 0)) ? true : false;
            }
        }
        if($amount && !$log){
            \Model\AutoMessagesTasks::create([
                'message_id'   => $message->id,
                'target_type'  => $temperature->getTable(),
                'target_id'    => $temperature->id,
                'trigger_date' => \Carbon::now(),
                'on_email'     => $message->group->on_email,
                'on_sms'       => $message->group->on_sms
            ]);
        }
    }

    public function checkWeekends($incWnd,$date){
        if(!$incWnd && $date->isWeekend()){
            $this->checkWeekends(0,$date->addDays(1));
        }
        return $date;
    }
    public function getDateByType($type,$value,$date){
        switch($type){
            case 'hours' : $date = $date->addHours($value); break;
            case 'days'  : $date = $date->addDays($value); break;
            case 'weeks' : $date = $date->addWeeks($value); break;
            case 'months': $date = $date->addMonths($value); break;
        }
        return $date;
    }

    public function sendAutoMessagesTasks()
    {
        $now = \Carbon::now();
        $tasks = \Model\AutoMessagesTasks::where('trigger_date','<=',$now)->get();
        foreach($tasks as $task){
            $taskCopy = $task->replicate()->toArray();
            $message = $task -> message;
            $target = $this -> getTargetData($task);
            if(!$message || !$target){
                $task -> delete();
                continue;
            }
            $title = $message -> title;
            $messages = $this->prepareTextMessage($message,$target);
            //echo $messages;
            $email = $this->getEmail($target);
            $sended = [];
            if($task -> on_email){
                if($title && $message && $email) {
                    $data = ['messages' => $messages, 'title' => $title];
                    \Mail::send('emails.messages.auto-messages', $data, function ($message) use ($title, $email) {
                        $message->to($email)->subject($title);
                    });
                    $task -> update(['on_email' => 0]);
                }
                else{
                    $sended['on_email'] = 'error';
                }
            }
            if($task -> on_sms){
                $textlocal = new \Textlocal(\Config::get('textlocal.username'), \Config::get('textlocal.hash'));
                $numbers = [$this->getMobilePhone($target)];
                $sender = 'Navitas';
                $messages = preg_replace('/&#x2103;/', '', $messages);
                $clear = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 -.]/', ' ', urldecode(html_entity_decode(strip_tags($messages))))));
                try {
                    $result = $textlocal->sendSms($numbers, substr($clear,0,160), $sender);
                } catch (Exception $exception) {
                    $sended['on_sms'] = 'error';
                    Log::error($exception->getMessage());
                }

                if(!isset($sended['on_sms'])){
                    $task -> update(['on_sms' => 0]);
                }
            }

            if(!in_array('error',$sended)){
                \Model\AutoMessagesLogs::firstOrCreate($taskCopy);
                $task -> delete();
            }
        }
    }

    public function getTargetData($task){
        $target = null;
        switch($task->target_type){
            case 'users' : $target = \User::find($task->target_id); break;
            case 'units' : $target = \Model\Units::find($task->target_id); break;
            case 'temperatures_for_pods' : $target = \Model\TemperaturesForPods::find($task->target_id); break;
        }
        return $target;
    }

    public function getEmail($target){
        $email = null;
        switch($target->getTable())
        {
            case 'users' :
            case 'units' : $email = $target -> email; break;
            case 'temperatures_for_pods' : $email = $target ->unit->email; break;
        }
        return $email;
    }

    public function getMobilePhone($target){
        $phone = null;
        switch($target->getTable())
        {
            case 'users' :
            case 'units' : $phone = $target -> mobile_phone; break;
            case 'temperatures_for_pods' : $phone = $target -> unit -> mobile_phone; break;
        }
        return $phone;
    }

    public function prepareTextMessage($message,$target)
    {
        $data = [];
        switch($target->getTable()){
            case 'users' :
                $data = [
                    'name'=>$target->first_name,
                    'surname'=>$target->surname,
                    'email'=>$target->email,
                    'username'=>$target->username];
                break;
            case 'units' :
                $data = [
                    'name'=>$target->name,
                    'email'=>$target->email,
                    'address'=>($target->post_code.' '.$target->city.', '.$target->street_number),
                    'hq_name'=>$target->headquarter->name];
                break;
            case 'temperatures_for_pods' :
                $rules = $target->rule;
                $data = [
                    'temperature'=>$target->temperature.' &#x2103; ',
                    'area_name'=>$target->area->name,
                    'valid_range'=>('Valid min:'.$rules->valid_min.' &#x2103; , Valid max:'.$rules->valid_max.' &#x2103; '),
                    'warning_range'=>('Warning min:'.$rules->warning_min.' &#x2103; , Warning max:'.$rules->warning_max.' &#x2103; '),
                    'unit_name'=>$target->unit->name];
                break;
        }

        $message = $message -> message;
        preg_match_all ('/{([^{]+?)}/', $message, $matches);
        if($matches){
            foreach ($matches[1] as $key => $match){
                if(isset($data[$match]))
                    $message = str_replace('{'.$match.'}', $data[$match], $message);
            }
        }
        return $message;
    }
}