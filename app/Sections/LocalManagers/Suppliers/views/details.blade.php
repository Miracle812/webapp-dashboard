@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/suppliers')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <div class="uk-grid">

                        <div class="uk-width-large-1-2">

                            <div class="uk-grid">

                                @if($supplier->logo)
                                <div class="uk-width-large-1-2">
                                     <div class="thumbnail">
                                        <img src="{{$supplier->logo}}" width="100%">
                                     </div>
                                </div>
                                @endif
                                <div class="uk-width-large-1-2">
                                    <h4 class="navitas-text font-bold m-b">{{$supplier->name}}</h4>
                                    <ul class="uk-list">
                                        <li id="GMapAddrres1"><i class="material-icons m-r">home</i>{{$supplier->post_code}} {{$supplier->city}}</li>
                                        <li id="GMapAddrres2"><i class="material-icons m-r">visibility</i>{{$supplier->street_number}}</li>
                                        <li><i class="material-icons m-r">phone</i>{{$supplier->phone}}</li>
                                        <li><i class="material-icons m-r">email</i>{{$supplier->email}}</li>
                                        <li><i class="material-icons m-r">person</i>{{$supplier->contact_person}}</li>
                                    </ul>
                                </div>

                            </div>

                        </div>
                    
                        <div class="uk-width-large-1-2 thumbnail">
                            <section
                                id="gmap_geocoding"
                                style="height:200px;"
                                data-gmaptitle="{{$supplier->name}}"
                                data-gmaplat="{{$supplier->gmap_lat}}"
                                data-gmaplng="{{$supplier->gmap_lng}}"
                                data-gmapzoom="{{$supplier->gmap_zoom}}">
                            </section>
                        </div>

                    </div>

                    <div class="uk-grid">

                        <div class="uk-width-medium-1-2">
                            <h4 class="navitas-text font-bold m-b">{{Lang::get('common/general.supplied_products')}}:</h4>
                            
                            <div class="m-l">{{$supplier->toString($supplier->getProducts())}}</div>

                            <h4 class="h4 navitas-text font-bold">{{\Lang::get('/common/general.rule_temperatures')}}:</h4>

                            <div class="uk-grid">
                                
                                <div class="uk-width-medium-1-2">
                                    <div class="m-b">
                                        <label class="inline-label navitas-text">{{\Lang::get('/common/general.valid_min')}}</label>
                                        <div class="m-l">{{$supplier->valid_min}} &#x2103</div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="m-b">
                                        <label class="inline-label navitas-text">{{\Lang::get('/common/general.valid_max')}}</label>
                                        <div class="m-l">{{$supplier->valid_max}} &#x2103</div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="m-b">
                                        <label class="inline-label navitas-text">{{\Lang::get('/common/general.warning_min')}} (below)</label>
                                        <div class="m-l">{{$supplier->warning_min}} &#x2103</div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="m-b">
                                        <label class="inline-label navitas-text">{{\Lang::get('/common/general.warning_max')}} (above)</label>
                                        <div class="m-l">{{$supplier->warning_max}} &#x2103</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="uk-width-medium-1-2">

                            <h4 class="navitas-text font-bold m-b">{{Lang::get('common/general.additional_contacts')}}:</h4>

                            <div class="uk-grid">
                                <div class="uk-width-medium-1-2">
                                    @if($supplier->contact_person1)
                                    <ul class="list-unstyled">
                                        <li><i class="fa fa-user m-r"></i><span class="font-bold">{{$supplier->contact_person1}}</span></li>
                                        <li><i class="fa fa-phone m-r"></i>{{$supplier->phone1}}</li>
                                        <li><i class="i i-mail2 m-r"></i>{{$supplier->email2}}</li>
                                    </ul>
                                    @endif
                                </div>
                                <div class="uk-width-medium-1-2">
                                    @if($supplier->contact_person2)
                                    <ul class="list-unstyled">
                                        <li><i class="fa fa-user m-r"></i><span class="font-bold">{{$supplier->contact_person1}}</span></li>
                                        <li><i class="fa fa-phone m-r"></i>{{$supplier->phone1}}</li>
                                        <li><i class="i i-mail2 m-r"></i>{{$supplier->email2}}</li>
                                    </ul>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <div class="md-card">
                <div class="md-card-content">

                    <h3 class="heading_a">{{Lang::get('common/sections.goods-in.title')}}</h3>

                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <table class="uk-table uk-table-striped uk-table-valign-middle dataTable" id="dataTable" data-source="{{URL::to('/goods-in/datatable/supplier/'.$supplier->id)}}">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{Lang::get('common/general.created')}}</th>
                                        <th>{{Lang::get('common/general.staff')}}</th>
                                        <th>{{Lang::get('common/general.products')}}</th>
                                        <th>{{Lang::get('common/general.temperature')}}</th>
                                        <th>{{Lang::get('common/general.package_accept')}}</th>
                                        <th>{{Lang::get('common/general.date_code_valid')}}</th>
                                        <th>{{Lang::get('common/general.compliant')}}</th>
                                        <th>{{Lang::get('common/general.details')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/js/custom/datatables_uikit.min.js') }}"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="{{ asset('newassets/packages/maplace-js/dist/maplace.min.js')}}"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        var $dataTable = $('#dataTable');
        if($dataTable.length) {
            $dataTable.DataTable({
                "ajax": $dataTable.data('source'),
                "columnDefs": [{
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                },{
                    "orderable": false, "targets": [5,6,7,8]
                }]
            })
        }

        var $gmap_geocoding = $( '#gmap_geocoding' );
        var gmap_loc = [
            {
                lat: $gmap_geocoding.data('gmaplat'),
                lon: $gmap_geocoding.data('gmaplng'),
                title: $gmap_geocoding.data('gmaptitle')
            }
        ];
        new Maplace({
            locations: gmap_loc,
            map_div: '#gmap_geocoding',
            map_options: {mapTypeId: google.maps.MapTypeId.ROADMAP, zoom: 12}
        }).Load();

    });
    </script>
@endsection