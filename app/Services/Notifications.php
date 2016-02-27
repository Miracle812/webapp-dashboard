<?php
namespace Services;

class Notifications extends \BaseController {

    public static function create($object)
    {
        $self = new self();
        $validToCreate = $self -> validToCreate($object);
        if($validToCreate) {
            $message = (isset($options['message']) && !empty($options['message'])) ? $options['message'] : $self->getMessageByTargetType($object);
            $receivers = (isset($options['receivers']) && !empty($options['receivers'])) ? $options['receivers'] : $self->getReceiversByTargetType($object);
            $notifi = new \Model\Notifications();
            $notifi->target_id = $object->id;
            $notifi->target_type = $object->getTable();
            $notifi->receivers_id = implode(',', $receivers);
            $notifi->message = $message;
            return $notifi->save();
        }
    }

    public static function updateLogs($idUser,$idNotifi,$status = 'read')
    {
        $log = \Model\NotificationsLogs::firstOrCreate(['user_id'=>$idUser,'notification_id'=>$idNotifi]);
        switch($status)
        {
            case 'read': $log -> read = 1; break;
            case 'removed': $log -> removed = 1; break;
        }
        return $log -> update();
    }

    public function getMessageByTargetType($object)
    {
        switch($object->getTable()){
            case 'rating_stars_logs' :
                $prev = $this->getPrevRecord($object);
                $message = 'The Food Safety Rating at the '.$object->unit->name.' has dropped from '.$prev->stars.' to '.$object->stars.' stars.';
                break;
            case 'support_tickets' :
                $message = 'Support ticket has been created.';
                break;
            case 'support_replies' :
                $message = 'Reply to ticket has been received.';
                break;
            default : $message = ''; break;
        }
        return $message;
    }

    public function getReceiversByTargetType($object)
    {
        $adminsIds = \User::whereHas('roles',function($query){
            $query->whereIn('name',['admin']);
        })->get();
        switch($object->getTable())
        {

            case 'rating_stars_logs' :
                $unit = $object->unit;
                $headquarter = $unit->headquarter;
                $hqManagersIds = \User::whereHas('roles',function($query){
                    $query->whereIn('name',['hq-manager']);
                })->whereHas('headquarters',function($query)  use($headquarter){
                    $query->where('headquarter_id',$headquarter->id);
                })->get();

                $areaManagersIds = \User::whereHas('roles',function($query){
                    $query->whereIn('name',['area-manager']);
                })->whereHas('units',function($query)  use($unit){
                    $query->where('unit_id',$unit->id);
                })->get();
                $ids = $adminsIds->merge($hqManagersIds)->merge($areaManagersIds)->lists('id');
            break;
            case 'support_tickets' :
                $membersIds = (($object->category->members) ? $object->category->members->lists('id') : []);
                if(!count($membersIds)) {
                    $adminsIds = \User::whereHas('roles',function($query){
                        $query->where('name','admin');
                    })->get();
                    $membersIds = $adminsIds->lists('id');
                }
                $ids = $membersIds;
            break;
            case 'support_replies' :
                $ids = $object->ticket->recipients();
                if(($key = array_search(\Auth::user()->id, $ids)) !== false) {
                    unset($ids[$key]);
                }
                break;
            default : $ids = []; break;
        }
        return $ids;
    }

    public function validToCreate($object)
    {
        switch($object->getTable())
        {
            case 'rating_stars_logs' :
                $prev = $this->getPrevRecord($object);
                $out = ($prev && ($prev->stars > $object->stars) && ($object->stars < 4)) ? true : false;
            break;
            case 'support_tickets' :
                $out =  true;
            break;
            case 'support_replies' :
                $out =  true;
            break;
            default : $out = true; break;
        }
        return $out;
    }

    public function getPrevRecord($object, $filters = ['unit_id'])
    {
        $table = $object -> getTable();
        $maxId = \DB::table($table);
        foreach($filters as $filter){
            $maxId = $maxId -> where($filter, '=', $object->$filter);
        }
        $maxId = $maxId -> where('id','<',$object->id) -> max('id');
        return $maxId ? \DB::table($table)->find($maxId) : null;
    }








/*








    public static function createHeaderContent($notifications){
        $count = count($notifications);
        $content = '';
        foreach ($notifications as $row){
            $content .= $row->{$row->target_type}?$row->{$row->target_type}->getIssueNotification('header'):'';
        }
        return ['content'=>$content,'count'=>$count];
    }

    public function updateTrainingsRecords(){
        $user = $this->user;
        $currentDate = $this->currentDate;
        $notifications = $this->notifications;

        $expiredTrainingRecords = \Model\TrainingRecords::
            where('unit_id','=',$user->unit()->id)->
            get();
        foreach($expiredTrainingRecords as $row){

            if(!$row -> staff) continue;
            $plusMonth = strtotime('+1 month');
            $refreshDate = strtotime($row -> date_refresh);

            $messageBefore = $row -> staff -> fullname().': Training Record "'.$row->name.'" is due to expire on ('.date('Y-m-d', strtotime($row->date_refresh)).'!). Please look to refresh this training.';
            $messageAfter = $row -> staff -> fullname().': Training Record "'.$row->name.'" expired on ('.date('Y-m-d', strtotime($row->date_refresh)).'!). Please refresh this training.';
            $message = $type =  false;
            $isNotifi = $notifications::where('unit_id','=',$user->unit()->id)->where('target_type','=','training_records')->where('target_id','=',$row->id);

            if ($plusMonth > $refreshDate && strtotime('now') < $refreshDate)
            {
                $type = 'due_to_expire';
                $message  =  $messageBefore;
            }

            elseif(strtotime('now') > $refreshDate)
            {
                $type = 'expired';
                $message  =  $messageAfter;
            }
            else
            {
                continue;
            }

            if($isNotifi->where('type','=',$type)->first()) continue;

            if($message){
                $add = new \Model\Notifications();
                $add -> user_id =  $user -> id;
                $add -> unit_id =  $user -> unit() -> id;
                $add -> target_id =  $row -> id;
                $add -> target_type = 'training_records';
                $add -> message = $message;
                $add -> type = $type;
                $add -> hide = 0;
                $add -> status = 0;
                $add -> save();
            }
        }
    }
    public function updateProbesTemperature(){
        $user = $this->user;
        $currentDate = $this->currentDate;
        $notifications = $this->notifications;
    }

*/
}