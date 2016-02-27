@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom clearfix">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/food-incidents')}}"><i class="material-icons">search</i> {{Lang::get('common/general.list')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <form id="wizard_advanced_form" method="post" action="">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>" />

                    <div id="wizard_advanced" data-uk-observe>

                        <h3>Step 1</h3>
                        <section>
                            <h2 class="heading_a">
                                Step 1
                            </h2>

                            <hr class="md-hr"/>

                            <div class="uk-grid">

                                <div class="uk-width-medium-1-2">
                                    <h4 class="navitas-text">Category</h4>
                                    <div class="uk-form-row parsley-row">
                                        <select name="s1_s1" required>
                                            @include('Sections\LocalManagers\FoodIncidents::partials.tree', ['menu'=>$categories, 'class'=>null])
                                        </select>
                                    </div>
                                    <div class="uk-form-row parsley-row">
                                        <h4 class="navitas-text">About the Complaint</h4>
                                        <label>Food complained about</label>
                                        <input name="s1_i1" value="" type="text" class="md-input" required />
                                    </div>

                                    <div class="uk-form-row parsley-row">
                                        <p>Date/time of purchase</p>
                                        <input name="s1_i2" value="" type="text" class="datetimepicker" required style="width:100%" />
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <h4 class="navitas-text">Complainant</h4>
                                    <div class="uk-form-row parsley-row">
                                        <label>Name</label>
                                        <input name="s1_i3" value="" type="text" class="md-input" required />
                                    </div>
                                    <div class="uk-form-row parsley-row">
                                        <label>Address</label>
                                        <input name="s1_i4" value="" type="text" class="md-input" />

                                    </div>
                                    <div class="uk-form-row parsley-row">
                                        <label>Telephone</label>
                                        <input name="s1_i5" value="" type="text" class="md-input"  />

                                    </div>
                                    <div class="uk-form-row parsley-row">
                                        <label>Email address</label>
                                        <input name="s1_i6" value="" type="text" class="md-input"  data-type="email" />

                                    </div>
                                </div>

                            </div>

                            <div class="uk-grid">
                                <div class="uk-width-1-1">
                                    <label>Incident details</label>
                                    <textarea name="s1_t1" type="text" class="md-input" rows="5" required></textarea>
                                </div>
                            </div>

                        </section>

                        <h3>Step 2</h3>
                        
                        <section>
                            <h2 class="heading_a">
                                Step 2
                            </h2>

                            <hr class="md-hr"/>

                            <div class="uk-grid">
                                <div class="uk-width-medium-1-2">
                                    <h4 class="navitas-text">Supplier of Food</h4>
                                    <div class="uk-form-row parsley-row">
                                        <label>In House</label>
                                        <select name="s2_s0">
                                            <option value="yes" >Yes</option>
                                            <option value="no" selected >No</option>
                                        </select>
                                    </div>
                                    <div class="uk-form-row parsley-row">
                                        <label>Batch number/coding</label>
                                        <input name="s2_i1" value="" type="text" class="md-input" required />
                                    </div>
                                    <div class="uk-form-row parsley-row">
                                        <p>Date coding</p>
                                        <input name="s2_i2" value="" type="text" class="datetimepicker" required style="width:100%" />
                                    </div>
                                    <div class="uk-form-row parsley-row">
                                        <p>Delivery date</p>
                                        <input name="s2_i3" value="" type="text" class="datetimepicker" required style="width:100%" />
                                    </div>
                                </div>

                                <div class="uk-width-medium-1-2">
                                    <h4 class="navitas-text">Complete if foreign body found</h4>
                                    <div class="uk-form-row parsley-row">
                                        <label>Has customer surrendered food/object:</label>
                                        <select name="s2_s1">
                                            <option selected="selected" value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                    <div id="s2_s1" class="uk-form-row parsley-row">
                                        <div class="uk-form-row parsley-row">
                                            <label>Object colour</label>
                                            <input name="s2_i4"  value="" type="text" class="md-input" required />
                                        </div>
                                        <div class="uk-form-row parsley-row">
                                            <label>Object size</label>
                                            <input name="s2_i5" value="" type="text" class="md-input" required />
                                        </div>
                                        <div class="uk-form-row parsley-row">
                                            <label>Object weight</label>
                                            <input name="s2_i6" value="" type="text" class="md-input" required />
                                        </div>
                                        <div class="uk-form-row parsley-row">
                                            <label>Possible identity</label>
                                            <input name="s2_i7" value="" type="text" class="md-input" required />
                                        </div>
                                        <div class="uk-form-row parsley-row">
                                            <label>Where exactly found in food</label>
                                            <input name="s2_i8" value="" type="text" class="md-input" required />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </section>

                        <h3>Step 3</h3>
                        
                        <section>
                            <h2 class="heading_a">
                                Step 3
                            </h2>

                            <hr class="md-hr"/>

                            <div class="uk-grid">
                                <div class="uk-width-1-1">
                                    <h4 class="navitas-text">Complete if alleged illness occurred</h4>
                                </div>
                            </div>

                            <div class="uk-grid">
                                <div class="uk-width-1-1">
                                    <div class="uk-form-row parsley-row">
                                        <label>Has alleged illness occurred?</label>
                                        <select class="md-input" name="s3_s0">
                                            <option selected="selected" value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                    <div id="s3_s0" class="uk-form-row parsley-row">
                                        <div class="uk-form-row parsley-row">
                                            <label>Food suspected to have caused illness</label>
                                            <input name="s3_i1" value="" type="text" class="md-input" required />
                                        </div>
                                        <div class="uk-form-row parsley-row">
                                            <label>Any other food purchased and eaten during the visit</label>
                                            <input name="s3_i2" value="" type="text" class="md-input" required />
                                        </div>
                                        <div class="uk-form-row parsley-row">
                                            <label>Symptoms of complaint</label>
                                            <input name="s3_i3" value="" type="text" class="md-input" required />
                                        </div>
                                        <div class="uk-form-row parsley-row">
                                            <p>Date symptoms began</p>
                                            <input name="s3_i4" value="" type="text" class="datetimepicker" required style="width:100%" />
                                        </div>
                                        <div class="uk-form-row parsley-row">
                                            <p>Date symptoms ceased</p>
                                            <input name="s3_i5" value="" type="text" class="datetimepicker" required style="width:100%" />
                                        </div>
                                        
                                        <div class="uk-form-row parsley-row">
                                            <select name="s3_s1" class="md-input">
                                                <option selected="selected" value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>

                                        <div class="uk-form-row parsley-row">                                        
                                            <select name="s3_s2" class="md-input">
                                                <option selected="selected" value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>

                                        <div class="uk-form-row parsley-row">
                                            <label>If a stool sample is available, please state:</label>
                                            <textarea name="s3_t1" class="md-input"></textarea>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </section>
                    </div>
                </form>

            </div>

        </div>
     </div>

@endsection
@section('styles')
    <link type="text/css" rel="stylesheet" href="{{ asset('newassets/packages/kendo-ui/kendo-ui.material.min.css') }}" />
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/kendo-ui/kendoui_custom.min.js') }}"></script>

    <script type="text/javascript">
        // load parsley config (altair_admin_common.js)
        altair_forms.parsley_validation_config();
        // load extra validators
        altair_forms.parsley_extra_validators();
    </script>

    <script type="text/javascript" src="{{ asset('newassets/packages/parsleyjs/dist/parsley.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/js/custom/wizard_steps.min.js') }}"></script>
    <script type="text/javascript">
    
    altair_wizard = {
        content_height: function(this_wizard,step) {
            var this_height = $(this_wizard).find('.step-'+ step).actual('outerHeight');
            $(this_wizard).children('.content').animate({ height: this_height }, 280, bez_easing_swiftOut);
        },
        advanced_wizard: function() {
            var $wizard_advanced = $('#wizard_advanced'),
                $wizard_advanced_form = $('#wizard_advanced_form');

            if ($wizard_advanced.length) {
                $wizard_advanced.steps({
                    headerTag: "h3",
                    bodyTag: "section",
                    transitionEffect: "slideLeft",
                    trigger: 'change',
                    onInit: function(event, currentIndex) {
                        altair_wizard.content_height($wizard_advanced,currentIndex);
                        // reinitialize checkboxes
                        altair_md.checkbox_radio($(".wizard-icheck"));
                        // reinitialize uikit margin
                        altair_uikit.reinitialize_grid_margin();
                        // reinitialize selects
                        altair_forms.select_elements($wizard_advanced);
                        // reinitialize switches
                        $wizard_advanced.find('span.switchery').remove();
                        altair_forms.switches();
                        // resize content when accordion is toggled
                        $('.uk-accordion').on('toggle.uk.accordion',function() {
                            $window.resize();
                        });
                        setTimeout(function() {
                            $window.resize();
                        },100);
                    },
                    onStepChanged: function (event, currentIndex) {
                        altair_wizard.content_height($wizard_advanced,currentIndex);
                        setTimeout(function() {
                            $window.resize();
                        },100)
                    },
                    onStepChanging: function (event, currentIndex, newIndex) {
                        var step = $wizard_advanced.find('.body.current').attr('data-step'),
                            $current_step = $('.body[data-step=\"'+ step +'\"]');

                        // check input fields for errors
                        $current_step.find('[data-parsley-id]').each(function() {
                            $(this).parsley().validate();
                        });

                        // adjust content height
                        $window.resize();

                        return $current_step.find('.md-input-danger').length ? false : true;
                    },
                    onFinished: function() {
                        //var form_serialized = JSON.stringify( $wizard_advanced_form.serializeObject(), null, 2 );
                        $wizard_advanced_form.submit();
                        //UIkit.modal.alert('<p>Wizard data:</p><pre>' + form_serialized + '</pre>');
                    }
                })/*.steps("setStep", 2)*/;

                $window.on('debouncedresize',function() {
                    var current_step = $wizard_advanced.find('.body.current').attr('data-step');
                    altair_wizard.content_height($wizard_advanced,current_step);
                });

                // wizard
                $wizard_advanced_form
                    .parsley()
                    .on('form:validated',function() {
                        setTimeout(function() {
                            altair_md.update_input($wizard_advanced_form.find('.md-input'));
                            // adjust content height
                            $window.resize();
                        },100)
                    })
                    .on('field:validated',function(parsleyField) {

                        var $this = $(parsleyField.$element);
                        setTimeout(function() {
                            altair_md.update_input($this);
                            // adjust content height
                            var currentIndex = $wizard_advanced.find('.body.current').attr('data-step');
                            altair_wizard.content_height($wizard_advanced,currentIndex);
                        },100);

                    });

            }
        }
    };

    $(document).ready(function(){

        altair_wizard.advanced_wizard();

        $(".datetimepicker").kendoDateTimePicker({
            format: 'yyyy-MM-dd HH:mm',
            value: new Date()
        });

        $(document).on('change','select[name="s2_s0"]',function(e){
            if($(e.target).val()=='no'){
                $( 'input[name="s2_i1"]' ).attr('required', 'required');
                $( 'input[name="s2_i2"]' ).attr('required', 'required');
                $( 'input[name="s2_i3"]' ).attr('required', 'required');
            }
            else{
                $( 'input[name="s2_i1"]' ).removeAttr('required');
                $( 'input[name="s2_i2"]' ).removeAttr('required');
                $( 'input[name="s2_i3"]' ).removeAttr('required');
            }
        })

        $(document).on('change','select[name="s2_s1"]',function(e){
            if($(e.target).val()=='yes'){
                $( '#s2_s1' ).show();
                $( 'input[name="s2_i4"]' ).attr('required', 'required');
                $( 'input[name="s2_i5"]' ).attr('required', 'required');
                $( 'input[name="s2_i6"]' ).attr('required', 'required');
                $( 'input[name="s2_i7"]' ).attr('required', 'required');
                $( 'input[name="s2_i8"]' ).attr('required', 'required');
            }
            else{
                $( 'input[name="s2_i4"]' ).removeAttr('required');
                $( 'input[name="s2_i5"]' ).removeAttr('required');
                $( 'input[name="s2_i6"]' ).removeAttr('required');
                $( 'input[name="s2_i7"]' ).removeAttr('required');
                $( 'input[name="s2_i8"]' ).removeAttr('required');
                $( '#s2_s1' ).hide();
            }
        })

        $(document).on('change','select[name="s3_s0"]',function(e){
            if($(e.target).val()=='yes'){
                $( '#s3_s0' ).show();
                $( 'input[name="s3_i1"]' ).attr('required', 'required');
                $( 'input[name="s3_i2"]' ).attr('required', 'required');
                $( 'input[name="s3_i3"]' ).attr('required', 'required');
                $( 'input[name="s3_i4"]' ).attr('required', 'required');
                $( 'input[name="s3_i5"]' ).attr('required', 'required');
            }
            else{
                $( 'input[name="s3_i1"]' ).removeAttr('required');
                $( 'input[name="s3_i2"]' ).removeAttr('required');
                $( 'input[name="s3_i3"]' ).removeAttr('required');
                $( 'input[name="s3_i4"]' ).removeAttr('required');
                $( 'input[name="s3_i5"]' ).removeAttr('required');
                $( '#s3_s0' ).hide();
            }
        })
    });
    </script>
@endsection