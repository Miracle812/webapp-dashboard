<?php namespace Model;

use Services\Notifications;

class FormsAnswers extends Models {

    protected $fillable = ['target_type','target_id','unit_id','form_log_id', 'options','assigned'];

    public function delete()
    {
        $this->values()->delete();
        $this->files()->delete();
        return parent::delete();
    }

    public function formLog()
    {
        return $this->belongsTo('\Model\FormsLogs', 'form_log_id');
    }

    public function assigned()
    {
        return $this->formLog->assigned_section();
    }

    public function values()
    {
        return $this->hasMany('\Model\FormsAnswersValues', 'answer_id');
    }

    public function files()
    {
        return $this->hasMany('\Model\FormsFiles', 'answer_id');
    }

    public function groupedRootItems()
    {
        $options = unserialize($this->options);
        $itemsLogsIds = isset($options['items_logs_ids']) ? $options['items_logs_ids'] : null;

        $formLog = $this->formLog;
        $itemsLog =  $formLog->items_log()->whereIn('id',$itemsLogsIds)->whereNull('parent_id')-> orWhere('parent_id', 0)->orderBy('sort','ASC')->get();
/*
        $itemsLog = $this->values()->leftJoin('forms_items_logs', function($join) {
                $join->on('forms_answers_values.item_log_id', '=', 'forms_items_logs.id');
            })->where(function($query){
                $query->whereNull('parent_id')-> orWhere('parent_id', 0);
        })->orderBy('sort','ASC')->get();
*/
        $added[]=$groupedItems=[];
        $x = 0;
        if($itemsLog){
            foreach($itemsLog as $key => $item){
                if(in_array($key, $added)){
                    continue;
                }
                if(!$item->parent_id){
                    if($item -> type !== 'tab'){
                        $groupedItems[] = ['item'=>[$item]];
                        $added[] = $key;
                    }
                    elseif($item -> type == 'tab'){
                        $tabs = [];
                        $tabs[] = $item;
                        $added[] = $key;
                        for($i = $key+1; $i<$itemsLog->count(); $i++){
                            if($itemsLog[$i]->type == 'tab'){
                                $tabs[] = $itemsLog[$i];
                                $added[] = $i;
                            }
                            else{
                                break;
                            }
                        }
                        $groupedItems[] = ['tabs'=>$tabs];
                    }
                }
                $x++;
            }
        }
        return $groupedItems;
    }

    public function getComplaintsAnswers()
    {
        $values = $this->values;
        return $values->filter(function($value){
            return (($value->itemLog->type == 'yes_no') && in_array('no',unserialize($value->value)));
        });
    }

    public function isCompliant()
    {
        $options = unserialize($this->options);
        return ( isset($options['compliant']) && ($options['compliant'] == 'no') ) ? false : true;
    }

    public function getSectionName()
    {
        return \Lang::get('/common/sections.'.$this->assigned().'.title');
    }

    public function getUrl( $type = 'item' )
    {
        $id = $this->id;
        if($assign = $this->assigned){
            $expl = explode(',',$assign);
            if(count($expl)==2 && ($expl[0]=='cleaning_schedules_items')){
                $item = \Model\CleaningSchedulesItems::find($expl[1]);
                if($item && ($submitted = $item->getLastSubmitted())){
                    $id = $submitted->id;
                }
            }
            elseif(count($expl)==2 && ($expl[0]=='check_list_items')){
                $item = \Model\CheckListItems::find($expl[1]);
                if($item && ($submitted = $item->getLastSubmitted())){
                    $id = $submitted->id;
                }
            }
        }
        switch ($this->assigned()){
            case 'check_list_daily'     : $url1 = 'check-list/submitted'; $url2 = $id.'/details'; break;
            case 'check_list_monthly'   : $url1 = 'check-list/submitted'; $url2 = $id.'/details'; break;
            case 'check_list'           : $url1 = 'check-list/submitted'; $url2 = $id.'/details'; break;
            case 'cleaning_schedule'    : $url1 = 'cleaning-schedule/submitted'; $url2 = $id.'/form-details'; break;
            case 'health_questionnaire' : $url1 = 'health-questionnaire/submitted'; $url2 = $id.'/details'; break;
            case 'haccp_forms'          : $url1 = 'haccp/forms/submitted'; $url2 = $id.'/details'; break;
            case 'knowledge_forms'      : $url1 = 'knowledge/forms/submitted'; $url2 = $id.'/details'; break;
            case 'temperatures_forms'   : $url1 = 'temperatures/forms/submitted'; $url2 = $id.'/details'; break;
        }
        switch ($type){
            case 'item' : $url = $url1.'/'.$url2; break;
            case 'section' : $url = $url1; break;
        }
        return $url;
    }

    public function getOutstandingTaskItemTitle($details = NULL)
    {
        return
            parent::getOutstandingTaskItemTitle($details) . '<br>' .
            'Form: '.$this -> formLog -> name;
    }
}