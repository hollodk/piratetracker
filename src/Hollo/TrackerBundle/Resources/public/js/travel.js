function initialize() {
    var map = new google.maps.Map(document.getElementById("map"), {
        center: {lat: 57.0445, lng: 9.93},
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    autoRefresh(map);
}

function moveMarker(map, marker, latlng) {
    marker.setPosition(latlng);
    //map.panTo(latlng);
}

function autoRefresh(map) {
    var i;

    {% for user in users %}
    var route{{ user.user.id }}, marker{{ user.user.id }};

    route{{ user.user.id }} = new google.maps.Polyline({
        path: [],
        geodesic : true,
        strokeColor: '#'+Math.floor(Math.random()*16777215).toString(16),
        strokeOpacity: 0.2,
        strokeWeight: 2,
        editable: false,
        map:map
    });

    marker{{ user.user.id }}=new google.maps.Marker({map:map,icon:"{{ asset('bundles/hollotracker/images/marker-pirate.png') }}"});

    for (i = 0; i < pathCoords{{ user.user.id }}.length; i++) {
        setTimeout(function (coords)
                {
                    var latlng = new google.maps.LatLng(coords.lat, coords.lng);
                    route{{ user.user.id }}.getPath().push(latlng);
                    moveMarker(map, marker{{ user.user.id }}, latlng);
                }, 100 * i, pathCoords{{ user.user.id }}[i]);
    }
    {% endfor %}

    for (i = 0; i < counter.length; i++) {
        setTimeout(function (i)
                {
                    var dom = document.getElementById("time").innerHTML = i.time;
                }, 100 * i, counter[i]);
    }
}

{% for user in users %}
var pathCoords{{ user.user.id }} = [
{% for pos in user.positions %}
{
    "lat": {{ pos.latitude }},
    "lng": {{ pos.longitude }}
}
{% if loop.last == false %},{% endif %}
{% endfor %}
];
{% endfor %}

var counter = [
{% for c in counter %}
{
    "time": "{{ c }}"
}
{% if loop.last == false %},{% endif %}
{% endfor %}
];

google.maps.event.addDomListener(window, 'load', initialize);
