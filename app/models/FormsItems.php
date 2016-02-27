<?php namespace Model;

class FormsItems extends Models {

    public $types = [
        'tab'        => 'fa-folder',
        'input'      => 'fa-arrows',
        'textarea'   => 'fa-align-justify',
        'paragraph'  => 'fa-align-left',
        'radio'      => 'fa-dot-circle-o',
        'checkbox'   => 'fa-check-square-o',
        'select'     => 'fa-caret-square-o-down',
        'multiselect'=> 'fa-level-down',
        'timepicker' => 'fa-calendar',
        'datepicker' => 'fa-calendar',
        'staff'      => 'fa-users',
        'yes_no'     => 'fa-thumbs-o-up',
        'files_upload'=> 'fa-upload',
        'signature' => 'fa-pencil',
        'compliant'     => 'fa-check',
        'assign_staff'  => 'fa-share-alt',
        'submit_button' => 'fa-send',
    ];

    protected $fillable = ['form_id','placeholder','description','label','arrangement','required', 'options', 'type', 'sort', 'parent_id'];

    public function units()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function form()
    {
        return $this->belongsTo('\Model\Forms', 'form_id');
    }

}