<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CloningFormsBrittishairways extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $pirbrightGroups = \Model\FormsGroups::whereUnitId(12)->get();
        $newGroups = null;
        foreach($pirbrightGroups as $pirbGroup) {
            $groupCopy = $pirbGroup->replicate();
            $groupCopy->unit_id = 15;
            $groupCopy->save();
            $newGroups[$pirbGroup->id] = $groupCopy -> id;
        }

		$pirbrightForms = \Model\Forms::whereUnitId(12)->get();
        $newTabs = null;
        foreach($pirbrightForms as $pirbForm){
            $formCopy = $pirbForm->replicate();
            $formCopy -> unit_id = 15;
            $formCopy -> save();
            $pirbrightItems = $pirbForm->items;
            $oldTabsIds =  $pirbForm->items()->whereNull('parent_id')->lists('id');
            foreach($pirbrightItems as $pirbItem){
                $itemCopy = $pirbItem->replicate();
                $itemCopy -> unit_id = 15;
                $itemCopy -> form_id = $formCopy->id;
                $itemCopy -> save();
                if(($pirbItem -> type == 'tab') && !$pirbItem -> parent_id){
                    if(in_array($pirbItem->id,$oldTabsIds)){
                        $newTabs[$pirbItem->id] = $itemCopy -> id;
                    }
                }
            }
        }
        if($newGroups){
            foreach ($newGroups as $old => $new){
                \Model\Forms::whereUnitId(15)->whereGroupId($old)->update(['group_id'=>$new]);
            }
        }
        if($newTabs){
            foreach ($newTabs as $old => $new){
                \Model\FormsItems::whereUnitId(15)->whereParentId($old)->update(['parent_id'=>$new]);
            }
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
