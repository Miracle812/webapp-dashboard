<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchForFormsManager extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::drop('forms_items');
        Schema::drop('forms');
        Schema::create('forms_groups', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned()->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('assigned_id');
            $table->String('name',255);
            $table->String('description',255)->nullable();
            $table->integer('sort')->default(0);
        });

        Schema::create('forms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned()->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('forms_groups')->onDelete('cascade');
            $table->integer('assigned_id');
            $table->String ('name',255);
            $table->Text('description')->nullable();
            $table->integer('active')->default(0);
            $table->integer('signature')->default(0);
            $table->timestamps();
        });

        Schema::create('forms_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned()->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->String('group')->nullable();
            $table->integer('assigned_id');
            $table->String ('name',255);
            $table->String ('description',255)->nullable();
            $table->timestamps();
        });

        Schema::create('forms_items', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('unit_id')->unsigned()->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('form_id')->unsigned();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->integer('parent_id')->nullable();
            $table->Text('label');
            $table->Text('description')->nullable();
            $table->Text('placeholder')->nullable();
            $table->String('type',50);
            $table->String('arrangement',50)->nullable();
            $table->integer('sort')->default(0);
            $table->integer('required')->default(0);
            $table->text('options')->nullable();
            $table->timestamps();
        });

        Schema::create('forms_items_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('form_log_id')->unsigned();
            $table->foreign('form_log_id')->references('id')->on('forms_logs')->onDelete('cascade');
            $table->integer('org_id');
            $table->integer('parent_id')->nullable();
            $table->Text('label');
            $table->Text('description')->nullable();
            $table->String('type',50);
            $table->integer('sort')->default(0);
            $table->text('options')->nullable();
            $table->integer('required')->default(0);
            $table->timestamps();
        });

        Schema::create('forms_answers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('form_log_id')->unsigned();
            $table->foreign('form_log_id')->references('id')->on('forms_logs')->onDelete('cascade');
            $table->integer('target_id')->nullable();
            $table->String('target_type',50)->nullable();
            $table->longText('signature')->nullable();
            $table->String('sign_name')->nullable();
            $table->String('sign_role')->nullable();
            $table->text('options')->nullable();
            $table->timestamps();
        });

        Schema::create('forms_answers_values', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('answer_id')->unsigned();
            $table->foreign('answer_id')->references('id')->on('forms_answers')->onDelete('cascade');
            $table->integer('item_log_id')->unsigned();
            $table->foreign('item_log_id')->references('id')->on('forms_items_logs')->onDelete('cascade');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('forms_files', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('answer_id')->unsigned()->nullable();
            $table->foreign('answer_id')->references('id')->on('forms_answers')->onDelete('cascade');
            $table->integer('form_log_id')->unsigned();
            $table->integer('item_log_id')->unsigned();
            $table->String('file_name',255);
            $table->String('file_path',255);
            $table->timestamps();
        });

        $filesOptions = serialize([
            'extensions'=>['jpg','jpeg','png','doc','docx','odt','pdf'],
            'file_size' =>2
        ]);


        $tabs[1] = ['name'=>'Section 1', 'description'=>'Structure  and  Equipment'];
        $tabs[2] = ['name'=>'Section 2', 'description'=>'Contamination Controls'];
        $tabs[3] = ['name'=>'Section 3', 'description'=>'Temperature Controls'];
        $tabs[4] = ['name'=>'Section 4', 'description'=>'Cleaning and Disinfection'];
        $tabs[5] = ['name'=>'Section 5', 'description'=>'Personal Hygiene'];
        $tabs[6] = ['name'=>'Section 6', 'description'=>'Refuse Disposal'];
        $tabs[7] = ['name'=>'Section 7', 'description'=>'Pest Control'];

        $tabs[1]['items'] = [
            'Lighting levels are adequate and working, all fittings in good repair?',
            'Ventilation satisfactory?',
            'Are kitchen temperature acceptable (target 24°C, or below)?',
            'Sinks and wash hand basins in good repair and correctly used?',
            'All equipment is operational and in good repair?',
            'All work surfaces in good repair?',
            'All refrigeration and freezer equipment is in good repair and operating at the correct temperatures?',
            'Hot and cold food holding equipment is in good repair and maintaining satisfactory temperatures?',
            'Adequate changing and toilet facilities provided?'
        ];
        $tabs[2]['items'] = [
            'Designated surfaces for raw and ready to eat foods – clearly labelled?',
            'Separate clingfilm and santiser dispensers for raw and ready to eat areas – clearly labelled?',
            'Staff wearing disposable aprons when handling/preparing raw meat and root vegetables?',
            'Correct use of disposable gloves?',
            'Correct separation of raw and ready to eat foods in storage?',
            'Correct use of colour coded equipment?',
            'Correct use of surface sanitiser?',
            'Correct use of wiping cloths?',
            'No foods unnecessarily exposed to risk of contamination?'
        ];
        $tabs[3]['items'] = [
            'Delivery procedures are correctly controlled and recorded?',
            'Refrigerated and frozen food storage temperatures are correct and recorded?',
            'Correct core cooking temperatures are being achieved and recorded?',
            'Defrosting is carried out in a controlled manner?',
            'Cooling and blast chilling procedures correctly controlled and recorded?',
            'Hot and cold service temperatures are being achieved and recorded?',
            'Correct control of buffet / hospitality service?',
            'Corrective actions are being taken and recorded in the event of non-conformances?'
        ];
        $tabs[4]['items'] = [
            'Does the cleaning schedule cover all structure and equipment?',
            'Is cleaning carried out according to the cleaning schedule?',
            'Company approved chemicals used?',
            'Pot wash operation satisfactory?',
            'Mechanical dishwasher operating satisfactorily?',
            'Arrangements for deep clean in place?',
            'Arrangements for filter cleaning in place and date completed?'
        ];
        $tabs[5]['items'] = [
            'All staff wearing clean uniforms and head wear?',
            'The company jewellery standard is being adhered to?',
            'Correct hand washing procedures are being followed?',
            'Personal hygiene habits are satisfactory?',
            'Where relevant, return to work procedure being correctly applied?',
            'Food hygiene training has been provided and is up to date?'
        ];
        $tabs[6]['items'] = [
            'Are internal foot bins being used?',
            'Refuse is regularly removed from catering areas (internal / external)?',
            'External refuse storage area is satisfactory?'
        ];
        $tabs[7]['items'] = [
            'All areas free from infestation?',
            'Insectocutors provided, correctly cleaned and maintained, not positioned over food preparation areas?',
            'External openings protected by fly screening?',
            'Premises monitored by external pest control contractor?',
            'Reports from contractor available, checked and acted upon?'
        ];
        $form = \Model\Forms::create(['name' => 'Navitas Generic: Monthly Check List', 'description' => 'Monthly Check List', 'assigned_id' => 3, 'active' => 1, 'signature' => 1]);

        foreach($tabs as $key => $tab){
            $newTab = \Model\FormsItems::create(['form_id' => $form->id, 'label' => $tab['name'], 'description' => $tab['description'], 'placeholder' => 'Enter responsible', 'type' => 'tab', 'sort' => $key, 'required' => 0, 'options' => serialize([])]);
            if(isset($tab['items'])) {
                $options = [];
                foreach ($tab['items'] as $item) {
                    $options['records'][] = $item;
                }
                $options['buttons_colors'] = ['yes'=>'#1aae88','no'=>'#e33244'];
                \Model\FormsItems::create(['form_id' => $form->id, 'parent_id'=>$newTab->id, 'label' => 'Please Answer the Following Questions:', 'description' => '',  'type' => 'yes_no', 'sort' => 1, 'required' => 0, 'options' => serialize($options)]);
            }
            \Model\FormsItems::create(['form_id' => $form->id, 'parent_id'=>$newTab->id, 'label' => 'Responsible', 'description' => '', 'placeholder' => 'Enter responsible', 'type' => 'input', 'sort' => 2, 'required' => 1, 'options' => serialize([])]);
            \Model\FormsItems::create(['form_id' => $form->id, 'parent_id'=>$newTab->id, 'label' => 'Comment / Action', 'description' => '', 'placeholder' => 'Enter Comment / Action.', 'type' => 'textarea', 'sort' => 3, 'required' => 1, 'options' => serialize([])]);
            \Model\FormsItems::create(['form_id' => $form->id, 'parent_id'=>$newTab->id, 'label' => 'Section Files', 'description' => '', 'type' => 'files_upload', 'sort' => 4, 'required' => 0, 'options' => $filesOptions]);

            \Model\FormsItems::create(['form_id' => $form->id, 'parent_id'=>$newTab->id, 'label' => 'Submit Form', 'description' => '', 'type' => 'submit_button', 'sort' => 5, 'required' => 0, 'options' => serialize([])]);
        }

        $dailyForms = [
            'Kitchen & equipments clean and in good condition?',
            'Dry Goods Store, Chilled, and Frozen Storage correct with no out of date foods?',
            'Good Seperation of raw and cooked food during storage and preparation?',
            'General temperature control procedures effective?',
            'Correct cleaning/sanitation chemicals and equipent are available and correctly used?',
            'Correct use of Hand Basin?',
            'Premises clear of insect or other pest infestation?',
            'No unnecessary accumulation of waste?',
            'Navitas system working effectively?',
        ];

        foreach ($dailyForms as $item) {
            $options['records'][] = $item;
        }
        $options['buttons_colors'] = ['yes'=>'#1aae88','no'=>'#e33244'];
        $form = \Model\Forms::create(['name' => 'Navitas Generic: Daily Check List', 'description' => 'Daily Check List', 'assigned_id' => 2, 'active' => 1, 'signature' => 1]);

        \Model\FormsItems::create(['form_id'=>$form->id,'label'=>'Please Answer the Following Questions:', 'description' => '',  'type' => 'yes_no', 'sort' => 1, 'required' => 0, 'options' => serialize($options)]);
        \Model\FormsItems::create(['form_id'=>$form->id,'label'=>'Responsible','description'=>'','placeholder'=>'Enter responsible', 'type'=>'input', 'sort'=>2, 'required'=>1, 'options'=>serialize([])]);
        \Model\FormsItems::create(['form_id'=>$form->id,'label'=>'Comment / Action','description'=>'','placeholder'=>'Enter Comment / Action.', 'type'=>'textarea', 'sort'=>3, 'required'=>1, 'options'=>serialize([])]);
        \Model\FormsItems::create(['form_id'=>$form->id,'label'=>'Section Files','description'=>'', 'type'=>'files_upload', 'sort'=>4, 'required'=>0,'options'=>$filesOptions]);
        \Model\FormsItems::create(['form_id'=>$form->id,'label'=>'Submit Form', 'description' => '', 'type' => 'submit_button', 'sort' => 5, 'required' => 0, 'options' => serialize([])]);

        $healthForms = [
            [
                'name'=>'Navitas Generic: Just Returned To Work? Starter? or a Visitor, Please Fill The Form',
                'description'=>' This form must be completed by all Food Handlers on return to work following absence due to illness or holidays',
                'options'=>[
                    1=>['records'=>["Returning to Work","New Starter","Visitor"]],
                    3=>['records'=>[
                        "Whilst you have been absent from work, have you suffered from diarrhoea, vomoting, or other stomach disorder?",
                        "Have you been in Contact with anyone from the above illness?",
                        "Whilst you have been absent from work, have you suffered from any septic or abnormal discharge from ears, eyes, nose, or skin infections?",
                        "Are you aware of any medical problem you may have?"],
                        'buttons_colors'=>['yes'=>'#CE0000','no'=>'#397B21']
                    ],
                ]
            ]
        ];
        foreach($healthForms as $item){
            $form = \Model\Forms::create(['name'=>$item['name'],'description'=>$item['description'],'assigned_id'=>1,'active'=>1,'signature'=>1]);
            \Model\FormsItems::create(['form_id'=>$form->id,'sort'=>1,'required'=>1,'label'=>'Type', 'type'=>'select',   'options'=>serialize($item['options'][1])]);
            \Model\FormsItems::create(['form_id'=>$form->id,'sort'=>2,'required'=>0,'label'=>'Date of original Assessment:','placeholder'=>'Date of original Assessment', 'type'=>'datepicker',  'options'=>serialize([])]);
            \Model\FormsItems::create(['form_id'=>$form->id,'sort'=>3,'required'=>0,'label'=>'Please Answer the Following Questions:', 'type'=>'yes_no', 'options'=>serialize($item['options'][3])]);
            \Model\FormsItems::create(['form_id'=>$form->id,'sort'=>4,'required'=>0,'label'=>'As you have answered yes to one or more of the above questions, can you confirm you are fit to return to work?','type'=>'files_upload', 'options'=>$filesOptions]);
            \Model\FormsItems::create(['form_id'=>$form->id,'sort'=>5,'required'=>0,'label'=>'Guidance to Manager','description'=>'If the answer to any of the above Questions is “Yes” the member of the staff should be refereed to their General Practitoner and should not be allowed to work until medical clearance has been given. A copy of the medical clearance note should be attached to this form, or alternatively the member of staff shall sign the folowing statment:','type'=>'paragraph', 'options'=>serialize([])]);
            \Model\FormsItems::create(['form_id'=>$form->id,'sort'=>6,'required'=>0,'label'=>'Submit Form','description'=>'','type'=>'submit_button','options'=>serialize([])]);
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