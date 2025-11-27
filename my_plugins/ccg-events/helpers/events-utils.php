<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper: parsează CSV în array curat.
 */
function ccg_events_csv_to_array( $value ) {
    if ( ! $value ) {
        return [];
    }
    return array_filter( array_map( 'trim', explode( ',', $value ) ) );
}

/**
 * Helper: serializare array → CSV.
 */
function ccg_events_array_to_csv( $arr ) {
    $arr = is_array( $arr ) ? $arr : [];
    $arr = array_filter( array_map( 'trim', $arr ) );
    return implode( ',', $arr );
}
