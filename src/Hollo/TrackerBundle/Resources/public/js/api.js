function getMarkers()
{
    $.ajax({
        type: 'GET',
        url: markersUrl
    })
    .done(function(data) {
        $.each(data.markers, function(key, value) {

            var match = false;
            var move = false;
            var movingMarker = null;

            for (i = 0; i < markers.length; i++) {
                if (markers[i].id == value.id) {
                    match = true;

                    if (markers[i].latlng != value.coords.lat+value.coords.lon) {
                        move = true;
                        movingMarker = markers[i];
                    }
                }
            }

            if (!match) {
                var latLng = new google.maps.LatLng(value.coords.lat, value.coords.lon);
                var image = new google.maps.MarkerImage(value.icon);

                var animation;

                if (!markersFirstRun) {
                    animation = google.maps.Animation.BOUNCE;
                } else {
                    if (value.minutes_ago < 20) {
                        animation = google.maps.Animation.BOUNCE;
                    }
                }

                var marker = new google.maps.Marker({
                    "position":latLng,
                    "map":map,
                    "animation":animation,
                    "icon":image
                });

                setTimeout(function() {
                    marker.setAnimation(null);
                }, 600000);

                var r = {
                    id: value.id,
                    latlng: value.coords.lat+value.coords.lon,
                    marker: marker
                };

                markers.push(r);

                marker.addListener('click', function() {
                    infowindow.close();
                    infowindow.setContent(value.infowindow);
                    infowindow.open(map, marker);
                });
            } else if (move) {
                var latLng = new google.maps.LatLng( value.coords.lat, value.coords.lon );

                movingMarker.marker.setPosition(latLng);
                movingMarker.latlng = value.coords.lat+value.coords.lon;
            }
        });

        markersFirstRun = false;
    });
}

function getImages()
{
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
}

function getRoutes()
{
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
}
