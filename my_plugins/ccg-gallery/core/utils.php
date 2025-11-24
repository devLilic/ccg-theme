<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Global registry for gallery sources.
 *
 * Structure:
 * $ccg_gallery_sources = [
 *   'post'  => [ 'type' => 'auto' ],
 *   'place' => [ 'type' => 'meta', 'meta_key' => '_ccg_place_gallery' ],
 *   ...
 * ];
 */
$GLOBALS['ccg_gallery_sources'] = [];

/**
 * Register a gallery source for a post type.
 *
 * @param string $post_type
 * @param array  $args {
 *     @type string $type     'auto' or 'meta'.
 *     @type string $meta_key Required when type = 'meta'.
 * }
 */
function ccg_gallery_register_source( $post_type, $args ) {
    $defaults = [
        'type'     => 'auto', // auto = detect from content, meta = from post meta.
        'meta_key' => '',
    ];

    $args = wp_parse_args( $args, $defaults );

    if ( 'meta' === $args['type'] && empty( $args['meta_key'] ) ) {
        return;
    }

    $GLOBALS['ccg_gallery_sources'][ $post_type ] = $args;
}

/**
 * Returns all registered sources after applying filter.
 *
 * @return array
 */
function ccg_gallery_get_registered_sources() {
    $sources = isset( $GLOBALS['ccg_gallery_sources'] ) ? $GLOBALS['ccg_gallery_sources'] : [];

    /**
     * Filter gallery sources.
     *
     * Allows other plugins/modules to register additional sources.
     */
    $sources = apply_filters( 'ccg_gallery_sources', $sources );

    return $sources;
}

/**
 * Get source config for a given post type.
 *
 * @param string $post_type
 *
 * @return array|null
 */
function ccg_gallery_get_source_for_post_type( $post_type ) {
    $sources = ccg_gallery_get_registered_sources();
    return isset( $sources[ $post_type ] ) ? $sources[ $post_type ] : null;
}

/**
 * Build image data array from attachment id.
 *
 * @param int $attachment_id
 * @param int $post_id
 *
 * @return array|null
 */
function ccg_gallery_build_image_data( $attachment_id, $post_id = 0 ) {
    $attachment_id = absint( $attachment_id );
    if ( ! $attachment_id ) {
        return null;
    }

    $image_src = wp_get_attachment_image_src( $attachment_id, 'large' );
    if ( ! $image_src ) {
        return null;
    }

    $alt     = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
    $caption = wp_get_attachment_caption( $attachment_id );

    return [
        'id'      => $attachment_id,
        'url'     => $image_src[0],
        'alt'     => $alt ? $alt : '',
        'caption' => $caption ? $caption : '',
    ];
}

/**
 * Main API: get images for a post.
 *
 * 1. Dacă există sursă 'meta' → citește IDs din meta.
 * 2. Altfel → auto detect (content + Gutenberg + featured image).
 *
 * @param int $post_id
 *
 * @return array
 */
function ccg_gallery_get_images_for_post( $post_id ) {
    $post = get_post( $post_id );
    if ( ! $post ) {
        return [];
    }

    $post_type = $post->post_type;
    $source    = ccg_gallery_get_source_for_post_type( $post_type );
    $images    = [];

    if ( $source && 'meta' === $source['type'] && ! empty( $source['meta_key'] ) ) {
        $raw = get_post_meta( $post_id, $source['meta_key'], true );

        if ( is_string( $raw ) && ! empty( $raw ) ) {
            $ids = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
        } elseif ( is_array( $raw ) ) {
            $ids = array_filter( array_map( 'absint', $raw ) );
        } else {
            $ids = [];
        }

        foreach ( $ids as $id ) {
            $img = ccg_gallery_build_image_data( $id, $post_id );
            if ( $img ) {
                $images[] = $img;
            }
        }
    }

    // Fallback / completare cu auto-detect.
    if ( empty( $images ) || ( $source && 'auto' === $source['type'] ) ) {
        $auto_images = ccg_gallery_detect_images_from_post( $post_id );

        // Prioritate pentru meta, apoi completare cu auto-detect (fără duplicate).
        $existing_ids = wp_list_pluck( $images, 'id' );

        foreach ( $auto_images as $img ) {
            if ( empty( $img['id'] ) || ! in_array( $img['id'], $existing_ids, true ) ) {
                $images[] = $img;
            }
        }
    }

    return $images;
}

/**
 * Helper to locate template inside plugin.
 *
 * @param string $template_name
 *
 * @return string|false
 */
function ccg_gallery_get_template_path( $template_name ) {
    $template_name = ltrim( $template_name, '/' );
    $path          = CCG_GALLERY_PATH . 'template-parts/' . $template_name;

    if ( file_exists( $path ) ) {
        return $path;
    }

    return false;
}
