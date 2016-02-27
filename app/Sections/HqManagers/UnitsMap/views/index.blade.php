@extends('_manager.layouts.manager')
@section('content')
<section id="content">
    <section class="vbox bg-white">
        <section>
            <section class="hbox stretch">
                <section id="gmap_geocoding" style="min-height:240px;" data-url="/units-map/map-data" ></section>
                <aside class="wrapper aside-lg">
                    <p class="text-lg font-thin">Google Maps</p>
                    <form method="post" id="geocoding_form" class="m-t-sm">
                        <div class="input-group">
                            <input type="text" id="address" name="address" class="input-sm form-control" placeholder="Search">
                        <span class="input-group-btn">
                          <button class="btn btn-sm btn-default" type="submit">Go!</button>
                        </span>
                        </div>
                    </form>
                    <div class="row">
                        <p class="col-sm-2 text-xs">From: </p>
                        <p id="start-name" class="text-sm font-thin col-sm-10 text-primary"></p>
                    </div>
                    <div class="row">
                    <p class="col-sm-2 text-xs">To: </p>
                        <p id="end-name" class="text-sm font-thin col-sm-10 text-primary"></p>
                    </div>
                    <a id="start_travel" class="btn btn-default btn-rounded btn-sm m-b">Start</a>
                    <ul id="instructions" class="list-unstyled scrollable h400 text-xs"></ul>
                </aside>
            </section>
        </section>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen,open" data-target="#nav,html"></a>
</section>
<input id="start-input" type="hidden" value=""/>
<input id="end-input" type="hidden" value=""/>
@endsection
@section('css')
    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
@endsection
@section('js')
    <script src="/assets/js/maps/gmaps.js"></script>
<script>
    $(document).ready(function(){
        $('#gmap_geocoding').height($(document).find('aside#nav').height());
        url = $('#gmap_geocoding').data('url');
        var map;
        var eventMarker;
        var directionsService = new google.maps.DirectionsService();
        $.getJSON(url, function(data){
            var locations  = data;
            map = new google.maps.Map(document.getElementById('gmap_geocoding'), {
                zoom: 10,
                center: new google.maps.LatLng(-39.92, 151.25),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            var infowindow = new google.maps.InfoWindow();
            var marker, i;
            var markers = new Array();
            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    animation: google.maps.Animation.DROP,
                    icon: new google.maps.MarkerImage("/assets/images/google-maps-"+locations[i][4]+".png")
                });
                markers.push(marker);
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        eventMarker = marker;
                        content = '<div data-location="('+locations[i][1]+','+locations[i][2]+')">Set type: <a href="#road-start" class="font-bold text-primary">Start</a> or <a href="#road-end" class="font-bold text-primary">End</a> road.</div>'
                        infowindow.setContent(locations[i][0] + content);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
            function AutoCenter() {
                var bounds = new google.maps.LatLngBounds();
                $.each(markers, function (index, marker) {
                    bounds.extend(marker.position);
                });
                map.fitBounds(bounds);
            }
            AutoCenter();
        });

        $('#geocoding_form').submit(function(e){
            e.preventDefault();
            geocoder = new google.maps.Geocoder();
            geocoder.geocode( { 'address': $('#address').val().trim()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    address = results[0].formatted_address;
                    map.setCenter(results[0].geometry.location);

                    content = '<div data-location="'+results[0].geometry.location+'">Set type: <a href="#road-start" class="font-bold text-primary">Start</a> or <a href="#road-end" class="font-bold text-primary">End</a> road.</div>'

                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location,
                        animation: google.maps.Animation.DROP,
                        title: address + content
                    });
                    google.maps.event.addListener(marker, 'click', function(){
                        eventMarker = marker;
                        openInfoWindow(marker);
                    });
                }
            })
        });

        $(document).on('click', 'a[href="#road-start"]', function(e){
            markerPosition = eventMarker.getPosition();
            geocodePosition(markerPosition, function(address){
                if(address!='undefined'){
                    $('#start-name').html(address);
                    $('#start-input').val(markerPosition);
                }
            });
            return false;
        })

        $(document).on('click', 'a[href="#road-end"]', function(e){
            markerPosition = eventMarker.getPosition();
            geocodePosition(markerPosition, function(address){
                if(address!='undefined'){
                    $('#end-name').html(address);
                    $('#end-input').val(markerPosition);
                }
            });
            return false;
        })

        $('#start_travel').click(function(e){
            $('#instructions').html('');
            e.preventDefault();
            initialize()
            calcRoute();
        });

        function openInfoWindow( marker ){
            var title = marker.getTitle();
            infowindow = new google.maps.InfoWindow({
                content: title
            });
            infowindow.open(map, marker);
        }

        function geocodePosition(pos, callback) {
            var geoaddress;
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                if (responses && responses.length > 0) {
                    geoaddress = responses[0].formatted_address;
                    callback(geoaddress);
                }
            })
        }

        function initialize() {
            directionsDisplay = new google.maps.DirectionsRenderer();
            directionsDisplay.setMap(map);
            directionsDisplay.setPanel(document.getElementById('instructions'));
        }

        function calcRoute() {

            var start = $('#start-input').val();
            var end = $('#end-input').val();
            if(!start || !end){
                alert ('Please select start and end of road.')
            }
            else{
                google.maps.event.trigger(map, 'resize');
                var request = {
                    origin: start,
                    destination: end,
                    travelMode: google.maps.DirectionsTravelMode.DRIVING
                };
                directionsService.route(request, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                    }
                });
            }
        }
    })
</script>
@endsection