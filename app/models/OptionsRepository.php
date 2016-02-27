<?php

class OptionsRepository
{
    public static function create($target,$option_idenfier, array $values)
    {
        $model = new \Model\OptionsMenu();
        $parent = $model::whereIdentifier($target->getTable())->first();
        $option = $model::whereIdentifier($option_idenfier)->whereParentId($parent->id)->first();
        $data = ['target_id'=>$target->id, 'target_type'=>$target->getTable(), 'option_id'=>$option->id];
        $new = \Model\Options::firstOrCreate($data);
        $new -> values = serialize($values);
        $save = $new -> save();
        return $save ? $new : false;
    }

    public static function createByInputs($target,$inputs)
    {
        $options = isset($inputs['options'])?$inputs['options']:[];
        $target->options('inputs')->delete();
        $save = false;
        if ($options) {
            foreach ($options as $key => $val) {
                $new = new \Model\Options();
                $new -> target_id = $target->id;
                $new -> target_type = $target->getTable();
                $new -> values = $val;
                $new -> option_id = $key;
                $save = $new -> save();
            }
        }
        return $save;
    }
}
