!function ($) {

  $(function(){

    IsEditForm = $('#form-googlemap').length ? true : false;
    $GMapAddress = IsEditForm ? $('input[name="post_code"]').val()+' '+$('input[name="city"]').val()+' '+$('input[name="street"]').val(): $('#GMapAddrres1').text()+' '+$('#GMapAddrres2').text();

    var gmap_lat  = $('input[name="gmap_lat"]');
    var gmap_lng  = $('input[name="gmap_lng"]');
    var gmap_zoom = $('input[name="gmap_zoom"]');
    var mapSection = $('#gmap_geocoding');

    var gmap_lat_val  = mapSection.data('gmaplat');
    var gmap_lng_val  = mapSection.data('gmaplng');
    var gmap_zoom_val = parseInt(mapSection.data('gmapzoom'));

    gmap_lat_val = gmap_lat_val == '' ? 52.362169700511345 : gmap_lat_val;
    gmap_lng_val = gmap_lng_val == '' ? -1.6621998464843273 : gmap_lng_val;
    gmap_zoom_val = isNaN(gmap_zoom_val) ? 6 : gmap_zoom_val;

    map = new GMaps({
      div: '#gmap_geocoding',
      lat: gmap_lat_val,
      lng: gmap_lng_val,
      zoom: gmap_zoom_val
    });

    map.addMarker({
      lat: gmap_lat_val,
      lng: gmap_lng_val,
      draggable: IsEditForm ? true : false,
      dragend: function(e) {
        gmap_lat.val(e.latLng.lat());
        gmap_lng.val(e.latLng.lng());
        gmap_zoom.val(map.getZoom());
      }
    });

    $('#geocoding_form').submit(function(e){
      e.preventDefault();
      GMaps.geocode({
        address: $('#address').val().trim(),
        callback: function(results, status){
          if(status=='OK'){

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

    $('#start_travel').click(function(e){
      $('#instructions').html('');
      e.preventDefault();
      map.setZoom(8);
      map.travelRoute({
        origin: [37.77493,-122.419416],
        destination: [37.339386,-121.894955],
        travelMode: 'driving',
        step: function(e){
          $('#instructions').append('<li><i class="fa-li fa fa-map-marker fa-lg icon-muted"></i> '+e.instructions+'</li>');
          $('#instructions li:eq('+e.step_number+')').delay(450*e.step_number).fadeIn(200, function(){
            map.setCenter(e.end_location.lat(), e.end_location.lng());
            map.drawPolyline({
              path: e.path,
              strokeColor: '#131540',
              strokeOpacity: 0.6,
              strokeWeight: 4
            });
          });
        }
      });
    });
    $GmapInputAddress = $('form').find('input.gmaploc');

    $GmapInputAddress.on('change',function(){
      zoom = $('input[name="street_number"]').val().length ? 18 : 12;
      var address = [];
      $GmapInputAddress.each(function(){
        address.push($(this).val())
      });
      GMaps.geocode({
        address: address.join(", "),
        callback: function(results, status){
          if(status=='OK'){
            map.removeMarkers();
            var latlng = results[0].geometry.location;
            var gmap_lat = $('input[name="gmap_lat"]'),gmap_lng = $('input[name="gmap_lng"]'), gmap_zoom = $('input[name="gmap_zoom"]');
            gmap_lat.val(latlng.lat());
            gmap_lng.val(latlng.lng());
            gmap_zoom.val(map.getZoom());
            map.setCenter(latlng.lat(), latlng.lng());
            map.setZoom(zoom);
            map.addMarker({
              lat: latlng.lat(),
              lng: latlng.lng(),
              draggable: true,
              dragend: function(e) {
                gmap_lat.val(e.latLng.lat());
                gmap_lng.val(e.latLng.lng());
                gmap_zoom.val(map.getZoom());
              }
            });
          }
        }
      });
    })
  });
}(window.jQuery);