<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsDangersWarnings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('temperatures_log_rules', function($table)
        {
            $table->tinyInteger('danger_min')->default(0);
            $table->tinyInteger('warning_min')->default(0);
            $table->tinyInteger('warning_max')->default(0);
            $table->tinyInteger('danger_max')->default(0);
        });

        Schema::table('temperatures_areas', function($table)
        {
            $table->tinyInteger('danger_min')->default(0);
            $table->tinyInteger('warning_min')->default(0);
            $table->tinyInteger('warning_max')->default(0);
            $table->tinyInteger('danger_max')->default(0);
        });

        Schema::table('temperatures_groups', function($table)
        {
            $table->tinyInteger('danger_min')->default(0);
            $table->tinyInteger('warning_min')->default(0);
            $table->tinyInteger('warning_max')->default(0);
            $table->tinyInteger('danger_max')->default(0);
        });

        Schema::table('suppliers', function($table)
        {
            $table->tinyInteger('danger_min')->default(0);
            $table->tinyInteger('warning_min')->default(0);
            $table->tinyInteger('warning_max')->default(0);
            $table->tinyInteger('danger_max')->default(0);
        });

        DB::statement('ALTER TABLE temperatures_log_invalid MODIFY COLUMN temperature INT');

        foreach(\Model\TemperaturesLogInvalid::all() as $item)
        {
            $item->update(['type'=>'danger']);
        }

        foreach(\Model\TemperaturesGroups::all() as $item)
        {
            if($item->identifier == 'freezers')
                $item->update(['rule_description'=>'Your food should be frozen from {warning_min} to {warning_max} and below {danger_max} {celsius} (good practice).']);
            if($item->identifier == 'fridges')
                $item->update(['rule_description'=>'Your food should be chilled from {warning_min} to {warning_max} {celsius}.']);
            if($item->identifier == 'probes')
                $item->update(['rule_description'=>'Your food temperature should be in range from {warning_min} to {warning_max} {celsius}.']);
        }

        foreach(\Model\TemperaturesAreas::all() as $item)
        {
            switch($item->rule_description) {
                case 'Your food should be chilled from {rule_min} to {rule_max} {celsius}.' :
                    $item->update(['rule_description' => 'Your food should be chilled from {warning_min} to {warning_max} {celsius}.']);
                    break;
                case '{name} temperature should be in range from {rule_min} to {rule_max} {celsius}' :
                    $item->update(['rule_description' => '{name} temperature should be in range from {warning_min} to {warning_max} {celsius}.']);
                    break;
                case 'Your food should be frozen below {rule_max} {celsius} (good practice).' :
                    $item->update(['rule_description' => 'Your food should be frozen below {warning_max} {celsius} (good practice).']);
                    break;
                case '{name} temperature should be in range from {rule_min} to {rule_max} {celsius}.' :
                    $item->update(['rule_description' => '{name} temperature should be in range from {warning_min} to {warning_max} {celsius}.']);
                    break;
                case 'Your food should be chilled in range from {rule_min} to {rule_max} {celsius}.' :
                    $item->update(['rule_description' => 'Your food should be chilled in range from {warning_min} to {warning_max} {celsius}.']);
                    break;
            }
        }






        foreach(\Model\TemperaturesAreas::all() as $item){
            $item -> rule_min = is_null($item -> rule_min) ? 0 : $item -> rule_min;
            $item -> rule_max = is_null($item -> rule_max) ? 0 : $item -> rule_max;
            $min = $item -> rule_min <= $item -> rule_max ? $item -> rule_min : $item -> rule_max;
            $max = $item -> rule_max >= $item -> rule_min ? $item -> rule_max : $item -> rule_min;
            $item->update(['danger_min'=>$min,'warning_min'=>$min,'danger_max'=>$max,'warning_max'=>$max]);
        }
        foreach(\Model\TemperaturesGroups::all() as $item){
            $item -> rule_min = is_null($item -> rule_min) ? 0 : $item -> rule_min;
            $item -> rule_max = is_null($item -> rule_max) ? 0 : $item -> rule_max;
            $min = $item -> rule_min <= $item -> rule_max ? $item -> rule_min : $item -> rule_max;
            $max = $item -> rule_max >= $item -> rule_min ? $item -> rule_max : $item -> rule_min;
            $item->update(['danger_min'=>$min,'warning_min'=>$min,'danger_max'=>$max,'warning_max'=>$max]);
        }
        foreach(\Model\Suppliers::all() as $item){
            $item -> rule_min = is_null($item -> rule_min) ? 0 : $item -> rule_min;
            $item -> rule_max = is_null($item -> rule_max) ? 0 : $item -> rule_max;
            $min = $item -> rule_min <= $item -> rule_max ? $item -> rule_min : $item -> rule_max;
            $max = $item -> rule_max >= $item -> rule_min ? $item -> rule_max : $item -> rule_min;
            $item->update(['danger_min'=>$min,'warning_min'=>$min,'danger_max'=>$max,'warning_max'=>$max]);
        }
        foreach(\Model\TemperaturesLogRules::all() as $item){
            $item -> min = is_null($item -> min) ? 0 : $item -> min;
            $item -> max = is_null($item -> max) ? 0 : $item -> max;
            $min = $item -> min <= $item -> max ? $item -> min : $item -> max;
            $max = $item -> max >= $item -> min ? $item -> max : $item -> min;
            $item->update(['danger_min'=>$min,'warning_min'=>$min,'danger_max'=>$max,'warning_max'=>$max]);
        }

        Schema::table('temperatures_log_rules', function($table)
        {
            $table->dropColumn('min');
            $table->dropColumn('max');
        });

        Schema::table('temperatures_areas', function($table)
        {
            $table->dropColumn('rule_min');
            $table->dropColumn('rule_max');

        });

        Schema::table('temperatures_groups', function($table)
        {
            $table->dropColumn('rule_min');
            $table->dropColumn('rule_max');

        });

        Schema::table('suppliers', function($table)
        {
            $table->dropColumn('rule_min');
            $table->dropColumn('rule_max');

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
