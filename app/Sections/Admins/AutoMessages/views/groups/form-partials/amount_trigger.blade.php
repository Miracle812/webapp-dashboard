<div id="amount_trigger">
    <div class="form-group col-sm-12">
        <label class="font-bold">{{\Lang::get('/common/general.amount_trigger')}}</label>
        <label class="clear">Message will be send by trigger when amount of incident will be achieved.</label>
        <div class="row">
            <div class="col-sm-12">
                <div class="">
                    <label class="font-bold">{{\Lang::get('/common/general.amount')}}</label>
                </div>
                <div class="col-sm-9 m-t" id="amount_value_parent">
                    <input name="slider_amount_value" class="slider form-control" type="text" value="1" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="1" id="amount_slider" >
                </div>
                <div class="col-sm-3 ">
                    <input value="1" name="amount_value" type="text" class="form-control text-right" readonly="">
                </div>
            </div>
        </div>
    </div>
</div>