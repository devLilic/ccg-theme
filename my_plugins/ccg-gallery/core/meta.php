<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register post meta for all 'meta' sources so it appears in REST API.
 */
function ccg_gallery_register_meta_for_sources() {
    $sources = ccg_gallery_get_registered_sources();

    foreach ( $sources as $post_type => $config ) {
        if ( ! isset( $config['type'] ) || 'meta' !== $config['type'] ) {
            continue;
        }

        if ( empty( $config['meta_key'] ) ) {
            continue;
        }

        register_post_meta(
            $post_type,
            $config['meta_key'],
            [
                'show_in_rest' => true,
                'single'       => true,
                'type'         => 'string', // CSV.
                'auth_callback' => function () {
                    return current_user_can( 'edit_posts' );
                },
            ]
        );
    }
}
