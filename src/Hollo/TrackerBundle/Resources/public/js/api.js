function getMarkers(firstRun)
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

                    if (markers[i].latlng != value.coords.lat+value.coords.lng) {
                        move = true;
                        movingMarker = markers[i];
                    }
                }
            }

            if (!match) {
                var latLng = new google.maps.LatLng(value.coords.lat, value.coords.lng);
                var image = new google.maps.MarkerImage(value.icon);

                var animation;

                if (!firstRun) {
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
                    latlng: value.coords.lat+value.coords.lng,
                    coords: value.coords,
                    marker: marker
                };

                markers.push(r);

                marker.addListener('click', function() {
                    infowindow.close();
                    infowindow.setContent(value.infowindow);
                    infowindow.open(map, marker);
                });

                log(value, 0, firstRun);

            } else if (move) {
                var latLng = new google.maps.LatLng( value.coords.lat, value.coords.lng );
                var distance = google.maps.geometry.spherical.computeDistanceBetween (movingMarker.marker.position, latLng);

                movingMarker.marker.setPosition(latLng);
                movingMarker.latlng = value.coords.lat+value.coords.lng;

                var coords = [
                {lat: movingMarker.coords.lat, lng: movingMarker.coords.lng},
                {lat: value.coords.lat, lng: value.coords.lng},
                ];
                var coordsPath = new google.maps.Polyline({
                    path: coords,
                    geodesic: true,
                    strokeColor: '#333',
                    strokeOpacity: 1.0,
                    strokeWeight: 1
                });

                coordsPath.setMap(map);

                setTimeout(function() {
                    coordsPath.setMap(null);
                }, 600000);

                log(value, distance, firstRun);
            }
        });
    });
}

function log(value, distance, firstRun)
{
    var distanceText;

    if (distance > 1000) {
        distanceText = Math.ceil(distance/1000)+'km';
    } else {
        distanceText = Math.ceil(distance)+'m';
    }

    if (!firstRun) {
        if (value.type == 'user') {
            if (distance == 0) {
                $('#event_log').prepend('<p><span class="fa fa-map-marker"></span> '+value.time+', welcome to ' +value.name);
                $('#event_log').show();
            } else if (distance > 50) {
                $('#event_log').prepend('<p><span class="fa fa-globe"></span> '+value.time+', '+value.name+' moved '+distanceText);
                $('#event_log').show();
            }
        } else if (value.type == 'shout') {
            $('#event_log').prepend('<p><span class="fa fa-bullhorn"></span> '+value.time+', '+value.name+' shouted!!');
            $('#event_log').show();
        }
    }
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
                latLng = new google.maps.LatLng(value.coords[i].lat, value.coords[i].lng);
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
