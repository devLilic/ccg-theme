<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Bootstrap pentru REST.
 */
function ccg_places_register_rest() {
    if ( function_exists( 'ccg_core_register_rest_routes' ) ) {
        ccg_core_register_rest_routes( 'ccg_places_register_rest_routes' );
    } else {
        add_action( 'rest_api_init', 'ccg_places_register_rest_routes' );
    }
}

/**
 * Definirea rutelor REST.
 */
function ccg_places_register_rest_routes() {

    register_rest_route(
        'ccg/v1',
        '/places',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'ccg_places_rest_list',
            'permission_callback' => '__return_true',
            'args'                => [
                'category'        => [ 'type' => 'string', 'required' => false ],
                'region'          => [ 'type' => 'string', 'required' => false ],
                'tourism_zone'    => [ 'type' => 'string', 'required' => false ],
                'themes'          => [ 'type' => 'string', 'required' => false ], // CSV
                'recommended_for' => [ 'type' => 'string', 'required' => false ], // CSV
                'best_season'     => [ 'type' => 'string', 'required' => false ],
                'duration'        => [ 'type' => 'string', 'required' => false ],
                'access'          => [ 'type' => 'string', 'required' => false ], // CSV
                'price_range'     => [ 'type' => 'string', 'required' => false ],
                'has_booking'     => [ 'type' => 'boolean', 'required' => false ],
                'search'          => [ 'type' => 'string', 'required' => false ],
            ],
        ]
    );

    register_rest_route(
        'ccg/v1',
        '/places/(?P<id>\d+)',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'ccg_places_rest_single',
            'permission_callback' => '__return_true',
            'args'                => [
                'id' => [
                    'description' => 'ID Locație',
                    'type'        => 'integer',
                    'required'    => true,
                ],
            ],
        ]
    );
}

/**
 * Listare.
 */
function ccg_places_rest_list( WP_REST_Request $request ) {

    $tax_query  = [];
    $meta_query = [];

    // Taxonomii
    if ( $cat = $request->get_param( 'category' ) ) {
        $tax_query[] = [
            'taxonomy' => 'place_category',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $cat ),
        ];
    }

    if ( $region = $request->get_param( 'region' ) ) {
        $tax_query[] = [
            'taxonomy' => 'place_region',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $region ),
        ];
    }

    if ( $zone = $request->get_param( 'tourism_zone' ) ) {
        $tax_query[] = [
            'taxonomy' => 'tourism_zone',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $zone ),
        ];
    }

    if ( $themes = $request->get_param( 'themes' ) ) {
        $themes_arr = array_filter( array_map( 'sanitize_text_field', explode( ',', $themes ) ) );
        if ( $themes_arr ) {
            $tax_query[] = [
                'taxonomy' => 'place_theme',
                'field'    => 'slug',
                'terms'    => $themes_arr,
            ];
        }
    }

    // Meta filters
    if ( $season = $request->get_param( 'best_season' ) ) {
        $meta_query[] = [
            'key'   => '_ccg_place_best_season',
            'value' => sanitize_text_field( $season ),
        ];
    }

    if ( $duration = $request->get_param( 'duration' ) ) {
        $meta_query[] = [
            'key'   => '_ccg_place_visit_duration',
            'value' => sanitize_text_field( $duration ),
        ];
    }

    if ( $price = $request->get_param( 'price_range' ) ) {
        $meta_query[] = [
            'key'   => '_ccg_place_price_range',
            'value' => sanitize_text_field( $price ),
        ];
    }

    if ( $has_booking = $request->get_param( 'has_booking' ) ) {
        $meta_query[] = [
            'key'     => '_ccg_place_booking_url',
            'compare' => filter_var( $has_booking, FILTER_VALIDATE_BOOLEAN ) ? '!=' : '=',
            'value'   => '',
        ];
    }

    if ( $recommended = $request->get_param( 'recommended_for' ) ) {
        $vals = array_filter( array_map( 'sanitize_text_field', explode( ',', $recommended ) ) );
        foreach ( $vals as $val ) {
            $meta_query[] = [
                'key'     => '_ccg_place_recommended_for',
                'value'   => $val,
                'compare' => 'LIKE',
            ];
        }
    }

    if ( $access = $request->get_param( 'access' ) ) {
        $vals = array_filter( array_map( 'sanitize_text_field', explode( ',', $access ) ) );
        foreach ( $vals as $val ) {
            $meta_query[] = [
                'key'     => '_ccg_place_access',
                'value'   => $val,
                'compare' => 'LIKE',
            ];
        }
    }

    $query_args = [
        'post_type'      => 'place',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        's'              => $request->get_param( 'search' ) ? sanitize_text_field( $request->get_param( 'search' ) ) : '',
    ];

    if ( ! empty( $tax_query ) ) {
        $query_args['tax_query'] = $tax_query;
    }

    if ( ! empty( $meta_query ) ) {
        $query_args['meta_query'] = $meta_query;
    }

    $q = new WP_Query( $query_args );

    $items = [];

    foreach ( $q->posts as $post ) {
        $items[] = ccg_places_rest_serialize( $post );
    }

    return rest_ensure_response( $items );
}

/**
 * Single place.
 */
function ccg_places_rest_single( WP_REST_Request $request ) {

    $id   = (int) $request['id'];
    $post = get_post( $id );

    if ( ! $post || 'place' !== $post->post_type ) {
        return new WP_Error(
            'ccg_place_not_found',
            __( 'Locația nu a fost găsită.', 'ccg-places' ),
            [ 'status' => 404 ]
        );
    }

    return rest_ensure_response( ccg_places_rest_serialize( $post ) );
}

/**
 * Serializator – structura JSON pentru front-end & hartă.
 */
function ccg_places_rest_serialize( WP_Post $post ) {

    $short_desc   = get_post_meta( $post->ID, '_ccg_place_short_description', true );
    $lat          = get_post_meta( $post->ID, '_ccg_place_lat', true );
    $lng          = get_post_meta( $post->ID, '_ccg_place_lng', true );
    $gallery      = get_post_meta( $post->ID, '_ccg_place_gallery', true );
    $hours        = get_post_meta( $post->ID, '_ccg_place_opening_hours', true );
    $duration     = get_post_meta( $post->ID, '_ccg_place_visit_duration', true );
    $best_season  = get_post_meta( $post->ID, '_ccg_place_best_season', true );
    $recommended  = get_post_meta( $post->ID, '_ccg_place_recommended_for', true );
    $access       = get_post_meta( $post->ID, '_ccg_place_access', true );
    $price_range  = get_post_meta( $post->ID, '_ccg_place_price_range', true );
    $website      = get_post_meta( $post->ID, '_ccg_place_contact_website', true );
    $phone        = get_post_meta( $post->ID, '_ccg_place_contact_phone', true );
    $email        = get_post_meta( $post->ID, '_ccg_place_contact_email', true );
    $social       = get_post_meta( $post->ID, '_ccg_place_contact_social', true );
    $booking_url  = get_post_meta( $post->ID, '_ccg_place_booking_url', true );

    $rel_events   = get_post_meta( $post->ID, '_ccg_place_related_events', true );
    $rel_routes   = get_post_meta( $post->ID, '_ccg_place_related_routes', true );
    $rel_wine     = get_post_meta( $post->ID, '_ccg_place_related_wineries', true );

    $gallery_ids  = $gallery ? array_filter( array_map( 'absint', explode( ',', $gallery ) ) ) : [];
    $gallery_out  = [];

    foreach ( $gallery_ids as $id ) {
        $url = wp_get_attachment_image_url( $id, 'large' );
        if ( $url ) {
            $gallery_out[] = [
                'id'  => $id,
                'url' => $url,
                'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
            ];
        }
    }

    // Taxonomies
    $terms_to_array = function( $taxonomy ) use ( $post ) {
        $terms = get_the_terms( $post, $taxonomy );
        $out   = [];

        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $t ) {
                $out[] = [
                    'id'   => $t->term_id,
                    'name' => $t->name,
                    'slug' => $t->slug,
                ];
            }
        }

        return $out;
    };

    $category_terms = $terms_to_array( 'place_category' );
    $region_terms   = $terms_to_array( 'place_region' );
    $zone_terms     = $terms_to_array( 'tourism_zone' );
    $theme_terms    = $terms_to_array( 'place_theme' );

    $recommended_arr = $recommended ? array_filter( array_map( 'trim', explode( ',', $recommended ) ) ) : [];
    $access_arr      = $access ? array_filter( array_map( 'trim', explode( ',', $access ) ) ) : [];

    return [
        'id'                => (int) $post->ID,
        'name'              => get_the_title( $post ),
        'slug'              => $post->post_name,
        'short_description' => $short_desc,
        'long_description'  => apply_filters( 'the_content', $post->post_content ),
        'coordinates'       => [
            'lat' => $lat ? (float) $lat : null,
            'lng' => $lng ? (float) $lng : null,
        ],
        'place_category'    => $category_terms,
        'region'            => $region_terms,
        'tourism_zone'      => $zone_terms,
        'themes'            => $theme_terms,
        'recommended_for'   => $recommended_arr,
        'best_season'       => $best_season,
        'visit_duration'    => $duration,
        'access'            => $access_arr,
        'price_range'       => $price_range,
        'opening_hours'     => $hours,
        'contact'           => [
            'website' => $website,
            'phone'   => $phone,
            'email'   => $email,
            'social'  => $social,
        ],
        'booking_url'       => $booking_url,
        'images'            => $gallery_out,
        'relations'         => [
            'events'   => $rel_events,
            'routes'   => $rel_routes,
            'wineries' => $rel_wine,
        ],
        'permalink'         => get_permalink( $post ),
    ];
}
