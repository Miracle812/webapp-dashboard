var validMin, validMax,
    $warningMin = $('#warning-min'),
    $warningMax = $('#warning-max'),
    $validRange = $('#valid-range');
$(function() {
    $warningMin.slider({
        formater: function(value) {
            return value+' ℃';
        }
    }).on('slideStop', function(ev){
        var $dngMinVal = ev.value;
        if(isNaN($dngMinVal)){
            $dngMinVal = validMin;
            $(this).slider('setValue', $dngMinVal);
        }
        $('input[name=warning_min]').val($dngMinVal);
    });

    $validRange.slider({
        formater: function(value) {
            return value+' ℃';
        }
    }).on('slideStop', function(ev){
        newMin=$(this).data('slider').value[0];
        newMax=$(this).data('slider').value[1];
        if(validMin !== newMin) {
            validMin = newMin;
            $warningMin.data('slider').max = newMin;
            $warningMin.slider('setValue', newMin);
            $('input[name=valid_min]').val(newMin);
            $('input[name=warning_min]').val(newMin);
        }
        if(validMax !== newMax) {
            validMax = newMax;
            $warningMax.data('slider').min = newMax;
            $warningMax.slider('setValue', newMax);
            $('input[name=valid_max]').val(newMax);
            $('input[name=warning_max]').val(newMax);
        }
    });
    $warningMax.slider({
        formater: function(value) {
            return value+' ℃';
        }
    }).on('slideStop', function(ev){
        var $dngMaxVal = ev.value;
        if(isNaN($dngMaxVal)){
            $dngMaxVal = validMax;
            $(this).slider('setValue', $dngMaxVal);
        }
        $('input[name=warning_max]').val($dngMaxVal);
    });
    validVAlue = $validRange.data('slider').value;
    validMin = validVAlue[0];
    validMax = validVAlue[1];

    window.addEventListener('resize', function(event){
        $('.warning-min .slider').width($('.warning-min').width());
        $('.warning-max .slider').width($('.warning-max').width());
        $('.valid-range .slider').width($('.valid-range').width());
    });
});