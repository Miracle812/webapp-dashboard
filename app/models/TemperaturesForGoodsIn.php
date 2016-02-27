<?php namespace Model;

class TemperaturesForGoodsIn extends TemperaturesModel
{
    protected $table = 'temperatures_for_goods_in';
    protected $section_url = '/goods-in/';
    protected $fillable = [
        'action_todo',
        'compliant',

        'staff_name',
        'supplier_name',
        'products_name',
        'device_name',

        'date_time',
        'staff_id',
        'device_id',
        'rules_id',
        'invalid_id',
        'supplier_id',
        'date_code_valid',
        'invoice_number',
        'package_accept',
        'temperature'
    ];

    public $rules = [
        'action_todo'   => 'required',
        'compliant'     => 'required',

        'staff_name'    => 'required',
        'supplier_name' => 'required',
        'products_name' => 'required',
        'device_name'   => 'required',

        //'date_time'      =>'required',
        //'device_id'      => 'required',

        'staff_id'       =>'required',
        'supplier_id'    =>'required',
        'date_code_valid'=>'required',
        'invoice_number' =>'required',
        'package_accept' =>'required',
        'temperature'    =>'required|numeric'
    ];

    public function supplier()
    {
        return $this->belongsTo('\Model\Suppliers', 'supplier_id');
    }

    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function rule()
    {
        return $this->belongsTo('\Model\TemperaturesLogRules', 'rules_id');
    }

    public function repository()
    {
        return \App::make('\Repositories\TemperaturesForGoodsIn', [$this]);
    }

    public function package_accept()
    {
        $text  = 'invalid'; $class = 'danger';
        if($this -> package_accept){
            $text  = 'valid'; $class = 'success';}
        return '<span class="uk-text-'.$class.'">'.\Lang::get('common/general.'.$text).'</span>';
    }

    public function date_code_valid()
    {
        $text  = 'invalid'; $class = 'danger';
        if($this -> date_code_valid){
            $text  = 'valid'; $class = 'success';}
        return '<span class="uk-text-'.$class.'">'.\Lang::get('common/general.'.$text).'</span>';
    }

    public function compliant()
    {
        $text  = 'compliant'; $class = 'success';
        if(!$this -> compliant){
            $text  = 'non_'.$text; $class = 'danger';
        }
        return '<span class="uk-text-'.$class.'">'.\Lang::get('common/general.'.$text).'</span>';
    }

    public function getAllNonCompliant()
    {
        $out = [];
        foreach( ['date_code_valid','package_accept','invalid_id'] as $type ) {
            switch ($type) {
                case 'invalid_id' :
                    if ($this->{$type}) {
                        $type = 'temperature';
                        $out[] = \Lang::get('common/general.' . $type);
                    }
                    break;
                case 'package_accept' :
                    if (!$this->{$type}) {
                        $type = 'package';
                        $out[] = \Lang::get('common/general.' . $type);
                    }
                    break;
                case 'date_code_valid' :
                    if (!$this->{$type}) {
                        $type = 'date_code';
                        $out[] = \Lang::get('common/general.' . $type);
                    }
                    break;

                default:
                    if (!$this->{$type}) {
                        $out[] = \Lang::get('common/general.' . $type);
                    }
                    break;
            }
        }
        $and = ' '.\Lang::get('common/general.and').' ';
        $char = $out == 0 ? false : ($out > 1 ? 'are' : 'is');
        return $char ? implode($and,$out).' '.\Lang::get('common/general.'.$char).' '.\Lang::get('common/general.non_compliant') . '.' : null;
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' : $url = 'details/'.$this->id; break;
            case 'section' : $url = ''; break;
            default : $url = ''; break;
        }
        return $this -> section_url . $url ;
    }

    public function getSectionName()
    {
        $table = $this -> getTable();
        return \Lang::get('/common/sections.'.$table.'.title');
    }

    public function getOutstandingTaskItemTitle($details = NULL)
    {
        return parent::getOutstandingTaskItemTitle(ucfirst($this->getAllNonCompliant()));
    }
}