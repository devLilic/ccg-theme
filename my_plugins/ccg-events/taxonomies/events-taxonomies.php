<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Taxonomii specifice pentru Events.
 */
function ccg_events_register_taxonomies() {

    // event_type – hierarchical
    $type_labels = [
        'name'              => __( 'Tipuri de eveniment', 'ccg-events' ),
        'singular_name'     => __( 'Tip eveniment', 'ccg-events' ),
        'search_items'      => __( 'Caută tipuri', 'ccg-events' ),
        'all_items'         => __( 'Toate tipurile', 'ccg-events' ),
        'parent_item'       => __( 'Categorie părinte', 'ccg-events' ),
        'parent_item_colon' => __( 'Categorie părinte:', 'ccg-events' ),
        'edit_item'         => __( 'Editează tip eveniment', 'ccg-events' ),
        'update_item'       => __( 'Actualizează tip eveniment', 'ccg-events' ),
        'add_new_item'      => __( 'Adaugă tip nou', 'ccg-events' ),
        'new_item_name'     => __( 'Nume tip nou', 'ccg-events' ),
        'menu_name'         => __( 'Tip eveniment', 'ccg-events' ),
    ];

    $type_args = [
        'labels'            => $type_labels,
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ];

    ccg_core_register_taxonomy(
        'event_type',
        [ 'event' ],
        $type_args,
        'ccg-events'
    );

    // event_theme – non-hierarchical (tags)
    $theme_labels = [
        'name'                       => __( 'Teme eveniment', 'ccg-events' ),
        'singular_name'              => __( 'Temă eveniment', 'ccg-events' ),
        'search_items'               => __( 'Caută teme', 'ccg-events' ),
        'popular_items'              => __( 'Teme populare', 'ccg-events' ),
        'all_items'                  => __( 'Toate temele', 'ccg-events' ),
        'edit_item'                  => __( 'Editează temă', 'ccg-events' ),
        'update_item'                => __( 'Actualizează temă', 'ccg-events' ),
        'add_new_item'               => __( 'Adaugă temă nouă', 'ccg-events' ),
        'new_item_name'              => __( 'Nume temă nouă', 'ccg-events' ),
        'separate_items_with_commas' => __( 'Separă temele cu virgulă', 'ccg-events' ),
        'add_or_remove_items'        => __( 'Adaugă sau șterge teme', 'ccg-events' ),
        'choose_from_most_used'      => __( 'Alege dintre cele mai folosite teme', 'ccg-events' ),
        'menu_name'                  => __( 'Teme eveniment', 'ccg-events' ),
    ];

    $theme_args = [
        'labels'            => $theme_labels,
        'public'            => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ];

    ccg_core_register_taxonomy(
        'event_theme',
        [ 'event' ],
        $theme_args,
        'ccg-events'
    );
}
