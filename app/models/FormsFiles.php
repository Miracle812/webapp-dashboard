<?php namespace Model;

class FormsFiles extends Models
{
    protected $fillable = ['user_id','unit_id','form_log_id','item_log_id','answer_id','file_name','file_path'];

    public function delete()
    {
        \File::delete(public_path().$this -> file_path.$this -> file_name);
        return parent::delete();
    }
}