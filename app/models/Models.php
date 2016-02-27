<?php namespace Model;

class Models extends \Eloquent
{
    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function temperaturesForProbes()
    {
        return $this->belongsTo('\Model\TemperaturesForProbes', 'target_id');
    }

    public function temperaturesForPods()
    {
        return $this->belongsTo('\Model\TemperaturesForPods', 'target_id');
    }

    public function temperaturesForGoodsIn()
    {
        return $this->belongsTo('\Model\TemperaturesForGoodsIn', 'target_id');
    }

    public function trainingRecords()
    {
        return $this->belongsTo('\Model\TrainingRecords', 'target_id');
    }

    public function foodIncidents()
    {
        return $this->belongsTo('\Model\FoodIncidents', 'target_id');
    }

    public function navinotes()
    {
        return $this->belongsTo('\Model\Navinotes', 'target_id');
    }

    public function notifications()
    {
        return $this->belongsTo('\Model\Notifications', 'target_id');
    }

    public function supportTickets()
    {
        return $this->belongsTo('\Model\SupportTickets', 'target_id');
    }

    public function supportReplies()
    {
        return $this->belongsTo('\Model\SupportReplies', 'target_id');
    }

    public function healthQuestionnaires()
    {
        return $this->belongsTo('\Model\HealthQuestionnaires', 'target_id');
    }

    public function cleaningSchedulesItems()
    {
        return $this->belongsTo('\Model\CleaningSchedulesItems', 'target_id');
    }

    public function checkListItems()
    {
        return $this->belongsTo('\Model\CheckListItems', 'target_id');
    }

    public function formsAnswers()
    {
        return $this->belongsTo('\Model\FormsAnswers','target_id');
    }

    public function target()
    {
        return $this->{$this->target_type};
    }

    public function options ($type = null)
    {
        $parent = \Model\OptionsMenu::with([ 'childrens' => function($query) use($type) {
            if($type == 'inputs')
                $query->whereNotIn('type',['feature']);
        }])->whereIdentifier($this->getTable())->whereType('root')->first();
        $optIds = ($parent && $parent -> childrens) ? $parent -> childrens -> lists('id') : [];
        return $this->hasMany('\Model\Options', 'target_id')->whereTargetType($this->getTable())->whereIn('option_id',$optIds);
    }

    public function getDisabledModules()
    {
        $option = $this->getOption('manage_unit_modules');
        if($option){
            $values = unserialize($option->values);
            return isset($values['disabled_modules'])?$values['disabled_modules']:[];
        }
        return false;
    }

    public function hasOption( $identifier )
    {
        $options = $this -> options() -> get();
        if($options->count())
            foreach ($options as $option) {
                if( $option->option->identifier == $identifier )
                {
                    return true;
                }
            }
        return false;
    }

    public function getOption( $identifier )
    {
        $options = $this -> options() -> get();
        if($options->count())
            foreach ($options as $option) {
                if( $option->option->identifier == $identifier )
                {
                    return $option;
                }
            }
        return null;
    }

    public function getOptionById( $id )
    {
        $options = $this -> options() -> get();
        if($options->count())
            foreach ($options as $option) {
                if( $option->option->id == $id )
                {
                    return $option;
                }
            }
        return null;
    }

    public function checkAccess()
    {
        $user = \Auth::user();
        if ( $user -> hasRole('local-manager') || $user -> hasRole('visitor') )
            return $this -> unit_id == $user -> unit() -> id;
        elseif ( $user -> hasRole('hq-manager') ) {
            return in_array($this -> unit_id, $user -> headquarter() -> getUnitsId());
        }
        elseif ( $user -> hasRole('admin') ) {
            return true;
        }
        return false;
    }

    public function date($date=null)
    {
        $tz = \Config::get('app.timezone');
        if(\Auth::check()){
            $tz = \Auth::user()->timezone;
        }
        if(is_null($date)) {
            $date = $this->created_at;
        }
        $date = \Carbon::createFromFormat('Y-m-d H:i:s',$date,'UTC')->timezone($tz);
        $date = ($date->timestamp <= 0) ? '2010-10-10 10:10:10' : $date;
        return \String::date($date);
    }

    public function created_at($date=null)
    {
        $tz = \Config::get('app.timezone');
        if(\Auth::check()){
            $tz = \Auth::user()->timezone;
        }
        if(is_null($date)) {
            $date = $this->created_at;
        }
        $date = ($date->timestamp <= 0) ? '2010-10-10 10:10:10' : $date;
        return \Carbon::createFromFormat('Y-m-d H:i:s',$date,'UTC')->timezone($tz)->format('d-m-Y H:i');
    }

    public function updated_at()
    {
        return $this->date($this->updated_at);
    }

    public function expiry_date()
    {
        $tz = \Config::get('app.timezone');
        if(\Auth::check()){
            $tz = \Auth::user()->timezone;
        }
        return \Carbon::createFromFormat('Y-m-d H:i:s',$this->expiry_date,'UTC')->timezone($tz)->format('d-m-Y H:i');
    }

    public function date_start()
    {
        return $this->date($this->start);
    }

    public function date_end()
    {
        return $this->date($this->end);
    }

    public function getSectionName()
    {
        $table  = $this -> getTable();
        return \Lang::get('/common/sections.'.$table.'.title');
    }

    public function filterValidAscii($text)
    {
        return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $text);
    }

    public function getOutstandingTaskItemTitle($details = NULL)
    {
        return '<a class="font-bold" href="'.$this->getUrl('item').'">'.\Lang::get('/common/sections.'.$this -> getTable().'.messages.outstanding_tasks').'</a>';
    }

    public static function moreLessButton($bid){
        return '<button class="text-xs label btn-green btn pull-right" data-toggle="class:show animated fadeInRight" data-target="#'.$bid.'">
                <i class="fa fa-plus text"></i>
                <span class="text">More</span>
                <i class="fa fa-minus text-active"></i>
                <span class="text-active">Less</span>
                </button>';
    }

    public function getNotificationTemplate($dest,$content, $bid){
        $outerClass = $dest == 'header' ? 'm-b b-b' : '';
        $ico        = $dest == 'header' ? '<span class="pull-left thumb-sm text-center"><i class="fa fa-info fa-2x text-danger"></i></span>' : '';
        $createdAt  = $dest == 'header' ? '<span class="text-muted text-xs clear">Created at: '.$this->created_at().'</span>' : '';

        return
            '<div class="'.$outerClass.'">'.
                $ico.
                '<span class="media-body block m-b-none">'.$this->moreLessButton($bid).
                        $createdAt.
                        $content.
                    '</span>'.
                '</span>'.
            '</div>';
    }

    public function getSchedulesDate()
    {
        $start =  \Carbon::parse($this->start)->timezone($this->tz);
        $end   =  \Carbon::parse($this->end)->timezone($this->tz);
        if($this -> all_day){
            $start = $start -> format('d-m-Y');
            $end   = $end   -> format('d-m-Y');
            $dispDate = ($start==$end) ? $start : $start . ' : ' . $end;
        }else{
            $start = $start -> format('d-m-Y H:i');
            $end   = $end   -> format('d-m-Y H:i');
            $dispDate = ($start==$end) ? $start : $start . ' : ' . $end;
        }
        return $dispDate;
    }
}
