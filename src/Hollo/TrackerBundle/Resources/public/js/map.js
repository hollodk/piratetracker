var infowindow = new google.maps.InfoWindow();
var map = new google.maps.Map(document.getElementById("map_canvas"), {
    "mapTypeId":google.maps.MapTypeId.ROADMAP,
    "zoom": zoom
});
var markers = [];
var markersFirstRun = true;

var center = new google.maps.LatLng(center.lat, center.lon);
map.setCenter(center);

$(document).ready(function() {

    getMarkers();
    getImages();
    getRoutes();

    setInterval(function() {
        getMarkers();
    }, 15000);

});
