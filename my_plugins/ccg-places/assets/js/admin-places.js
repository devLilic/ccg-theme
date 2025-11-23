(function ($) {
    $(document).ready(function () {

        // ====== LEAFLET MAP ======
        var $map = $('#ccg_place_map');
        if ($map.length && typeof L !== 'undefined') {

            var centerLat = parseFloat($map.data('center-lat')) || 47.0;
            var centerLng = parseFloat($map.data('center-lng')) || 28.8;
            var centerZoom = parseInt($map.data('center-zoom'), 10) || 7;

            var markerLat = parseFloat($map.data('marker-lat'));
            var markerLng = parseFloat($map.data('marker-lng'));

            var $latInput = $('#ccg_place_lat');
            var $lngInput = $('#ccg_place_lng');
            var $centerLatI = $('#ccg_place_map_center_lat');
            var $centerLngI = $('#ccg_place_map_center_lng');
            var $zoomInput = $('#ccg_place_map_zoom');

            var map = L.map('ccg_place_map', {
                zoomControl: true,
                scrollWheelZoom: true
            }).setView([centerLat, centerLng], centerZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // === FIX IMPORTANT Leaflet ===
            setTimeout(function () {
                map.invalidateSize(true);
            }, 300);

            var marker = null;

            function setMarker(lat, lng, moveCenter) {
                if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
                    return;
                }

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng], {draggable: true}).addTo(map);

                    marker.on('dragend', function (e) {
                        var m = e.target.getLatLng();
                        $latInput.val(m.lat.toFixed(6));
                        $lngInput.val(m.lng.toFixed(6));
                        // dacă tragi markerul, actualizăm și centrul
                        map.setView(m, map.getZoom());
                    });
                }

                $latInput.val(lat.toFixed(6));
                $lngInput.val(lng.toFixed(6));

                if (moveCenter) {
                    map.setView([lat, lng], map.getZoom());
                }
            }

            // marker inițial dacă există coordonate
            if (!isNaN(markerLat) && !isNaN(markerLng)) {
                setMarker(markerLat, markerLng, false);
            }

            // când utilizatorul face click pe hartă → setează marker + coordonate
            map.on('click', function (e) {
                setMarker(e.latlng.lat, e.latlng.lng, false);
            });

            // salvăm center + zoom de fiecare dată când user-ul mută/face zoom
            function updateMapMeta() {
                var c = map.getCenter();
                $centerLatI.val(c.lat.toFixed(6));
                $centerLngI.val(c.lng.toFixed(6));
                $zoomInput.val(map.getZoom());
            }

            map.on('moveend', updateMapMeta);
            map.on('zoomend', updateMapMeta);

            // la load inițial setăm valorile, ca să fie ceva salvat
            updateMapMeta();

            // FIX clasic Leaflet (container inițial gri / mic)
            setTimeout(function () {
                map.invalidateSize(true);
            }, 300);

            // ====== INPUT → MAP (scriu coordonate manual) ======
            function updateMapFromInputs() {
                var lat = parseFloat($latInput.val());
                var lng = parseFloat($lngInput.val());
                if (!isNaN(lat) && !isNaN(lng)) {
                    setMarker(lat, lng, true);
                    updateMapMeta();
                }
            }

            $latInput.on('change blur', updateMapFromInputs);
            $lngInput.on('change blur', updateMapFromInputs);

            // ====== SEARCH PE HARTĂ (Nominatim) ======
            var $searchInput = $('#ccg_place_map_search');
            var $searchBtn = $('#ccg_place_map_search_btn');

            function doSearch() {
                var query = $searchInput.val().trim();
                if (!query) return;

                // limităm căutarea în Moldova (adăugăm "Moldova" în query)
                var q = query + ', Moldova';
                var url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(q);

                $searchBtn.prop('disabled', true).text('Caut...');
                fetch(url, {headers: {'Accept': 'application/json'}})
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        if (!data || !data.length) {
                            alert('Nu am găsit locația specificată.');
                            return;
                        }
                        var res = data[0];
                        var lat = parseFloat(res.lat);
                        var lon = parseFloat(res.lon);
                        if (isNaN(lat) || isNaN(lon)) {
                            return;
                        }
                        map.setView([lat, lon], 14);
                        setMarker(lat, lon, false);
                        updateMapMeta();
                    })
                    .catch(function (err) {
                        console.error(err);
                        alert('A apărut o eroare la căutare.');
                    })
                    .finally(function () {
                        $searchBtn.prop('disabled', false).text('Caută');
                    });
            }

            $searchBtn.on('click', function (e) {
                e.preventDefault();
                doSearch();
            });

            $searchInput.on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    doSearch();
                }
            });
        }

        // ====== GALLERY ======
        var file_frame;
        $('#ccg_place_gallery_button').on('click', function (e) {
            e.preventDefault();

            if (file_frame) {
                file_frame.open();
                return;
            }

            file_frame = wp.media({
                title: 'Selectează imagini pentru galerie',
                button: {text: 'Folosește imaginile'},
                multiple: true
            });

            file_frame.on('select', function () {
                var selection = file_frame.state().get('selection');
                var ids = [];
                var $preview = $('#ccg_place_gallery_preview');

                $preview.empty();

                selection.each(function (attachment) {
                    attachment = attachment.toJSON();
                    ids.push(attachment.id);

                    var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                    $preview.append('<img src="' + url + '" />');
                });

                $('#ccg_place_gallery').val(ids.join(','));
            });

            file_frame.open();
        });

        $('#ccg_place_gallery_clear').on('click', function () {
            $('#ccg_place_gallery').val('');
            $('#ccg_place_gallery_preview').empty();
        });

    });
})(jQuery);
