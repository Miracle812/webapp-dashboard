<?php namespace Model;

class Staffs extends Models
{
    private $section_url = 'staff';
    protected $fillable = [
        'unit_id',
        'first_name',
        'surname',
        'email',
        'phone',
        'role',
        'smartprobe',
    ];

    public $import = [
        'rules' => [
            'first_name'    => 'max:20|required',
            'surname'       => 'max:50|required',
            'phone'         => 'max:20|required',
            'role'          => 'max:50|required',
            'email'         => 'max:50|email|required'
        ],
        'columns' => ['A'=>'first_name','B'=>'surname','C'=>'role','D'=>'email','E'=>'phone']
    ];

    public function repository()
    {
        return \App::make('\Repositories\Staffs', [$this]);
    }

    public function units()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function fullname(){
        return $this -> first_name . ' ' . $this -> surname;
    }

    public function trainingsRecords()
    {
        return $this->hasMany('\Model\TrainingRecords','staff_id');
    }

    public function healthQuestionnaires()
    {
        return $this->hasMany('\Model\FormsAnswers','target_id')->whereTargetType('staffs')->whereIn('form_log_id', function($query){
            $query->select('id')->from('forms_logs')->where('assigned_id', 1);
        });
    }

    public function avatar(){
        return $this -> avatar ? : '/assets/images/user_blank.jpg';
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' : $url = '/edit/general/'.$this->id; break;
            case 'section' :  $url = ''; break;
            default : $url = ''; break;
        }
        return $this -> section_url . $url ;
    }

}