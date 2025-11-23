<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Înregistrăm rutele REST folosind helper-ul din ccg-core.
 */
function ccg_partners_register_rest() {
    if ( function_exists( 'ccg_core_register_rest_routes' ) ) {
        ccg_core_register_rest_routes( 'ccg_partners_register_rest_routes' );
    } else {
        add_action( 'rest_api_init', 'ccg_partners_register_rest_routes' );
    }
}

/**
 * Rutele efective.
 */
function ccg_partners_register_rest_routes() {

    register_rest_route(
        'ccg/v1',
        '/partners',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'ccg_partners_rest_list',
            'permission_callback' => '__return_true',
            'args'                => [
                'type' => [
                    'description' => 'Filtru după tip (partner/sponsor)',
                    'type'        => 'string',
                    'required'    => false,
                ],
                'tier' => [
                    'description' => 'Filtru după tier (standard/gold/silver)',
                    'type'        => 'string',
                    'required'    => false,
                ],
                'published' => [
                    'description' => 'Filtru după status (1/0)',
                    'type'        => 'string',
                    'required'    => false,
                ],
            ],
        ]
    );

    register_rest_route(
        'ccg/v1',
        '/partners/(?P<id>\d+)',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'ccg_partners_rest_single',
            'permission_callback' => '__return_true',
            'args'                => [
                'id' => [
                    'description' => 'ID Partener',
                    'type'        => 'integer',
                    'required'    => true,
                ],
            ],
        ]
    );
}

/**
 * Listare parteneri.
 */
function ccg_partners_rest_list( WP_REST_Request $request ) {

    $meta_query = [];

    $type = $request->get_param( 'type' );
    if ( $type ) {
        $meta_query[] = [
            'key'   => '_ccg_type',
            'value' => sanitize_text_field( $type ),
        ];
    }

    $tier = $request->get_param( 'tier' );
    if ( $tier ) {
        $meta_query[] = [
            'key'   => '_ccg_partner_tier',
            'value' => sanitize_text_field( $tier ),
        ];
    }

    $published = $request->get_param( 'published' );
    if ( $published !== null && $published !== '' ) {
        $meta_query[] = [
            'key'   => '_ccg_published',
            'value' => sanitize_text_field( $published ),
        ];
    } else {
        // implicit doar cei publicați
        $meta_query[] = [
            'key'   => '_ccg_published',
            'value' => '1',
        ];
    }

    $query_args = [
        'post_type'      => 'partners',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => $meta_query,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    $q = new WP_Query( $query_args );

    $items = [];
    foreach ( $q->posts as $post ) {
        $items[] = ccg_partners_rest_serialize( $post );
    }

    return rest_ensure_response( $items );
}

/**
 * Single partner.
 */
function ccg_partners_rest_single( WP_REST_Request $request ) {
    $id   = (int) $request['id'];
    $post = get_post( $id );

    if ( ! $post || 'partners' !== $post->post_type ) {
        return new WP_Error(
            'ccg_partner_not_found',
            __( 'Partenerul nu a fost găsit.', 'ccg-partners' ),
            [ 'status' => 404 ]
        );
    }

    return rest_ensure_response( ccg_partners_rest_serialize( $post ) );
}

/**
 * Serializator comun (pentru list & single).
 */
function ccg_partners_rest_serialize( WP_Post $post ) {

    $banner_id = get_post_meta( $post->ID, '_ccg_banner_id', true );
    $site_url  = get_post_meta( $post->ID, '_ccg_site_url', true );
    $type      = get_post_meta( $post->ID, '_ccg_type', true );
    $tier      = get_post_meta( $post->ID, '_ccg_partner_tier', true );
    $published = get_post_meta( $post->ID, '_ccg_published', true );

    $banner_url = $banner_id ? wp_get_attachment_image_url( $banner_id, 'full' ) : '';

    return [
        'id'         => (int) $post->ID,
        'title'      => get_the_title( $post ),
        'link'       => get_permalink( $post ),
        'site_url'   => $site_url,
        'type'       => $type,
        'tier'       => $tier,
        'published'  => $published,
        'banner_id'  => $banner_id ? (int) $banner_id : null,
        'banner_url' => $banner_url,
        'date'       => get_the_date( 'c', $post ),
    ];
}
