<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchNewStructure extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::drop('hubs_devices');
        Schema::drop('hubs_messages');
        Schema::drop('sockets_hubs');
        Schema::rename('probes_devices', 'temperatures_probes_devices');

        Schema::table('temperatures_probes_devices', function ($table)
        {
            $table->dropColumn('remember_token');
        });

        Schema::table('temperatures_alert_box', function ($table)
        {
            $table->String('group',20);
        });

        DB::statement('ALTER TABLE temperatures_areas MODIFY COLUMN warning_min MEDIUMINT');
        DB::statement('ALTER TABLE temperatures_areas MODIFY COLUMN warning_max MEDIUMINT');
        DB::statement('ALTER TABLE temperatures_areas MODIFY COLUMN danger_min MEDIUMINT');
        DB::statement('ALTER TABLE temperatures_areas MODIFY COLUMN danger_max MEDIUMINT');


        Schema::create('temperatures_pods_sensors', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->String('identifier',20);
            $table->String('name',255);
            $table->timestamps();
        });

        Schema::create('temperatures_pods_areas', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->Integer('root')->default(0);
            $table->Integer('parent_id')->default(0);
            $table->Integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->Integer('lft')->default(0);
            $table->Integer('rgt')->default(0);
            $table->Integer('lvl')->default(0);
            $table->Integer('sort')->default(0);
            $table->String('type',11)->nullable();
            $table->String('name',100);
            $table->String('description')->nullable();
            $table->String('rule_description')->nullable();
            $table->String('lang',5)->default('en');
            $table->mediumInteger('warning_min')->default(0);
            $table->mediumInteger('warning_max')->default(0);
            $table->mediumInteger('valid_min')->default(0);
            $table->mediumInteger('valid_max')->default(0);
            $table->timestamps();
        });



        Schema::create('assigned_pods_sensors', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->Integer('pod_area_id')->unsigned();
            $table->foreign('pod_area_id')->references('id')->on('temperatures_pods_areas')->onDelete('cascade');
            $table->Integer('pod_sensor_id')->unsigned();
            $table->foreign('pod_sensor_id')->references('id')->on('temperatures_pods_sensors')->onDelete('cascade');
        });

        Schema::table('temperatures_for_pods', function ($table) {
            $table->dropColumn('hub_id');
        });

        DB::statement('ALTER TABLE temperatures_log_rules MODIFY COLUMN warning_min MEDIUMINT');
        DB::statement('ALTER TABLE temperatures_log_rules MODIFY COLUMN warning_max MEDIUMINT');
        DB::statement('ALTER TABLE temperatures_log_rules MODIFY COLUMN danger_min MEDIUMINT');
        DB::statement('ALTER TABLE temperatures_log_rules MODIFY COLUMN danger_max MEDIUMINT');


        Schema::table('temperatures_log_rules', function ($table)
        {
            $table->renameColumn('warning_min', 'valid_min');
            $table->renameColumn('warning_max', 'valid_max');
            $table->renameColumn('danger_min', 'warning_min');
            $table->renameColumn('danger_max', 'warning_max');
        });

        DB::statement('ALTER TABLE suppliers MODIFY COLUMN warning_min MEDIUMINT');
        DB::statement('ALTER TABLE suppliers MODIFY COLUMN warning_max MEDIUMINT');
        DB::statement('ALTER TABLE suppliers MODIFY COLUMN danger_min MEDIUMINT');
        DB::statement('ALTER TABLE suppliers MODIFY COLUMN danger_max MEDIUMINT');

        Schema::table('suppliers', function ($table)
        {
            $table->renameColumn('warning_min', 'valid_min');
            $table->renameColumn('warning_max', 'valid_max');
            $table->renameColumn('danger_min', 'warning_min');
            $table->renameColumn('danger_max', 'warning_max');
        });

        Schema::rename('temperatures_menu_items', 'temperatures_probes_menu_items');


        Schema::table('temperatures_areas', function ($table)
        {
            $table->renameColumn('warning_min', 'valid_min');
            $table->renameColumn('warning_max', 'valid_max');
            $table->renameColumn('danger_min', 'warning_min');
            $table->renameColumn('danger_max', 'warning_max');
        });

        Schema::rename('temperatures_areas', 'temperatures_probes_areas');

        Schema::table('temperatures_for_pods', function ($table)
        {
            $table->dropForeign('temperatures_for_pods_ibfk_2');
        });

        Schema::table('temperatures_probes_areas', function ($table)
        {
            $table->dropForeign('temperatures_probes_areas_ibfk_2');
        });

        $podsAreas = \Model\TemperaturesProbesAreas::whereIn('group_id',[1,2])->get();
        foreach($podsAreas as $area){
            $root = \Model\TemperaturesPodsAreas::whereType('ROOT')->whereUnitId($area->unit_id)->first();
            if(!$root){
                $root = new \Model\TemperaturesPodsAreas();
                $root -> name = 'ROOT';
                $root -> type = 'ROOT';
                $root -> parent_id = 0;
                $root -> unit_id   = $area -> unit_id;
                $root -> lang      = 'en';
                $root -> save();
            }
            $group = \Model\TemperaturesPodsAreas::whereType('group')->whereName($area->group_id==2?'Fridges':'Freezers')->whereUnitId($area->unit_id)->first();
            if(!$group){
                $group = new \Model\TemperaturesPodsAreas();
                $group -> root = $root->id;
                $group -> parent_id = $root->id;
                $group -> unit_id = $area->unit_id;
                $group -> name = $area->group_id==2?'Fridges':'Freezers';
                $group -> lvl = 1;
                $group -> type = 'group';
                $group -> lang = 'en';
                $group -> save();
            }

            $newArea = \Model\TemperaturesPodsAreas::create([
                'root'=>$root->id,
                'parent_id'=>$group->id,
                'unit_id'=>$area->unit_id,
                'type'=>'area',
                'name'=>$area->name,
                'lvl'=>2,
                'description'=>$area->description,
                'rule_description'=>$area->rule_description,
                'lang'=>'en',
                'warning_min'=>$area->warning_min,
                'warning_max'=>$area->warning_max,
                'valid_min'=>$area->valid_min,
                'valid_max'=>$area->valid_max,
            ]);
            \Model\TemperaturesForPods::wherePodId(66)->delete();
            \Model\TemperaturesForPods::whereId(10)->delete();
            \Model\TemperaturesAlertBox::whereAreaId($area->id)->whereUnitId($area->unit_id)->update(['area_id'=>$newArea->id,'group'=>'pods']);

            $oldAreaPods = \Model\PodsDevices::whereAreaId($area->id)->get();
            if($oldAreaPods->count()) {
                foreach($oldAreaPods as $oldPod){
                        $newPod = \Model\TemperaturesPodsSensors::create([
                            'unit_id' => $area->unit_id,
                            'identifier' => $oldPod->identifier,
                            'name' => $oldPod->name,
                        ]);
                        \Model\AssignedPodsSensors::insert([
                            'pod_area_id' => $newArea->id,
                            'pod_sensor_id' => $newPod->id,
                        ]);
                        \Model\TemperaturesForPods::whereAreaId($area->id)->whereUnitId($area->unit_id)->wherePodId($oldPod->id)->update(['pod_id'=>$newPod->id,'area_id'=>$newArea->id]);
                }
            }
        }

        \Model\TemperaturesAlertBox::whereNotNull('parent_id')->whereNotNull('area_id')->whereNotIn('group',['pods'])->update(['group'=>'probes']);

        DB::statement('ALTER TABLE temperatures_for_pods MODIFY pod_id INTEGER UNSIGNED NULL');

        Schema::table('temperatures_for_pods', function($table)
        {
            $table->foreign('pod_id')->references('id')->on('temperatures_pods_sensors')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('temperatures_pods_areas')->onDelete('cascade');
        });

        \Model\TemperaturesProbesAreas::whereIn('group_id',[1,2])->delete();

        Schema::drop('temperatures_groups');
        Schema::drop('pods_devices');
        Schema::table('temperatures_probes_areas', function ($table)
        {
            $table->dropColumn('group_id');
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
