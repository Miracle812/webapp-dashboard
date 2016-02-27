    IsEditForm = $('#form-googlemap,#gmap_geocoding').length ? true : false;
    if(IsEditForm) {
        $GMapAddress = IsEditForm ? $('input[name="post_code"]').val() + ' ' + $('input[name="city"]').val() + ' ' + $('input[name="street"]').val() : $('#GMapAddrres1').text() + ' ' + $('#GMapAddrres2').text();
        var gmap_lat = $('input[name="gmap_lat"]');
        var gmap_lng = $('input[name="gmap_lng"]');
        var gmap_zoom = $('input[name="gmap_zoom"]');
        var modalWinow = $(document).find('.modal');
        var mapSelector = '#gmap_geocoding';
        var mapSection = $(mapSelector);
        if (modalWinow.length) {
            mapSection = $(modalWinow).find('#gmap_geocoding_modal');
            mapSelector = mapSelector + '_modal';
        }
        var gmap_lat_val = mapSection.data('gmaplat');
        var gmap_lng_val = mapSection.data('gmaplng');
        var gmap_zoom_val = parseInt(mapSection.data('gmapzoom'));

        gmap_lat_val = gmap_lat_val == '' ? 52.362169700511345 : gmap_lat_val;
        gmap_lng_val = gmap_lng_val == '' ? -1.6621998464843273 : gmap_lng_val;
        gmap_zoom_val = isNaN(gmap_zoom_val) ? 6 : gmap_zoom_val;

        map = new GMaps({
            div: mapSelector,
            lat: gmap_lat_val,
            lng: gmap_lng_val,
            zoom: gmap_zoom_val
        });

        map.addMarker({
            lat: gmap_lat_val,
            lng: gmap_lng_val,
            draggable: IsEditForm ? true : false,
            dragend: function (e) {
                gmap_lat.val(e.latLng.lat());
                gmap_lng.val(e.latLng.lng());
                gmap_zoom.val(map.getZoom());
            }
        });

        mapSection.submit(function (e) {
            e.preventDefault();
            GMaps.geocode({
                address: $('#address').val().trim(),
                callback: function (results, status) {
                    if (status == 'OK') {

                        var latlng = results[0].geometry.location;
                        map.setCenter(latlng.lat(), latlng.lng());
                        map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng()
                        });
                    }
                }
            });
        });
        $GmapInputAddress = $('form').find('input.gmaploc');
        $GmapInputAddress.on('change', function () {
            zoom = $('input[name="street_number"]').val().length ? 18 : 12;
            var address = [];
            $GmapInputAddress.each(function () {
                address.push($(this).val())
            });
            GMaps.geocode({
                address: address.join(", "),
                callback: function (results, status) {
                    if (status == 'OK') {
                        map.removeMarkers();
                        var latlng = results[0].geometry.location;
                        var gmap_lat = $('input[name="gmap_lat"]'), gmap_lng = $('input[name="gmap_lng"]'), gmap_zoom = $('input[name="gmap_zoom"]');
                        gmap_lat.val(latlng.lat());
                        gmap_lng.val(latlng.lng());
                        gmap_zoom.val(map.getZoom());
                        map.setCenter(latlng.lat(), latlng.lng());
                        map.setZoom(zoom);
                        map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng(),
                            draggable: true,
                            dragend: function (e) {
                                gmap_lat.val(e.latLng.lat());
                                gmap_lng.val(e.latLng.lng());
                                gmap_zoom.val(map.getZoom());
                            }
                        });
                    }
                }
            });
        });
    }