<?php namespace Model;

class CheckListSections extends Models {

    public function tasks()
    {
        return $this->hasMany('\Model\CheckListTasks','section_id');
    }

    public function activeTasks()
    {
        return $this->tasks()->where('active','=',1);
    }

    public function getDailyIds(){
        return $this->select('id')->where('group','=',1)->get()->lists('id');
    }

    public function getMonthlyIds(){
        return $this->select('id')->where('group','=',2)->get()->lists('id');
    }

}