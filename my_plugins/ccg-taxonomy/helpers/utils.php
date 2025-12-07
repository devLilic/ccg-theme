<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Returnează lista de post type-uri disponibile pentru asociere cu taxonomii.
 */
function ccg_taxmgr_get_available_post_types() {

    $exclude = [
        'attachment',
        'revision',
        'nav_menu_item',
        'custom_css',
        'customize_changeset',
        'oembed_cache',
        'user_request',
        'wp_block',
        'wp_template',
        'wp_template_part',
    ];

    $post_types = get_post_types( [], 'objects' );
    $result     = [];

    foreach ( $post_types as $pt ) {
        if ( in_array( $pt->name, $exclude, true ) ) {
            continue;
        }
        // excludem CPT-ul intern
        if ( 'ccg_taxonomy' === $pt->name ) {
            continue;
        }
        $result[ $pt->name ] = $pt->labels->singular_name . ' (' . $pt->name . ')';
    }

    return $result;
}
