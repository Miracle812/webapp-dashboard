<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAssignForms extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('assigned_forms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('form_id')->unsigned();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->Text('data')->nullable();
        });

        Schema::table('forms', function ($table)
        {
            $table->dropForeign('forms_unit_id_foreign');

        });

        $forms = \Model\Forms::all();
        foreach($forms as $form){
            $assign = [];
            if($form -> unit) {
                $unit = $form -> unit;
                $hq = $unit -> headquarter;
                $assign[$hq -> id] = [$unit -> id];
                $data = serialize($assign);
            }
            else{
                $data = 'generic';
            }
            $assignForm = \Model\AssignedForms::firstOrCreate(['form_id'=>$form->id]);
            $assignForm -> data = $data;
            $assignForm -> update();
        }

        Schema::table('forms', function ($table)
        {

            $table->dropColumn('unit_id');
        });


        Schema::table('forms_groups', function ($table)
        {
            $table->dropForeign('forms_groups_unit_id_foreign');
            $table->dropColumn('unit_id');
        });

        \Model\FormsGroups::whereIn('id',[6,7,8,9,10])->delete();
        $formsGroups = \Model\Forms::whereIn('group_id',[6,7,8,9,10])->get();
        foreach($formsGroups as $record){
          switch($record->group_id){
              case 6 : $record->group_id = 1; break;
              case 7 : $record->group_id = 2; break;
              case 8 : $record->group_id = 3; break;
              case 9 : $record->group_id = 4; break;
              case 10 : $record->group_id = 5; break;
          }
            $record->update();
        }


        Schema::table('forms_logs', function ($table)
        {
            $table->dropForeign('forms_logs_unit_id_foreign');
            $table->dropColumn('unit_id');
        });

        Schema::table('forms_items', function ($table)
        {
            $table->dropForeign('forms_items_unit_id_foreign');
            $table->dropColumn('unit_id');
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
