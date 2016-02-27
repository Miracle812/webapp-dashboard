<?php namespace Services;

class OutstandingTasks extends \BaseController
{
    public static $extra_data;
    public static function create($data,$inputs=[])
    {
        return null;
        /*
        self::$extra_data = $inputs;

        $task = self::check($data);
        if(!$task) {
            $insertData = self:: fillDataBySection($data);
            if($insertData){
                $task = new \Model\OutstandingTask();
                $task -> unit_id     = $data -> unit_id;
                $task -> target_id   = $data -> id;
                $task -> target_type = $data -> getTable();
                $task -> fill($insertData) -> save();
            }
            else{
                return null;
            }
        }
        return $task;
        */
    }

    public static function update($data)
    {
        return false;
        /*
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
        */
    }

    public static function check($data)
    {
        return \Model\OutstandingTask :: whereTargetType( $data -> getTable() )->whereTargetId( $data -> id )->first();
    }

    public static function getFormsExpiryDate($data)
    {
        //[1 => 'health_questionnaires', 2 => 'check_list_daily', 3 => 'check_list_monthly' , 4=>'cleaning_schedule'];
        $assignedId = $data->formLog->assigned_id;
        switch($assignedId){
            //case '1': ;break;
            case '2': return \Carbon::tomorrow()->setTime(3, 0, 0);break;
            case '3': return \Carbon::now()->endOfMonth(); break;
            case '4':
                $target = $data->assigned ? (explode(',',$data->assigned)) : [];
                if(count($target) == 2){
                    $section = \DB::table($target[0])->find($target[1]);
                    return $section ? $section -> end : null;
                }
                break;
        }
    }

    public static function fillDataBySection( $data )
    {
        if (!$data) return [];
        $sectionData = [];
        switch ($data->getTable()) {
            case  'forms_answers' :
                $options = unserialize($data->options);
                $status = isset($options['compliant']) ? ($options['compliant'] === 'yes' ? 1 : 0) : null;
                if (is_numeric($status)) {
                    $expiryDate = self::getFormsExpiryDate($data);
                    $sectionData = (isset($options['compliant']) && $status === 0) ? ['action_todo' => 'Non compliant form data.', 'status' => $status, 'expiry_date' => $expiryDate] : [];
                }
                break;
            case  'new_cleaning_schedules_items2' :
                $sectionData = ['action_todo' => ( ($submitted = $data -> getLastSubmitted()) ? $submitted -> summary : '' ), 'status' => $data -> isCompleted(), 'expiry_date' => $data -> end ];
                break;
            case  'new_cleaning_schedules_items' :
            case  'check_list_items' :
                $sectionData = ['action_todo' => ( ($submitted = $data -> getLastSubmitted()) ? $submitted -> summary : '' ), 'status' => $data -> isCompleted(), 'expiry_date' => $data -> expiry];
                break;
            case  'food_incidents' :
                $sectionData = ['action_todo' => $data -> action_todo, 'status' => 0];
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
        }
        return $sectionData;
    }

    public static function updateTarget( $target , $input ) //input = [expiry_date, status]
    {
        if ( !$target ) return false;
        switch ($target -> getTable()) {
            case  'check_list_actions' :
                $srvDiary = new \Services\CompilanceDiary();
                $input['status'] ? $srvDiary::delete($target) : $srvDiary::create($target);
            //case  'new_cleaning_schedules_items' :
            //    $data = array_merge($input,['completed' => $input['status'], 'summary'=>$input['action_todo']]);
            //    $update = $target -> fill ($data) -> update();
            //    break;
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
                if(!$row->target()){
                    $row->delete();
                    continue;
                }
                $target = $row->target();
                if(!$target) {$row->delete(); continue;};
                switch($row->target_type){
                    case 'new_cleaning_schedules_items' : $expiryDate = \Carbon::parse($row->expiry_date)->format('d/m/Y H:i'); break;
                    case 'new_cleaning_schedules_items2' :
                        $expiryDate = \Carbon::parse($target->end)->timezone($target->task->tz)->format('d/m/Y H:i');
                        break;
                    case 'check_list_items' : $expiryDate = \Carbon::parse($row->expiry_date)->format('d/m/Y H:i'); break;
                    case 'forms_answers' : $expiryDate = \Carbon::parse($row->expiry_date)->format('d/m/Y H:i'); break;
                    default : $expiryDate = $row->expiry_date(); break;
                }
                $expiryDate = $row -> expiry_date == '0000-00-00 00:00:00' ? \Lang::get('/common/general.not_applicable') : $expiryDate;
                $actionButton = $row -> getActionButton();
                $sectionName  = $row -> getSectionName();
                $linkedTitle  = $row -> getLinkedTitle();
                $options[] = [strtotime($row->created_at), $row->created_at(),$linkedTitle,$sectionName,$expiryDate,$actionButton];
            }
        }
        return isset($options) ? $options : [];
    }
}