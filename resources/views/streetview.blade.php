<div id="map-canvas-{!! $id !!}" style="width: 100%; height: 100%; margin: 0; padding: 0; position: relative; overflow: hidden;"></div>
<script type="text/javascript">
    function GMapInit_{!! $id !!}() {
        var bounds = new google.maps.LatLngBounds();
        var position = new google.maps.LatLng({!! $options['latitude'] !!}, {!! $options['longitude'] !!});

        var mapOptions = {
            @if ($options['center'])
            center: position,
            @endif
            zoom: {!! $options['zoom'] !!},
            mapTypeId: google.maps.MapTypeId.{!! $options['type'] !!},
            disableDefaultUI: @if (!$options['ui']) true @else false @endif
        };

        var map = new google.maps.Map(document.getElementById('map-canvas-{!! $id !!}'), mapOptions);

        var panoramaOptions = {
            position: position,
            pov: {
                heading: {!! $options['heading'] !!},
                pitch: {!! $options['pitch'] !!}
            }
        };

        var panorama = new google.maps.StreetViewPanorama(document.getElementById('map-canvas-{!! $id !!}'), panoramaOptions);

        map.setStreetView(panorama);

        window.maps.push({
            key: {!! $id !!},
            map: map
        });
    }
    window.initMaps.push({ method: GMapInit_{!! $id !!} });
</script>