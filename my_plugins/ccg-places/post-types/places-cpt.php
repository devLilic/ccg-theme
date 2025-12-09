<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Înregistrarea CPT "place".
 */
function ccg_places_register_cpt() {

    $labels = [
        'name'               => __( 'Locații', 'ccg-places' ),
        'singular_name'      => __( 'Locație', 'ccg-places' ),
        'menu_name'          => __( 'Locații turistice', 'ccg-places' ),
        'add_new'            => __( 'Adaugă Locație', 'ccg-places' ),
        'add_new_item'       => __( 'Adaugă Locație Nouă', 'ccg-places' ),
        'edit_item'          => __( 'Editează Locație', 'ccg-places' ),
        'new_item'           => __( 'Locație Nouă', 'ccg-places' ),
        'view_item'          => __( 'Vezi Locația', 'ccg-places' ),
        'search_items'       => __( 'Caută Locații', 'ccg-places' ),
        'not_found'          => __( 'Nicio locație găsită', 'ccg-places' ),
        'not_found_in_trash' => __( 'Nicio locație în coș', 'ccg-places' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-location-alt',
        'supports'           => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'places' ],
        'show_in_rest'       => true,

        // 🔥 AICI SE REZOLVĂ PROBLEMA
        'taxonomies'         => [
            'place_category',
            'tourism_zone',
            'place_theme'
        ],
    ];

    ccg_core_register_post_type( 'place', $args, 'ccg-places' );
}

