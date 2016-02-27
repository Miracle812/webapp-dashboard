function MapApiLoaded() {
    $.ajaxSetup({async: false});
    $.getScript("/assets/js/maps/gmaps.js");
    $.getScript("/assets/init/form.gmap.init.js");
    $.ajaxSetup({async: true});
}
if (!(typeof window.google === 'object' && window.google.maps && google.maps)) {
    $.getScript("https://maps.google.com/maps/api/js?sensor=false&async=2&callback=MapApiLoaded", function () {});
}
else {
    MapApiLoaded()
}

