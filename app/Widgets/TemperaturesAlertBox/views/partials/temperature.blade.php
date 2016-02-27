<div class="col-sm-6 col-md-3">
    <section class="panel no-border">
        <div class="panel-heading no-border btn-danger lt small">
            <span class="pull-right badge dk small">ALARM</span>
            <div class="text-lt block font-bold text-white" href="#">{{$temperature->temperature}}</div>
        </div>
        <div class="panel-body">
            <div class="text-center" id="b-c">
                <div data-animate="2000" data-line-cap="round" data-size="110" data-scale-color="false" data-track-color="#f2f4f8" data-bar-color="#E33244" data-line-width="9" data-percent="{{$temperature->temperature}}" class="easypiechart inline easyPieChart temperatures-widget">
                    <div>
                        <span class="h3 step font-bold text-danger">{{$temperature->temperature}}</span>
                        <span class="h4 "><sup>o</sup>C</span>
                        <div class="text text-xs text-danger bold">alert</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix panel-footer text-center">
            <button
                class="btn btn-sm btn-danger"
                data-toggle="popover"
                data-html="true"
                data-placement="top"
                data-content='
                <div class="aside">
                    <div class="row m-b">
                        <div class="col-sm-6 p-r-0">Valid range:</div>
                        <div class="col-sm-6 p-l-0 text-right h5 font-bold"> {{$temperature->temperature}} </div>
                    </div>
                    <div class="row m-b">
                        <div class="col-sm-6 p-r-0">Last temp.: </div>
                        <div class="col-sm-6 p-l-0 font-bold h5 text-right text-danger"> {{$temperature->temperature}} </div>
                    </div>
                    <div class="row m-b">
                        <div class="col-sm-6 p-r-0">Daily max: </div>
                        <div class="col-sm-6 p-l-0 font-bold h5 text-right"> {{$temperature->temperature}} </div>
                    </div>
                    <div class="row m-b">
                        <div class="col-sm-6 p-r-0">Daily min: </div>
                        <div class="col-sm-6 p-l-0 font-bold h5 text-right"> {{$temperature->temperature}} </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 p-r-0">Daily average:</div>
                        <div class="col-sm-6 p-l-0 font-bold h5 text-right"> {{$temperature->temperature}} </div>
                    </div>
                </div>
                '
                title='Alert Details <div style="font-size:10px;">(Data from {{$temperature->temperature}})</div>'
                data-original-title='<button type="button" class="close pull-right" data-dismiss="popover">&times;</button>Popover on top'>more</button>
        </div>
    </section>
</div>