
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            @foreach($pieTemperatures as $group => $data)
            <div class="col-sm-6">
                <section class="panel bg-white">
                    <div class="panel-heading bg-default b-light bg-light">
                        <h4> @if($group == 'probes') Probes @else Pods @endif Temperatures Compliance</h4>
                    </div>
                    <span class="media-body block m-b-none">
                        <div pie-temperatures='{{$data['data']}}'></div>
                    </span>
                    <div class="row text-center no-gutter">
                    </div>
                </section>
            </div>
            @endforeach
        </div>
    </div>
</div>