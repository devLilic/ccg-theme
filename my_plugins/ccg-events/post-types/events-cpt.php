<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * CPT: event
 */
function ccg_events_register_cpt() {

    $labels = [
        'name'               => __( 'Evenimente', 'ccg-events' ),
        'singular_name'      => __( 'Eveniment', 'ccg-events' ),
        'menu_name'          => __( 'Evenimente', 'ccg-events' ),
        'add_new'            => __( 'Adaugă eveniment', 'ccg-events' ),
        'add_new_item'       => __( 'Adaugă eveniment nou', 'ccg-events' ),
        'edit_item'          => __( 'Editează eveniment', 'ccg-events' ),
        'new_item'           => __( 'Eveniment nou', 'ccg-events' ),
        'view_item'          => __( 'Vezi eveniment', 'ccg-events' ),
        'search_items'       => __( 'Caută evenimente', 'ccg-events' ),
        'not_found'          => __( 'Niciun eveniment găsit', 'ccg-events' ),
        'not_found_in_trash' => __( 'Niciun eveniment în coș', 'ccg-events' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'events' ],
        'show_in_rest'       => true,
        'capability_type'    => 'post',
    ];

    ccg_core_register_post_type( 'event', $args, 'ccg-events' );
}
