var infowindow = new google.maps.InfoWindow();
var map = new google.maps.Map(document.getElementById("map_canvas"), {
    "mapTypeId":google.maps.MapTypeId.ROADMAP,
    "zoom": zoom
});

var center = new google.maps.LatLng(center.lat, center.lon);
map.setCenter(center);

$(document).ready(function() {

    $.ajax({
        type: 'GET',
        url: imagesUrl
    })
    .done(function(data) {
        $.each(data.images, function(key, value) {
            var latLng1 = new google.maps.LatLng(value.coords.x1, value.coords.x2);
            var latLng2 = new google.maps.LatLng(value.coords.y1, value.coords.y2);
            var latLngBounds = new google.maps.LatLngBounds(latLng1, latLng2);

            new google.maps.GroundOverlay(value.url, latLngBounds, {"map":map});
        });
    });

    $.ajax({
        type: 'GET',
        data: data,
        url: routesUrl
    })
    .done(function(data) {
        $.each(data.routes, function(key, value) {
            var path = [];

            for (var i = 0; i < value.coords.length; i++) {
                latLng = new google.maps.LatLng(value.coords[i].lat, value.coords[i].lon);
                path.push(latLng);
            }

            new google.maps.Polyline({
                "map":map,
                "path":path,
                "strokeColor":value.color
            });
        });
    });

    $.ajax({
        type: 'GET',
        url: markersUrl
    })
    .done(function(data) {
        $.each(data.markers, function(key, value) {
            var effect;

            latLng = new google.maps.LatLng(value.coords.lat, value.coords.lon);
            image = new google.maps.MarkerImage(value.icon);

            if (value.effect == 'bounce') {
                effect = google.maps.Animation.BOUNCE;
            } else if (value.effect == 'drop') {
                effect = google.maps.Animation.DROP;
            } else {
                effect = null;
            }

            var m = new google.maps.Marker({
                "position":latLng,
                "map":map,
                "animation":effect,
                "icon":image
            });

            m.addListener('click', function() {
                infowindow.close();
                infowindow.setContent(value.infowindow);
                infowindow.open(map, m);
            });
        });
    });
});
