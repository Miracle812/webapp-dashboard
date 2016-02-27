<?php namespace Model;

class Knowledges extends NestableTree {

    private $section_url = 'knowledge';
    protected $guarded = array();
    public $targetType;
    public $rules = [
        'title'         => 'required|min:5|max:100',
        'content_short' => '',
        'content_full'  => '',
    ];
    protected $fillable = ['title','content_one','content_two'];


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
        foreach(['content_one','content_two'] as $data){
            if($this->$data)
                $this->$data = $this->filterValidAscii($this->$data);
        }
        return parent::save($options);
    }
}