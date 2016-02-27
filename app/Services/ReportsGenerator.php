<?php
namespace Services;

class ReportsGenerator extends \BaseController {
    public $form_data;

    public function getReport(){
        $html  = '';

        $html .= isset($this -> form_data['goods_in_records']) ?          ($this->getGoodsInRecords()?:'No Records') : '' ;
        $html .= isset($this -> form_data['calendar']) ?                  ($this->getCalendar()?:'No Records') : '' ;
        $html .= isset($this -> form_data['cleaning_records']['head']) ?  ($this->getCleaningSchedules()?:'No Records') : '' ;
        $html .= isset($this -> form_data['daily_check_list']) ?          ($this->getCheckList(2)?:'No Records') : '' ;
        $html .= isset($this -> form_data['monthly_check_list']) ?        ($this->getCheckList(3)?:'No Records') : '' ;
        $html .= isset($this -> form_data['training_records']['head']) ?  ($this->getTrainingRecords()?:'No Records') : '' ;
        $html .= isset($this -> form_data['staff']) ?                     ($this->getStaff()?:'No Records') : '' ;
        $html .= isset($this -> form_data['suppliers']) ?                 ($this->getSuppliers()?:'No Records') : '' ;
        $html .= isset($this -> form_data['temperatures']['head']) ?      ($this->getTemperatures()?:'No Records') : '' ;
        $html .= isset($this -> form_data['food_incidents']) ?            ($this->getFoodIncidents()?:'No Records') : '' ;
        return $html ? : NULL;
    }
    public function getSelectedReports(){
        $selRep = [];
        $selRep[] = isset($this -> form_data['goods_in_records']) ?          'Goods In Records' : '' ;
        $selRep[] = isset($this -> form_data['calendar']) ?                  'Calendar' : '' ;
        $selRep[] = isset($this -> form_data['cleaning_records']['head']) ?  'Cleaning Records' : '' ;
        $selRep[] = isset($this -> form_data['daily_check_list']) ?          'Daily Check List' : '' ;
        $selRep[] = isset($this -> form_data['monthly_check_list']) ?        'Monthly Check List' : '' ;
        $selRep[] = isset($this -> form_data['training_records']['head']) ?  'Training Records' : '' ;
        $selRep[] = isset($this -> form_data['staff']) ?                     'Staff' : '' ;
        $selRep[] = isset($this -> form_data['suppliers']) ?                 'Suppliers' : '' ;
        $selRep[] = isset($this -> form_data['temperatures']['head']) ?      'Temperatures' : '' ;
        $selRep[] = isset($this -> form_data['food_incidents']) ?            'Food Incidents' : '' ;
        return $selRep ? : NULL;
    }

    public function getCalendar(){
        $html = '';

        $data = \Model\ComplianceDiary::where('unit_id',$this->auth_user->unitId());
        $data = $this -> getDateRange($data,'start');
        $data = $data -> orderBy('start', 'ASC');
        $data = $data -> get();
        $headerColumns = ['Created','Name', 'Description','Start Event Date'];
        $dataRows = [];
        if($data){
            foreach($data as $item){
                $dataRows[] = [
                    $item->created_at(),
                    $item->name,
                    $item->description,
                    $item->start,
                ];
            }
        }
        if($dataRows){
            $html .= $this->defaultHtmlBuilder('start',$dataRows,$headerColumns,['title'=>'Compliance Diary']);
            $html .= $this->defaultHtmlBuilder('end');
        }
        else {
            $html .=  $this->getNoRecords('Compliance Diary');
        }
        return $html;
    }

    public function getCleaningSchedules(){
        $html = '';
        $headerColumns = ['Task date','Name','Description', 'Completed'];
        $dataRows = [];
        $unitId = \Auth::user() -> unit() -> id;
        $submitted = \Model\CleaningSchedulesSubmitted::whereUnitId($unitId);
        $submitted = $this -> getDateRange($submitted);
        $submitted = $submitted -> orderBy('start', 'ASC');
        $submitted = $submitted -> get();
        if($submitted->count())
            foreach ($submitted as $record)
            {
                $title = '<span class="font-bold">'.$record -> title.'</span>';
                if($record -> form_name)
                    $title = $title.('<BR>'.'<small class="text-muted">Form: '.$record -> form_name.'</small>');
                if($record -> staff_name)
                    $title = $title.('<BR>'.'<small class="text-muted">Staff: '.$record -> staff_name.'</small>');
                $dataRows[] = [
                    $record->getSchedulesDate(),
                    $title,
                    $record->description,
                    ($record -> completed ? '<span class="font-bold text-success">Completed</span>' : '<span class="font-bold text-danger">Not Completed</span>'),
                ];
            }
        if($dataRows){
            $html .= $this->defaultHtmlBuilder('start',$dataRows,$headerColumns,['title'=>'Cleaning Schedule']);
            $html .= $this->defaultHtmlBuilder('end');
        }
        else {
            $html .=  $this->getNoRecords('Cleaning Schedule');
        }
        return $html;
    }

    public function getCheckList($type){
        $html = '';
        $data = \Model\FormsAnswers::whereIn('form_log_id', function($query){
            $query->select('id')->from('forms_logs')->where('assigned_id', 2);
        })->whereIn('unit_id',$this->getUnitsId());
        $data = $this -> getDateRange($data);
        $data = $data -> orderBy('created_at', 'ASC');
        $data = $data -> get();
        $headerColumns = ['Created','Form', 'Compliant'];
        $dataRows = [];
        if($data){
            foreach($data as $item){
                $opt = unserialize($item->options);

                $dataRows[] = [
                    $item->created_at(),
                    $item->formLog->name,
                    isset($opt['compliant']) ? $opt['compliant'] : 'N/A Not implemented',
                ];
            }
        }
        $title = $type==2?'Daily':'Monthly';
        if($dataRows){
            $html .= $this->defaultHtmlBuilder('start',$dataRows,$headerColumns,['title'=>$title.' Check List']);
            $html .= $this->defaultHtmlBuilder('end');
        }
        else {
            $html .=  $this->getNoRecords($title.' Check List');
        }
        return $html;
    }

    public function getGoodsInRecords(){
        $html = '';
        $data = \Model\TemperaturesForGoodsIn::whereIn('unit_id',$this->getUnitsId());
        $data = $this -> getDateRange($data);
        $data = $data -> orderBy('created_at', 'ASC');
        $data = $data -> get();
        $headerColumns = ['Created','by Device', 'by Staff','Supplier','Items','Temp','Invoice','Packaking','Data Code'];
        $dataRows = [];
        if($data){
            foreach($data as $item){
                $dataRows[] = [
                    $item->created_at(),
                    $item->device_name,
                    $item->staff_name,
                    $item->supplier_name ,
                    $item->products_name,
                    $item->temperature.'&deg;C',
                    $item->invoice_number,
                    $item->package_accept(),
                    $item->date_code_valid(),
                ];
            }
        }

        if($dataRows){
            $html .= $this->defaultHtmlBuilder('start',$dataRows,$headerColumns,['title'=>'Goods In Records']);
            $html .= $this->defaultHtmlBuilder('end');
        }
        return $html;
    }

    public function getStaff(){
        $html = '';

        $data = \Model\Staffs::whereIn('unit_id',$this->getUnitsId());
        $data = $data -> orderBy('created_at', 'DESC');
        $data = $data -> get();
        $headerColumns = ['Added','Role','Name','Email'];
        $dataRows = [];
        if($data){
            foreach($data as $item){
                $dataRows[] = [
                    $item->created_at(),
                    $item->role,
                    $item->surname.' '.$item->first_name,
                    $item->email,
                ];
            }
        }
        if($dataRows){
            $html .= $this->defaultHtmlBuilder('start',$dataRows,$headerColumns,['title'=>'Staff Records']);
            $html .= $this->defaultHtmlBuilder('end');
        }
        else {
            $html .=  $this->getNoRecords('Staff Records');
        }
        return $html;
    }

    public function getSuppliers(){
        $html = '';

        $data = \Model\Suppliers::whereIn('unit_id',$this->getUnitsId());
        $data = $data -> orderBy('created_at', 'DESC');
        $data = $data -> get();
        $headerColumns = ['Created','Name','Description','Email','Phone','Address','Products'];

        $dataRows = [];
        if($data){
            foreach($data as $item){
                $address = $item->post_code.' '.$item->city.' '.$item->street_number;
                $dataRows[] = [
                    $item->created_at(),
                    $item->name,
                    $item->description,
                    $item->email,
                    $item->telephone,
                    $address,
                    $item->products()
                ];
            }
        }

        if($dataRows){
            $html .= $this->defaultHtmlBuilder('start',$dataRows,$headerColumns,['title'=>'Suppliers Records']);
            $html .= $this->defaultHtmlBuilder('end');
        }
        else {
            $html .=  $this->getNoRecords('Suppliers Records');
        }
        return $html;
    }

    public function getTemperatures()
    {
        $html = '';
        $temperatures = $this->form_data['temperatures'];
        $groupsTypes = isset($temperatures['all-groups']) ? [] :( isset($temperatures['groups']) ? array_keys($temperatures['groups']) : [] );
        $probes = \Model\TemperaturesForProbes::whereIn('unit_id',$this->getUnitsId());
        $pods   = \Model\TemperaturesForPods::whereIn('unit_id',$this->getUnitsId());
        $out = [];
        if($groupsTypes){
            foreach($groupsTypes as $groupType){
                switch ($groupType){
                    case 'pods':$out[] = $pods;break;
                    case 'probes':$out[] = $probes; break;
                }
            }
            $datas = $out;
        }
        else{
            $datas = [$probes,$pods];
        }
        $collections = new \Illuminate\Support\Collection();
        foreach($datas as $data) {
            $data = $this->getDateRange($data);
            $data = $data -> orderBy('created_at', 'ASC');
            $collections = $collections->merge($data->get());
        }
        $headerColumns = ['Created', 'Staff', 'Device', 'Service', 'Group', 'Item', 'Temperature', 'Valid'];
        $dataRows = [];
        $datas = $collections->sortByDesc('created_at');
        if ($datas->count()) {
            foreach ($datas as $item) {
                $valid = $item->invalid ? 'Exceed ' . $item->invalid->exceed . 'imal value ' . $item->invalid->temperature . '&deg;C' : 'Valid';
                $dataRows[] = [
                    $item->created_at(),
                    $item->staff_name ? $item->staff_name : 'N/A',
                    $item->device_name ? $item->device_name : 'N/A',
                    $item->area ? $item->area->name : 'N/A',
                    $item->area->group ? $item->area->group : 'N/A',
                    $item->item_name ? $item->item_name : 'N/A',
                    $item->temperature . '&deg;C',
                    $valid,
                ];
            }
        }
        if ($dataRows) {
            $html .= $this->defaultHtmlBuilder('start', $dataRows, $headerColumns, ['title' => 'Temperatures Records']);
            $html .= $this->defaultHtmlBuilder('end');
        }
        else {
            $html .=  $this->getNoRecords('Temperatures Records');
        }
        return $html;
    }

    public function getTrainingRecords(){

        $html = '';
        $trainingRecords = $this->form_data['training_records'];
        $staffIds = isset($trainingRecords['all-staff']) ? [] :( isset($trainingRecords['staff']) ? array_keys($trainingRecords['staff']) : [] );

        $data = \Model\TrainingRecords::whereIn('unit_id',$this->getUnitsId());
        if($staffIds){
            $data = $data -> whereIn('staff_id',$staffIds);
        }
        $data = $this -> getDateRange($data);
        $data = $data -> orderBy('created_at', 'ASC');
        $data = $data -> get();
        $headerColumns = ['Created','Staff', 'Training Name','Comment','Date Start','Date Finish','Date Refresh'];
        $dataRows = [];
        if($data){
            foreach($data as $item){
                $dataRows[] = [
                    $item->created_at(),
                    $item->staff ? $item->staff->fullname():'N/A',
                    $item->name,
                    $item->comment,
                    date('Y-m-d', strtotime($item->start)),
                    date('Y-m-d', strtotime($item->finish)),
                    date('Y-m-d', strtotime($item->date_refresh))
                ];
            }
        }

        if($dataRows){
            $html .= $this->defaultHtmlBuilder('start',$dataRows,$headerColumns,['title'=>'Training Records']);
            $html .= $this->defaultHtmlBuilder('end');
        }
        else {
            $html .=  $this->getNoRecords('Training Records');
        }
        return $html;
    }

    public function getFoodIncidents(){
        $html = '';
        $data = \Model\FoodIncidents::whereIn('unit_id',$this->getUnitsId());
        $data = $this -> getDateRange($data);
        $data = $data -> orderBy('created_at', 'ASC') -> get();
        $headerColumns = ['Date of Incident','Category', 'Claimant','Type of food incident'];
        $dataRows = [];

        if($data->count()){
            foreach($data as $item){
                $dataRows[] = [
                    $item->s1_i2,
                    $item->getCategory(),
                    $item->s1_t1,
                    $item->s1_i1,
                ];
            }
        }

        if($dataRows){
            $html .= $this->defaultHtmlBuilder('start',$dataRows,$headerColumns,['title'=>'Food Incidents']);
            $html .= $this->defaultHtmlBuilder('end');
        }
        else {
            $html .=  $this->getNoRecords('Food Incidents');
        }
        return $html;
    }

    public function getDateRange($data,$column='created_at')
    {
        if($data){
            $date_from = isset($this->form_data['date_from']) ? $this->form_data['date_from'] : false;
            $date_to   = isset($this->form_data['date_to']) ? $this->form_data['date_to'] : false;
            if($date_from)
                $data = $data -> where($column,'>=',date('Y-m-d 00:00',strtotime($date_from)));
            if($date_to)
                $data = $data -> where($column,'<=',date('Y-m-d 23:59',strtotime($date_to)));
           return $data;
        }
    }

    public function defaultHtmlBuilder($type,$dataRows=null,$headerColumns=null,$title=[]){
        $html = '';
        if($type == 'start'){
            $html .= $this->getTable('top');
            $html .= $this->getTable('header',$dataRows, $title);
            $html .= $this->getTable('thead',$headerColumns);
            $html .= $this->getTable('body_start');
            $html .= $this->getTable('row',$dataRows);
        }
        if($type == 'end'){
            $html .= $this->getTable('body_end');
            $html .= $this->getTable('footer');
            $html .= $this->getTable('bottom');
        }
        return $html;
    }

    public function getNoRecords($title){
        return  '<div class="page-break"></div>'.
                '<section class="panel panel-default">'.
                '<div style="padding:15px;"><span style="font-weight:bold; font-size:24px">'.$title.'</span></div>'.
                '<div style="padding:15px;"><span style="font-weight:bold; font-size:24px">N/A - No records.</span></div>'.
                '</section>';
    }

    public function getTable($target,$array=[],$options=[])
    {
        $count = count($array);
        if($target == 'top'){
            return '<div class="page-break"></div><section class="panel panel-default">';
        }
        elseif($target == 'header'){
            return '<header class="" style="padding:15px;"><span style="font-weight:bold; font-size:24px">'.$options['title'].'</span><span class="badge badge-sm bg-danger" style="width:50px; margin-left:30px; background-color: #f79546; color: #fff; float:right">'.$count.' items</span> </header>
                    <table class="table table-striped m-b-none small">';
        }
        elseif($target == 'thead'){
            if($array){
                $data = '<thead><tr>';
                foreach($array as $item){
                    $data .= '<th>'.$item.'</th>';
                }
                $data .= '</tr></thead>';
                return $data;
            }
        }
        elseif($target == 'body_start'){
            return '<tbody>';
        }
        elseif($target == 'body_end'){
            return '</tbody>';
        }
        elseif($target == 'row'){
            $html = '';
            if($array){
                foreach($array as $items){
                    $html .= '<tr>';
                    foreach($items as $item){
                        $html .= '<td>'.$item.'</td>';
                    }
                    $html .= '</tr>';
                }
            }
            return $html;
        }
        elseif($target == 'footer'){
               return '</table>';
        }
        elseif($target == 'bottom'){
            return '</section>';
        }
    }
}