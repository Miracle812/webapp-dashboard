<?php namespace Model;

class Haccp extends NestableTree {

    protected $table = 'haccp';
    private $section_url = 'haccp';
    public $targetType;
    protected $guarded = array();
    public $rules = [
        'title'       => 'required|min:5|max:100',
        'content'     => '',
        'hazards'     => '',
        'control'     => '',
        'monitoring'  => '',
        'corrective_action' => '',
    ];
    protected $fillable = ['title','content','hazards','control','monitoring','corrective_action'];
    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' : $url = '/storage/item/'.$this->id; break;
            case 'section' :  $url = ''; break;
            default : $url = ''; break;
        }
        return $this -> section_url . $url ;
    }

    public function save(array $options = [])
    {
        foreach(['content','control','monitoring','corrective_action'] as $data){
            if($this->$data)
            $this->$data = $this->filterValidAscii($this->$data);
        }
        return parent::save($options);
    }
}