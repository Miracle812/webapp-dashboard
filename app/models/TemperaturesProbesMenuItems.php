<?php namespace Model;

class TemperaturesProbesMenuItems extends Models {


    public function getStructureArray()
    {
       return unserialize($this->structure);
    }

    public function getItemByKeyValue ($data = [], $key, $value)
    {
        $data = empty($data) ? $this->getStructureArray() : $data;
        foreach ($data as $k => $v)
        {
            $out = false;
            $id = $v[$key];
            if ($id == $value){
                return $data[$k];
            }
            if (isset($v['children'])){
                $out = self::getItemByKeyValue ($v['children'], $key, $value);
            }
        }
        return $out;
    }

    public function getItemByKey ($data = [], $key)
    {
        $data = empty($data) ? $this->getStructureArray() : $data;
        $out = false;
        foreach ($data as $v)
        {
            if ($v['id']==$key){
                return $v['name'];
            }
            if (!$out && isset($v['children'])){
                $out = self::getItemByKey ($v['children'], $key);
            }
        }
        return $out;
    }

    public function getTree ($data = [])
    {
        $data = empty($data) ? $this->getStructureArray() : $data;
        foreach ($data as $v)
        {
            $id=$v['id'];
            $out[$id]=['name'=>$v['name']];
            if (isset($v['children'])){
                $out[$id]['children'] = self::getTree ($v['children']);
            }
        }
        return $out;
    }
}