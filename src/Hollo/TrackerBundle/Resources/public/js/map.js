var infowindow = new google.maps.InfoWindow();
var map = new google.maps.Map(document.getElementById("map_canvas"), {
    "mapTypeId":google.maps.MapTypeId.ROADMAP,
    "zoom": zoom
});
var markers = [];

var center = new google.maps.LatLng(center.lat, center.lng);
map.setCenter(center);

$(document).ready(function() {

    getMarkers(true);
    getImages();
    getRoutes();

    setInterval(function() {
        getMarkers(false);
    }, 15000);
});
