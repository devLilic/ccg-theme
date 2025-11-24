<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register REST routes.
 */
function ccg_gallery_register_rest_routes() {
    register_rest_route(
        'ccg/v1',
        '/gallery/(?P<id>[\d]+)',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'ccg_gallery_rest_get_gallery',
            'permission_callback' => '__return_true',
            'args'                => [
                'id' => [
                    'description' => 'Post ID.',
                    'type'        => 'integer',
                    'required'    => true,
                ],
            ],
        ]
    );
}

/**
 * REST callback: return gallery for a given post.
 *
 * @param WP_REST_Request $request
 *
 * @return WP_REST_Response|array
 */
function ccg_gallery_rest_get_gallery( $request ) {
    $post_id = (int) $request->get_param( 'id' );
    $post    = get_post( $post_id );

    if ( ! $post ) {
        return new WP_REST_Response(
            [
                'error'   => 'not_found',
                'message' => 'Post not found.',
            ],
            404
        );
    }

    $images = ccg_gallery_get_images_for_post( $post_id );

    $response = [
        'post_id' => $post_id,
        'count'   => count( $images ),
        'images'  => $images,
    ];

    return rest_ensure_response( $response );
}
