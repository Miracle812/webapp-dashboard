<?php namespace Services;

class Messages extends \BaseController
{
    public static function getRecipients($links)
    {
        $self = new self();
        $data = [];
        $user = \Auth :: user();
        $role = $user -> role() -> name;

        $headquarters = \Model\Headquarters::whereActive(1);
        $units        = \Model\Units::whereActive(1);

        switch ($role) {
            case 'admin':
                $hqIds    = $headquarters -> lists('id');
                $unitsIds = $units -> lists('id');
                break;
            case 'hq-manager':
                $hqIds    = $user -> headquarters -> lists('id');
                $unitsIds = $units -> whereHas('headquarter',function($q)use($hqIds){
                                $q -> whereIn ('id',$hqIds)->whereActive(1);
                            })->lists('id');
                break;
            case 'area-manager' :
            case 'local-manager':
                $hqIds    = $user -> headquarters -> lists('id');
                $unitsIds = $user -> units -> lists('id');
                break;
        }

        foreach ($links as $role) {
            $recipients = \User::whereActive(1)->whereHas('roles', function($q)use($role){
                $q -> where('name', $role);
            });
            switch ($role) {
                case 'admin':
                    break;
                case 'hq-manager':
                    $recipients =  $recipients -> whereHas('headquarters',function($q)use($hqIds){
                        $q -> whereIn ('headquarters.id',$hqIds);
                    });
                    break;
                case 'area-manager' :
                case 'local-manager':
                    $recipients =  $recipients -> whereHas('units',function($q)use($unitsIds){
                        $q -> whereIn ('units.id',$unitsIds);
                    });
                    break;
            }
            if($recipients->count()) {
                $data[] = ['text'=>\Lang::get('common/general.' . $role), 'children'=>$self->prepareRecipients($recipients->get())];
            }
        }

        return $data;
    }

    public function prepareRecipients($users)
    {
        if (!$users) return [];
        $out = [];
        foreach($users as $user){
            $table = $user -> getTable();
            switch ($table) {
                case  'users' :
                    $data = [
                        'id'    => $user->id,
                        'text'  => $user->fullname(),
                        'email' => $user->email,
                        'place' => $this->getUserPlace($user),
                        'online'=> $user->isOnline()?'on':'off',
                        'avatar'=> \URL::to($user->avatar()),
                        'role'  => \Lang::get('/common/roles.'.$user->role()->name)];
                    break;
                default : $data = []; break;
            }
            $out[]=$data;
        }
        return $out;
    }

    public function getUserPlace($user)
    {
        $name = '';

        switch ($user->role()->name) {
            case 'admin':
                $name = 'Navitas Admin';
                break;
            case 'hq-manager':
                $name = $user->headquarter()->name;
                break;

            case 'area-manager' :
                $name = $user->headquarter()->name;
                break;
            case 'local-manager':
                $name = $user->unit() ? $user->unit()->name : 'N/A' ;
                break;
        }
        return $name;
    }
    public static function getGroupedMessages()
    {
        $user = \Auth::user();
        return $user->lastGroupedMessages()->filter(function ($item) use ($user) {
            $status = \Model\MessagesRecipients::whereMessageId($item->id)->whereUserId($user->id)->first();
            return in_array($status->status, [0]);
        });
    }

    public static function createThread( $inputs )
    {
        $rules = [
            'message'     => 'required|min:10',
            'recipients'  => 'required',
        ];
        $validator = \Validator::make($inputs, $rules);
        if (!$validator -> fails()){
            $user = \Auth::user();
            $message = new \Model\Messages();
            $message -> fill( $inputs );
            $message -> author_id = $user -> id;
            $save = $message -> save();
            if($save){
                $recipients = array_merge($inputs['recipients'],[$user -> id]);
                self::createRecipients($recipients,$message->id);
                self::sendMessages($message);
                return ['status'=>'success'];
            }
        }
        else
            return ['status'=>'errors', 'errors' => $validator -> messages() -> toArray()];
    }

    public static function sendTargetMessage($inputs, $target)
    {
        $rules = [
            'title'       => 'required|min:10|max:100',
            'message'     => 'required|min:10',
            'recipient'   => 'required|email',
        ];
        $validator = \Validator::make($inputs, $rules);
        if (!$validator -> fails()){
            $user = \Auth::user();
            $user = ['email'=>$user->email,'name'=>$user->fullname()];
            $mail = \Mail::send('emails.messages.'.$target->getTable(), compact('target','inputs','user'), function($message) use($inputs)
            {
                $message->to($inputs['recipient'])->subject($inputs['title']);
            });
            return $mail;
        }
        else
            return $validator;
    }

    public static function createRecipients(array $inputs, $msgId)
    {
        foreach($inputs as $userId){
            $recipient = new \Model\MessagesRecipients();
            $recipient -> message_id = $msgId;
            $recipient -> user_id = $userId;
            $recipient -> save();
        }
    }

    public static function sendMessages(\Model\Messages $message)
    {
        $recipients = $message -> recipients;
        foreach($recipients as $recipient)
        {
            $data = [];
            if($recipient->id !== $message->author->id)
            {
                $data = [
                    'msg' => $message -> message,
                    'author' => $message -> author,
                    'recipient' => $recipient,
                    'recipient_url' => '/messages-access/'.\Crypt::encrypt(self::getRecipientUrl($recipient,$message -> id)),
                ];
                \Mail::send('emails.navichat.new_message', $data, function ($mail) use ($recipient) {
                    $mail->to($recipient -> email, $recipient -> fullname())->subject('Navitas message from: ' . $recipient -> fullname());
                });
            }
        }
    }

    public static function getRecipientName($recipient)
    {
        $table  = $recipient -> getTable();
        switch ($table) {
            case  'users' :

                $name = $recipient -> fullname();
                break;
            case  'suppliers' :
                $name = $recipient -> contact_person;
                break;
            case  'units_contacts' :
                $name = $recipient -> fullname;
                break;
            default : $name = ''; break;
        }
        return $name;
    }

    public static function getRoleName($recipient)
    {
        $table  = $recipient -> getTable();
        switch ($table) {
            case  'users' :
                $role = \Lang::get('/common/general.roles.'.$recipient->role()->name);
                $target = $recipient -> hasRole('local-manager') ? $recipient -> unit() -> name : $recipient -> headquarter() -> name;
                $name = $role .  ' of ' . $target;
                break;
            case  'suppliers' :
                $name = \Lang::get('/common/general.supplier').': '.$recipient -> name.'';
                break;
            case  'units_contacts' :
                $name = '('.\Lang::get('/common/general.unit_contact').')';
                break;
            default : $name = ''; break;
        }
        return $name;
    }

    public static function getRecipientUrl($recipient,$threadId)
    {
        $table  = $recipient -> getTable();
        return $recipient->id.','.$table.','.$threadId.','.\Carbon::now()->timestamp;
    }

    public static function getRecipientss($recipients)
    {
        $names=[];
        foreach($recipients as $recipient){
            $recipient = $recipient -> recipient();
            $names[] = self::getRecipientName($recipient) .' - '.  self::getRoleName($recipient);
        }
        return $names;
    }

















    public static function create($data)
    {
        $task = self::check($data);
        if(!$task) {
            $task = new \Model\OutstandingTask();
            $task -> unit_id     = $data -> unit_id;
            $task -> target_id   = $data -> id;
            $task -> target_type = $data -> getTable();
            $task -> fill(self:: fillDataBySection($data)) -> save();
        }
        return $task;
    }

    public static function update($data)
    {
        if (!$data)
            return false;

        $task = self::check($data);
        if (!$task){
            return  self::create( $data );
        }
        else{
            $task -> fill( self::fillDataBySection( $data ) ) -> update();
            return $task;
        }
    }

    public static function check($data)
    {
        return \Model\OutstandingTask :: whereTargetType( $data -> getTable() )->whereTargetId( $data -> id )->first();
    }

    public static function fillDataBySection( $data )
    {
        if (!$data) return [];
        switch ($data->getTable()) {
            case  'food_incidents' :
                $sectionData = ['action_todo' => $data -> action_todo, 'status' => 0];
                break;
            case  'check_list_actions' :
                $sectionData = ['action_todo' => $data -> action_todo, 'status' => $data->status, 'expiry_date' => $data->expiry_date];
                break;
            case  'temperatures_for_goods_in' :
                $sectionData = ['action_todo' => $data -> action_todo, 'status' => 0, 'expiry_date' => \Carbon::tomorrow()->setTime(3, 0, 0)];
                break;
            case  'temperatures_for_pods' :
            case  'temperatures_for_probes' :
            case  'health_questionnaires' :
            case  'navinotes' :
                $sectionData = ['status' => 0];
                break;
            case  'training_records' :
                $sectionData = ['status' => 0, 'expiry_date' => $data->date_refresh];
                break;
            default : $sectionData = []; break;
        }
        return $sectionData;
    }

    public static function updateTarget( $target , $input ) //input = [expiry_date, status]
    {
        if ( !$target ) return false;

        switch ($target -> getTable()) {
            case  'check_list_actions' :
            case  'temperatures_for_goods_in' :
                $data = array_merge($input,['compliant' => $input['status']]);
                $update = $target -> fill ($data) -> update();
                break;
            case  'training_records' :
            case  'temperatures_for_pods' :
            case  'temperatures_for_probes' :
                $update = false;
                break;
            default : $update = false; break;
        }
        return $update;
    }

    public static function getDatatable($data) //data = outstandingTasks
    {
        if($data->count()){
            foreach($data as $row)
            {
                $expiryDate = $row -> expiry_date == '0000-00-00 00:00:00' ? \Lang::get('/common/general.not_applicable') : $row->expiry_date;
                $actionButton = $row -> getActionButton();
                $sectionName  = $row -> getSectionName();
                $linkedTitle  = $row -> getLinkedTitle();
                $options[] = [strtotime($row->created_at), $row->created_at(),$linkedTitle,$sectionName,$expiryDate,$actionButton];
            }
        }
        return isset($options) ? $options : [];
    }
}