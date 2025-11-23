<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Înregistrarea CPT "partners" prin ccg-core.
 */
function ccg_partners_register_cpt() {

    $labels = [
        'name'               => __( 'Parteneri', 'ccg-partners' ),
        'singular_name'      => __( 'Partener', 'ccg-partners' ),
        'menu_name'          => __( 'Parteneri & Sponsori', 'ccg-partners' ),
        'add_new'            => __( 'Adaugă Partener', 'ccg-partners' ),
        'add_new_item'       => __( 'Adaugă Partener Nou', 'ccg-partners' ),
        'edit_item'          => __( 'Editează Partener', 'ccg-partners' ),
        'new_item'           => __( 'Partener Nou', 'ccg-partners' ),
        'view_item'          => __( 'Vezi Partener', 'ccg-partners' ),
        'search_items'       => __( 'Caută Parteneri', 'ccg-partners' ),
        'not_found'          => __( 'Niciun partener găsit', 'ccg-partners' ),
        'not_found_in_trash' => __( 'Niciun partener în coșul de gunoi', 'ccg-partners' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => [ 'title' ],
        'rewrite'            => false,
        'show_in_rest'       => false, // gestionăm prin REST custom ccg/v1
        'capability_type'    => 'post',
    ];

    ccg_core_register_post_type( 'partners', $args, 'ccg-partners' );
}
