<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render gallery HTML for a post.
 *
 * @param int|null $post_id
 * @param array    $args
 */
function ccg_gallery_render( $post_id = null, $args = [] ) {
    if ( null === $post_id ) {
        $post = get_post();
        if ( ! $post ) {
            return;
        }
        $post_id = $post->ID;
    }

    $images = ccg_gallery_get_images_for_post( $post_id );

    /**
     * Filter before rendering gallery.
     *
     * @param array $images
     * @param int   $post_id
     * @param array $args
     */
    $images = apply_filters( 'ccg_gallery_pre_render', $images, $post_id, $args );

    if ( empty( $images ) ) {
        return;
    }

    $template_path = ccg_gallery_get_template_path( 'gallery.php' );
    if ( ! $template_path ) {
        return;
    }

    // Make var available in template.
    $gallery_args = $args;

    include $template_path;

    /**
     * Action after gallery render.
     *
     * @param array $images
     * @param int   $post_id
     * @param array $args
     */
    do_action( 'ccg_gallery_post_render', $images, $post_id, $args );
}
