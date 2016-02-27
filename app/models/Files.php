<?php namespace Model;

class Files extends Models {

    protected $fillable = ['user_id','unit_id','target_id','target_type','file_name','file_path'];

    public function full_path(){
        return $this->file_path.$this->file_name;
    }

}