<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * CPT: ccg_taxonomy – definiții de taxonomii dinamice.
 */
function ccg_taxmgr_register_cpt() {

    $labels = [
        'name'               => __( 'Taxonomii CCG', 'ccg-taxonomy' ),
        'singular_name'      => __( 'Taxonomie CCG', 'ccg-taxonomy' ),
        'menu_name'          => __( 'Taxonomy Manager', 'ccg-taxonomy' ),
        'add_new'            => __( 'Adaugă taxonomie', 'ccg-taxonomy' ),
        'add_new_item'       => __( 'Adaugă taxonomie nouă', 'ccg-taxonomy' ),
        'edit_item'          => __( 'Editează taxonomie', 'ccg-taxonomy' ),
        'new_item'           => __( 'Taxonomie nouă', 'ccg-taxonomy' ),
        'view_item'          => __( 'Vezi taxonomie', 'ccg-taxonomy' ),
        'search_items'       => __( 'Caută taxonomii', 'ccg-taxonomy' ),
        'not_found'          => __( 'Nicio taxonomie găsită', 'ccg-taxonomy' ),
        'not_found_in_trash' => __( 'Nicio taxonomie în coș', 'ccg-taxonomy' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => false, // o punem noi sub "Taxonomy Manager"
        'supports'           => [ 'title' ],
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
        'show_in_rest'       => true,
    ];

    register_post_type( 'ccg_taxonomy', $args );
}
