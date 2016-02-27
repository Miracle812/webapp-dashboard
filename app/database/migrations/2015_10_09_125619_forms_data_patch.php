<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FormsDataPatch extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$forms = \Model\Forms::all();
        $forms -> each(function($form){
            $items = $form->items->sortBy('sort');
            $one = $two = $three = null;
            foreach($items as $item) {
                if(($form->assigned_id == 4) && ($item->type == 'assign_staff')){
                    $item->delete();
                    continue;
                }
                if ($item->type == 'compliant') {
                    $item->type = 'yes_no';
                    $item->options = 'a:2:{s:7:"records";a:1:{i:1;s:34:"Please select option";}s:14:"buttons_colors";a:2:{s:3:"yes";s:7:"#1aae88";s:2:"no";s:7:"#e33244";}}';
                    $item->update();
                }
                if ($item->type == 'yes_no') {
                    if(($lower = strtolower($item->label)) == 'item' || $lower == 'item '){
                        $options = $item->options ? unserialize($item->options) : [];
                        if(isset($options['records']) && (count($options['records']))){
                            $item -> label = implode(', ',$options['records']);
                            $options['records'] = ['Please select option'];
                            $item -> options = serialize($options);
                            $item -> update();
                        }
                    }
                    $one = $item;
                    continue;
                }
                if ($one && ($one->sort < $item->sort)) {
                    if (($item->type == 'input') && !empty($item->label)) {
                        if (
                            ( strpos(strtolower($item->label), 'cleaning agent') !== false) ||
                            ( strpos(strtolower($item->label), 'ecolab chemical(s)') !== false)
                        ) {
                            $two = $item;
                            $two -> new_description = $item->label;
                        }
                        if (
                            (strpos(strtolower($item->label), 'ppe') !== false) ||
                            (strpos(strtolower($item->label), 'ppe required') !== false)
                        )
                        {
                            $three = $item;
                            $three -> new_description = $item->label;
                        }
                    }
                }
                if ($one && $two && $three) {
                    $description = $one->description ? $one->description . '<BR>' : '';
                    $description .= '<strong>'.$two -> new_description.' : </strong> ' . $two->placeholder . '<BR>';
                    $description .= '<strong>'.$three -> new_description.' : </strong> ' . $three->placeholder . '<BR>';
                    $one->update(['description' => $description]);
                    $two->delete();
                    $three->delete();
                    $one = $two = $three = null;
                }
            };
        });
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
