<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Auto-detect images for a post:
 * - Gutenberg blocks (wp-image-{ID}, data-id)
 * - [gallery ids="1,2,3"]
 * - featured image (fallback)
 *
 * @param int $post_id
 *
 * @return array
 */
function ccg_gallery_detect_images_from_post( $post_id ) {
    $post = get_post( $post_id );
    if ( ! $post ) {
        return [];
    }

    $content = $post->post_content;
    $images  = [];
    $found_ids = [];

    // 1. Gutenberg / classic content: wp-image-{ID}.
    if ( $content ) {
        if ( preg_match_all( '/wp-image-([0-9]+)/', $content, $matches ) ) {
            $ids = array_map( 'absint', $matches[1] );
            foreach ( $ids as $id ) {
                if ( $id && ! in_array( $id, $found_ids, true ) ) {
                    $img = ccg_gallery_build_image_data( $id, $post_id );
                    if ( $img ) {
                        $images[]   = $img;
                        $found_ids[] = $id;
                    }
                }
            }
        }

        // 2. [gallery ids="1,2,3"]
        if ( preg_match_all( '/\[gallery[^\]]*ids="([^"]+)"[^\]]*\]/', $content, $gallery_matches ) ) {
            foreach ( $gallery_matches[1] as $ids_string ) {
                $ids = array_filter( array_map( 'absint', explode( ',', $ids_string ) ) );
                foreach ( $ids as $id ) {
                    if ( $id && ! in_array( $id, $found_ids, true ) ) {
                        $img = ccg_gallery_build_image_data( $id, $post_id );
                        if ( $img ) {
                            $images[]   = $img;
                            $found_ids[] = $id;
                        }
                    }
                }
            }
        }
    }

    // 3. Featured image fallback.
    if ( empty( $images ) ) {
        $thumb_id = get_post_thumbnail_id( $post_id );
        if ( $thumb_id ) {
            $img = ccg_gallery_build_image_data( $thumb_id, $post_id );
            if ( $img ) {
                $images[] = $img;
            }
        }
    }

    return $images;
}
