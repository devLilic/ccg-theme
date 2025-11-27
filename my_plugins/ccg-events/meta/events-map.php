<?php
if (!defined('ABSPATH')) exit;

/**
 * Render map block for selecting coordinates + search + zoom memory.
 */
function ccg_events_render_map_block($post) {

    $lat  = get_post_meta($post->ID, '_ccg_event_lat', true);
    $lng  = get_post_meta($post->ID, '_ccg_event_lng', true);
    $zoom = get_post_meta($post->ID, '_ccg_event_map_zoom', true);

    // default Moldova
    if (!$lat)  $lat  = 47.0;
    if (!$lng)  $lng  = 28.8;
    if (!$zoom) $zoom = 8;

    wp_nonce_field('ccg_event_map_nonce', 'ccg_event_map_nonce_field');

    ?>
    <div class="ccg-field-group">
        <label><strong>Localizare pe hartă (Leaflet)</strong></label>

        <!-- Search -->
        <input type="text"
               id="ccg_event_map_search"
               class="widefat"
               placeholder="Caută localitatea... (ex: Chișinău, Orhei, Cahul)" />

        <br><br>

        <!-- Map container -->
        <div id="ccg-event-map"
             data-lat="<?php echo esc_attr($lat); ?>"
             data-lng="<?php echo esc_attr($lng); ?>"
             data-zoom="<?php echo esc_attr($zoom); ?>">
        </div>

        <br>

        <div class="ccg-meta-two-cols">
            <div>
                <label for="ccg_event_lat">Latitudine</label>
                <input type="text" id="ccg_event_lat" name="ccg_event_lat"
                       value="<?php echo esc_attr($lat); ?>" class="widefat">
            </div>
            <div>
                <label for="ccg_event_lng">Longitudine</label>
                <input type="text" id="ccg_event_lng" name="ccg_event_lng"
                       value="<?php echo esc_attr($lng); ?>" class="widefat">
            </div>
        </div>

        <input type="hidden" id="ccg_event_map_zoom" name="ccg_event_map_zoom"
               value="<?php echo esc_attr($zoom); ?>">

        <p class="description">
            Click pe hartă pentru a seta coordonatele.
            Poți modifica și manual lat/lng — markerul se va actualiza automat.
            Căutarea funcționează prin geocodare OpenStreetMap.
        </p>
    </div>
    <?php
}
