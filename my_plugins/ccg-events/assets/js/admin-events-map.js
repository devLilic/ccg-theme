jQuery(function($){

    const mapEl = $('#ccg-event-map');

    if (!mapEl.length) return;

    const lat = parseFloat(mapEl.data('lat'));
    const lng = parseFloat(mapEl.data('lng'));
    const zoom = parseInt(mapEl.data('zoom'));

    // INIT MAP
    const map = L.map('ccg-event-map').setView([lat, lng], zoom);

    // TILE LAYER
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    // === FIX IMPORTANT Leaflet ===
    setTimeout(function () {
        map.invalidateSize(true);
    }, 300);

    // MARKER
    let marker = L.marker([lat, lng], {draggable: true}).addTo(map);

    function updateInputs(lat, lng) {
        $('#ccg_event_lat').val(lat);
        $('#ccg_event_lng').val(lng);
    }

    function updateMarkerPosition() {
        let nLat = parseFloat($('#ccg_event_lat').val());
        let nLng = parseFloat($('#ccg_event_lng').val());
        marker.setLatLng([nLat, nLng]);
        map.panTo([nLat, nLng]);
    }

    // CLICK ON MAP → update
    map.on('click', function(e){
        marker.setLatLng(e.latlng);
        updateInputs(e.latlng.lat, e.latlng.lng);
    });

    // DRAG MARKER → update
    marker.on('dragend', function(e){
        const pos = e.target.getLatLng();
        updateInputs(pos.lat, pos.lng);
    });

    // INPUTS → update marker
    $('#ccg_event_lat, #ccg_event_lng').on('change keyup', updateMarkerPosition);

    // SAVE ZOOM
    map.on('zoomend', function(){
        $('#ccg_event_map_zoom').val(map.getZoom());
    });

    // SEARCH (Nominatim)
    $('#ccg_event_map_search').on('keyup', function(e){
        if (e.keyCode !== 13) return;

        let query = $(this).val().trim();
        if (!query) return;

        let url = "https://nominatim.openstreetmap.org/search?format=json&q=" + encodeURIComponent(query);

        fetch(url)
            .then(r => r.json())
            .then(results => {
                if (!results.length) return;

                let r = results[0];

                let nLat = parseFloat(r.lat);
                let nLng = parseFloat(r.lon);

                map.setView([nLat, nLng], 14);
                marker.setLatLng([nLat, nLng]);
                updateInputs(nLat, nLng);
            });
    });

    // --- PLACE SEARCH ---
    $('#ccg_event_place_search').on('keyup', function(){

        let query = $(this).val();
        if (query.length < 3) {
            $('#ccg_event_place_results').html('');
            return;
        }

        $.get('/wp-json/wp/v2/place?search=' + encodeURIComponent(query), function(data){

            let html = '<ul class="ccg-place-list">';

            data.forEach(item => {
                html += '<li data-id="'+item.id+'">'+item.title.rendered+'</li>';
            });

            html += '</ul>';

            $('#ccg_event_place_results').html(html);

            $('.ccg-place-list li').on('click', function(){
                let id = $(this).data('id');
                $('#ccg_event_related_place').val(id);
                $('#ccg_event_place_search').val($(this).text());
                $('#ccg_event_place_results').html('');

                // preload place coordinates
                $.get('/wp-json/wp/v2/place/' + id, function(place){
                    if (place.meta && place.meta.lat && place.meta.lng) {
                        let lat = parseFloat(place.meta.lat);
                        let lng = parseFloat(place.meta.lng);

                        $('#ccg_event_lat').val(lat);
                        $('#ccg_event_lng').val(lng);

                        marker.setLatLng([lat,lng]);
                        map.setView([lat,lng], 14);
                    }
                });
            });
        });
    });

});
