<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register gallery source for Places CPT.
 */
function ccg_gallery_register_source_place() {
    ccg_gallery_register_source(
        'place',
        [
            'type'     => 'meta',
            'meta_key' => '_ccg_place_gallery',
        ]
    );
}
add_action( 'ccg_gallery_init', 'ccg_gallery_register_source_place' );
