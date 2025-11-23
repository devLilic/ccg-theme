<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Înregistrarea taxonomiilor pentru Places.
 */
function ccg_places_register_taxonomies() {

    // 1. CATEGORIE PRINCIPALĂ
    $category_labels = [
        'name'              => __( 'Categorii loc', 'ccg-places' ),
        'singular_name'     => __( 'Categorie loc', 'ccg-places' ),
        'search_items'      => __( 'Caută Categorii', 'ccg-places' ),
        'all_items'         => __( 'Toate Categoriile', 'ccg-places' ),
        'edit_item'         => __( 'Editează Categorie', 'ccg-places' ),
        'update_item'       => __( 'Actualizează Categorie', 'ccg-places' ),
        'add_new_item'      => __( 'Adaugă Categorie Nouă', 'ccg-places' ),
        'new_item_name'     => __( 'Nume Categorie Nouă', 'ccg-places' ),
        'menu_name'         => __( 'Categorie loc', 'ccg-places' ),
    ];

    $category_args = [
        'labels'            => $category_labels,
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ];

    ccg_core_register_taxonomy(
        'place_category',
        [ 'place' ],
        $category_args,
        'ccg-places'
    );

    // 2. REGIUNE (raion / ATU)
    $region_labels = [
        'name'              => __( 'Regiuni', 'ccg-places' ),
        'singular_name'     => __( 'Regiune', 'ccg-places' ),
        'search_items'      => __( 'Caută Regiuni', 'ccg-places' ),
        'all_items'         => __( 'Toate Regiunile', 'ccg-places' ),
        'edit_item'         => __( 'Editează Regiune', 'ccg-places' ),
        'update_item'       => __( 'Actualizează Regiune', 'ccg-places' ),
        'add_new_item'      => __( 'Adaugă Regiune Nouă', 'ccg-places' ),
        'new_item_name'     => __( 'Nume Regiune Nouă', 'ccg-places' ),
        'menu_name'         => __( 'Regiuni', 'ccg-places' ),
    ];

    $region_args = [
        'labels'            => $region_labels,
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ];

    ccg_core_register_taxonomy(
        'place_region',
        [ 'place' ],
        $region_args,
        'ccg-places'
    );

    // 3. ZONĂ TURISTICĂ
    $zone_labels = [
        'name'              => __( 'Zone turistice', 'ccg-places' ),
        'singular_name'     => __( 'Zonă turistică', 'ccg-places' ),
        'search_items'      => __( 'Caută Zone', 'ccg-places' ),
        'all_items'         => __( 'Toate Zonele', 'ccg-places' ),
        'edit_item'         => __( 'Editează Zonă', 'ccg-places' ),
        'update_item'       => __( 'Actualizează Zonă', 'ccg-places' ),
        'add_new_item'      => __( 'Adaugă Zonă Nouă', 'ccg-places' ),
        'new_item_name'     => __( 'Nume Zonă Nouă', 'ccg-places' ),
        'menu_name'         => __( 'Zone turistice', 'ccg-places' ),
    ];

    $zone_args = [
        'labels'            => $zone_labels,
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ];

    ccg_core_register_taxonomy(
        'tourism_zone',
        [ 'place' ],
        $zone_args,
        'ccg-places'
    );

    // 4. TEME (tag-uri)
    $themes_labels = [
        'name'                       => __( 'Teme turistice', 'ccg-places' ),
        'singular_name'              => __( 'Temă', 'ccg-places' ),
        'search_items'               => __( 'Caută Teme', 'ccg-places' ),
        'popular_items'              => __( 'Teme populare', 'ccg-places' ),
        'all_items'                  => __( 'Toate Teme', 'ccg-places' ),
        'edit_item'                  => __( 'Editează Temă', 'ccg-places' ),
        'update_item'                => __( 'Actualizează Temă', 'ccg-places' ),
        'add_new_item'               => __( 'Adaugă Temă Nouă', 'ccg-places' ),
        'new_item_name'              => __( 'Nume Temă Nouă', 'ccg-places' ),
        'separate_items_with_commas' => __( 'Separă temele cu virgulă', 'ccg-places' ),
        'add_or_remove_items'        => __( 'Adaugă sau șterge teme', 'ccg-places' ),
        'choose_from_most_used'      => __( 'Alege dintre cele mai folosite teme', 'ccg-places' ),
        'menu_name'                  => __( 'Teme', 'ccg-places' ),
    ];

    $themes_args = [
        'labels'            => $themes_labels,
        'public'            => true,
        'hierarchical'      => false,
        'show_ui'           => true,         // <-- important
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ];

    ccg_core_register_taxonomy(
        'place_theme',
        [ 'place' ],
        $themes_args,
        'ccg-places'
    );
}
