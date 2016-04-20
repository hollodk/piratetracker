function initialize() {
    var map = new google.maps.Map(document.getElementById("map"), {
        center: {lat: 57.0445, lng: 9.93},
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    autoRefresh(map);

    for (i = 0; i < counter.length; i++) {
        setTimeout(function (i)
                {
                    var dom = document.getElementById("time").innerHTML = i.time;
                }, 100 * i, counter[i]);
    }
}

function moveMarker(map, marker, latlng) {
    marker.setPosition(latlng);
    //map.panTo(latlng);
}

function autoRefresh(map) {
    var i, j, routes = [], markers = [];

    for (i = 0; i < users.length; i++) {
        routes[i] = new google.maps.Polyline({
            path: [],
            geodesic : true,
            strokeColor: '#'+Math.floor(Math.random()*16777215).toString(16),
            strokeOpacity: 0.2,
            strokeWeight: 2,
            editable: false,
            map:map
        });

        if (users[i].rank == 'captain') {
            markers[i] = new google.maps.Marker({map:map,icon:captainIcon});
        } else if (users[i].rank == 'ship') {
            markers[i] = new google.maps.Marker({map:map,icon:shipIcon});
        } else {
            markers[i] = new google.maps.Marker({map:map,icon:pirateIcon});
        }

        for (j = 0; j < users[i].positions.length; j++) {
            setTimeout(function (coords, i)
            {
                var latlng = new google.maps.LatLng(coords.latitude, coords.longitude);
                routes[i].getPath().push(latlng);

                if (coords.active == false) {
                    if (users[i].rank == 'captain') {
                        markers[i].setIcon(captainInactiveIcon);
                    } else if (users[i].rank == 'ship') {
                        markers[i].setIcon(shipIcon);
                    } else {
                        markers[i].setIcon(pirateInactiveIcon);
                    }

                } else {
                    if (users[i].rank == 'captain') {
                        markers[i].setIcon(captainIcon);
                    } else if (users[i].rank == 'ship') {
                        markers[i].setIcon(shipIcon);
                    } else {
                        markers[i].setIcon(pirateIcon);
                    }
                }

                moveMarker(map, markers[i], latlng);
            }, 100 * j, users[i].positions[j], i);
        }
    }
}

google.maps.event.addDomListener(window, 'load', initialize);
