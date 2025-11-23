<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Returnează coordonatele ca array sau null.
 */
function ccg_places_get_coordinates( $post_id ) {
    $lat = get_post_meta( $post_id, '_ccg_place_lat', true );
    $lng = get_post_meta( $post_id, '_ccg_place_lng', true );

    if ( ! $lat || ! $lng ) {
        return null;
    }

    return [
        'lat' => (float) $lat,
        'lng' => (float) $lng,
    ];
}
