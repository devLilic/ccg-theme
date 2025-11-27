<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register REST routes.
 */
function ccg_events_register_rest() {

    register_rest_route(
        'ccg/v1',
        '/events',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'ccg_events_rest_list',
            'permission_callback' => '__return_true',
            'args'                => [
                'event_type'      => [ 'type' => 'string', 'required' => false ],
                'region'          => [ 'type' => 'string', 'required' => false ],
                'tourism_zone'    => [ 'type' => 'string', 'required' => false ],
                'event_theme'     => [ 'type' => 'string', 'required' => false ],
                'date_mode'       => [ 'type' => 'string', 'required' => false ], // today|weekend|month|range
                'date_from'       => [ 'type' => 'string', 'required' => false ],
                'date_to'         => [ 'type' => 'string', 'required' => false ],
                'language'        => [ 'type' => 'string', 'required' => false ], // RO,RU,EN
                'audience'        => [ 'type' => 'string', 'required' => false ], // slug in meta
                'free_only'       => [ 'type' => 'boolean', 'required' => false ],
                'environment'     => [ 'type' => 'string', 'required' => false ], // indoor|outdoor|mixed
                'place_id'        => [ 'type' => 'integer', 'required' => false ],
                'search'          => [ 'type' => 'string', 'required' => false ],
            ],
        ]
    );

    register_rest_route(
        'ccg/v1',
        '/events/(?P<id>\d+)',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'ccg_events_rest_single',
            'permission_callback' => '__return_true',
            'args'                => [
                'id' => [
                    'description' => 'ID eveniment',
                    'type'        => 'integer',
                    'required'    => true,
                ],
            ],
        ]
    );
}

/**
 * List events.
 */
function ccg_events_rest_list( WP_REST_Request $request ) {

    $tax_query  = [];
    $meta_query = [];

    // Tax filters
    if ( $type = $request->get_param( 'event_type' ) ) {
        $tax_query[] = [
            'taxonomy' => 'event_type',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $type ),
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

    if ( $theme = $request->get_param( 'event_theme' ) ) {
        $tax_query[] = [
            'taxonomy' => 'event_theme',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $theme ),
        ];
    }

    // Language
    if ( $language = $request->get_param( 'language' ) ) {
        $meta_query[] = [
            'key'     => '_ccg_event_languages',
            'value'   => sanitize_text_field( $language ),
            'compare' => 'LIKE',
        ];
    }

    // Audience
    if ( $audience = $request->get_param( 'audience' ) ) {
        $meta_query[] = [
            'key'     => '_ccg_event_target_audience',
            'value'   => sanitize_text_field( $audience ),
            'compare' => 'LIKE',
        ];
    }

    // Environment
    if ( $env = $request->get_param( 'environment' ) ) {
        $meta_query[] = [
            'key'   => '_ccg_event_environment',
            'value' => sanitize_text_field( $env ),
        ];
    }

    // Free only (heuristic: ticket_price contains "gratuit" sau este gol)
    if ( $request->get_param( 'free_only' ) ) {
        $meta_query[] = [
            'relation' => 'OR',
            [
                'key'     => '_ccg_event_ticket_price',
                'compare' => '=',
                'value'   => '',
            ],
            [
                'key'     => '_ccg_event_ticket_price',
                'compare' => 'LIKE',
                'value'   => 'gratuit',
            ],
        ];
    }

    // Filter by place
    if ( $place_id = $request->get_param( 'place_id' ) ) {
        $meta_query[] = [
            'key'   => '_ccg_event_related_place',
            'value' => (int) $place_id,
        ];
    }

    // Date filtering
    $date_mode = $request->get_param( 'date_mode' );
    $today     = current_time( 'Y-m-d' );

    if ( $date_mode === 'today' ) {
        $meta_query[] = [
            'key'     => '_ccg_event_date_start',
            'value'   => $today,
            'compare' => '<=',
            'type'    => 'DATE',
        ];
        $meta_query[] = [
            'key'     => '_ccg_event_date_end',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATE',
        ];
    } elseif ( $date_mode === 'range' ) {
        $from = $request->get_param( 'date_from' );
        $to   = $request->get_param( 'date_to' );
        if ( $from ) {
            $meta_query[] = [
                'key'     => '_ccg_event_date_end',
                'value'   => sanitize_text_field( $from ),
                'compare' => '>=',
                'type'    => 'DATE',
            ];
        }
        if ( $to ) {
            $meta_query[] = [
                'key'     => '_ccg_event_date_start',
                'value'   => sanitize_text_field( $to ),
                'compare' => '<=',
                'type'    => 'DATE',
            ];
        }
    }
    // Weekend / month se pot adăuga într-o versiune ulterioară

    $query_args = [
        'post_type'      => 'event',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ];

    if ( $search = $request->get_param( 'search' ) ) {
        $query_args['s'] = sanitize_text_field( $search );
    }

    if ( ! empty( $tax_query ) ) {
        $query_args['tax_query'] = $tax_query;
    }

    if ( ! empty( $meta_query ) ) {
        $query_args['meta_query'] = $meta_query;
    }

    $q = new WP_Query( $query_args );

    $items = [];
    foreach ( $q->posts as $post ) {
        $items[] = ccg_events_rest_serialize( $post );
    }

    return rest_ensure_response( $items );
}

/**
 * Single event.
 */
function ccg_events_rest_single( WP_REST_Request $request ) {
    $id   = (int) $request['id'];
    $post = get_post( $id );

    if ( ! $post || 'event' !== $post->post_type ) {
        return new WP_Error(
            'ccg_event_not_found',
            __( 'Evenimentul nu a fost găsit.', 'ccg-events' ),
            [ 'status' => 404 ]
        );
    }

    return rest_ensure_response( ccg_events_rest_serialize( $post ) );
}

/**
 * Serializator Eveniment.
 */
function ccg_events_rest_serialize( WP_Post $post ) {

    $short_desc   = get_post_meta( $post->ID, '_ccg_event_short_description', true );
    $date_start   = get_post_meta( $post->ID, '_ccg_event_date_start', true );
    $time_start   = get_post_meta( $post->ID, '_ccg_event_time_start', true );
    $date_end     = get_post_meta( $post->ID, '_ccg_event_date_end', true );
    $time_end     = get_post_meta( $post->ID, '_ccg_event_time_end', true );
    $is_one_day   = get_post_meta( $post->ID, '_ccg_event_is_one_day', true );
    $recurrence   = get_post_meta( $post->ID, '_ccg_event_recurrence', true );
    $rec_pattern  = get_post_meta( $post->ID, '_ccg_event_recurrence_pattern', true );

    $address      = get_post_meta( $post->ID, '_ccg_event_address', true );
    $locality     = get_post_meta( $post->ID, '_ccg_event_locality', true );
    $lat          = get_post_meta( $post->ID, '_ccg_event_lat', true );
    $lng          = get_post_meta( $post->ID, '_ccg_event_lng', true );

    $related_place = (int) get_post_meta( $post->ID, '_ccg_event_related_place', true );

    $org_name     = get_post_meta( $post->ID, '_ccg_event_organizer_name', true );
    $org_site     = get_post_meta( $post->ID, '_ccg_event_organizer_website', true );
    $org_phone    = get_post_meta( $post->ID, '_ccg_event_organizer_phone', true );
    $org_email    = get_post_meta( $post->ID, '_ccg_event_organizer_email', true );
    $fb_event     = get_post_meta( $post->ID, '_ccg_event_facebook', true );
    $ig_event     = get_post_meta( $post->ID, '_ccg_event_instagram', true );

    $ticket_price = get_post_meta( $post->ID, '_ccg_event_ticket_price', true );
    $booking_url  = get_post_meta( $post->ID, '_ccg_event_booking_url', true );
    $program_file = (int) get_post_meta( $post->ID, '_ccg_event_program_file', true );
    $program_text = get_post_meta( $post->ID, '_ccg_event_program_text', true );

    $environment  = get_post_meta( $post->ID, '_ccg_event_environment', true );

    $languages    = ccg_events_csv_to_array( get_post_meta( $post->ID, '_ccg_event_languages', true ) );
    $audiences    = ccg_events_csv_to_array( get_post_meta( $post->ID, '_ccg_event_target_audience', true ) );

    $gallery      = get_post_meta( $post->ID, '_ccg_event_gallery', true );
    $gallery_ids  = $gallery ? array_filter( array_map( 'absint', explode( ',', $gallery ) ) ) : [];
    $video_url    = get_post_meta( $post->ID, '_ccg_event_video_url', true );

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

    $type_terms   = $terms_to_array( 'event_type' );
    $theme_terms  = $terms_to_array( 'event_theme' );
    $region_terms = $terms_to_array( 'place_region' );
    $zone_terms   = $terms_to_array( 'tourism_zone' );

    $program_file_url = $program_file ? wp_get_attachment_url( $program_file ) : '';

    return [
        'id'               => (int) $post->ID,
        'name'             => get_the_title( $post ),
        'slug'             => $post->post_name,
        'short_description'=> $short_desc,
        'long_description' => apply_filters( 'the_content', $post->post_content ),
        'dates' => [
            'start_date'  => $date_start,
            'start_time'  => $time_start,
            'end_date'    => $date_end,
            'end_time'    => $time_end,
            'is_one_day'  => (bool) $is_one_day,
            'recurrence'  => $recurrence,
            'pattern'     => $rec_pattern,
        ],
        'location' => [
            'address'  => $address,
            'locality' => $locality,
            'lat'      => $lat ? (float) $lat : null,
            'lng'      => $lng ? (float) $lng : null,
        ],
        'related_place' => $related_place,
        'organizer' => [
            'name'     => $org_name,
            'website'  => $org_site,
            'phone'    => $org_phone,
            'email'    => $org_email,
            'facebook' => $fb_event,
            'instagram'=> $ig_event,
        ],
        'tickets' => [
            'price'   => $ticket_price,
            'booking' => $booking_url,
        ],
        'program' => [
            'file_id'  => $program_file,
            'file_url' => $program_file_url,
            'details'  => $program_text,
        ],
        'environment' => $environment,
        'languages'   => $languages,
        'target_audience' => $audiences,
        'taxonomies' => [
            'event_type'   => $type_terms,
            'event_theme'  => $theme_terms,
            'region'       => $region_terms,
            'tourism_zone' => $zone_terms,
        ],
        'media' => [
            'gallery'   => $gallery_out,
            'video_url' => $video_url,
        ],
        'permalink' => get_permalink( $post ),
    ];
}
