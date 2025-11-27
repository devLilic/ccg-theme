<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Populează taxonomy event_type cu tipurile standard.
 */
function ccg_seed_event_types() {

    $taxonomy = 'event_type';

    if ( ! taxonomy_exists( $taxonomy ) ) {
        return;
    }

    $types = [
        'Festivaluri' => [
            'Festival cultural',
            'Festival gastronomic',
            'Festival de vin',
            'Festival muzical',
            'Festival de arte',
        ],
        'Tradiții și comunități' => [
            'Eveniment tradițional',
            'Ritual popular',
            'Hram',
            'Târg meșteșugăresc',
        ],
        'Gastronomie' => [
            'Degustare de vin',
            'Eveniment gastronomic',
            'Masterclass culinar',
        ],
        'Outdoor & Natură' => [
            'Drumeție organizată',
            'Tur cu bicicleta',
            'Kayaking',
            'Birdwatching',
            'Camping Fest',
        ],
        'Religie' => [
            'Procesiune religioasă',
            'Sărbătoare creștină',
        ],
        'Sport' => [
            'Competiție sportivă',
            'Maraton',
            'Trail running',
        ],
        'Educație & Business' => [
            'Conferință',
            'Workshop',
            'Forum',
            'Expoziție',
        ],
    ];

    foreach ( $types as $parent => $children ) {
        $parent_term = term_exists( $parent, $taxonomy );
        if ( ! $parent_term ) {
            $parent_term = wp_insert_term( $parent, $taxonomy );
        }

        if ( is_wp_error( $parent_term ) ) {
            continue;
        }

        $parent_id = isset( $parent_term['term_id'] ) ? $parent_term['term_id'] : $parent_term['term_id'];

        foreach ( $children as $child ) {
            if ( ! term_exists( $child, $taxonomy ) ) {
                wp_insert_term(
                    $child,
                    $taxonomy,
                    [ 'parent' => $parent_id ]
                );
            }
        }
    }
}
// rulează doar în admin, să nu încarce front-end inutil
add_action( 'admin_init', 'ccg_seed_event_types' );
