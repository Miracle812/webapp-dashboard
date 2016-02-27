<?php namespace Model;

class OptionsMenu extends Models
{
    protected $table = 'options_menu';
    public $rules = [
       'create' => [
           'identifier'=>'required|max:50',
           'name'=>'required|max:50',
       ],
       'edit' => [
           'identifier'=>'required|max:50',
           'name'=>'required|max:50',
       ],
    ];
    protected $fillable = [
        'parent_id','identifier','name','type'
    ];

    public function childrens()
    {
        return $this->hasMany('\Model\OptionsMenu', 'parent_id');
    }
}
