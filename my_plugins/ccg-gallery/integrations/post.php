<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register default gallery source for blog posts.
 */
function ccg_gallery_register_source_post() {
    ccg_gallery_register_source(
        'post',
        [
            'type' => 'auto',
        ]
    );
}
add_action( 'ccg_gallery_init', 'ccg_gallery_register_source_post' );
