<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Coloane custom pentru CPT event.
 */
function ccg_events_columns( $columns ) {
    $new = [];

    $new['cb']        = $columns['cb'];
    $new['title']     = __( 'Eveniment', 'ccg-events' );
    $new['date_info'] = __( 'Dată', 'ccg-events' );
    $new['type']      = __( 'Tip', 'ccg-events' );
    $new['region']    = __( 'Regiune', 'ccg-events' );
    $new['place']     = __( 'Loc (Place)', 'ccg-events' );
    $new['date']      = $columns['date'];

    return $new;
}

function ccg_events_columns_content( $column, $post_id ) {

    if ( 'date_info' === $column ) {
        $start = get_post_meta( $post_id, '_ccg_event_date_start', true );
        $end   = get_post_meta( $post_id, '_ccg_event_date_end', true );
        $one   = get_post_meta( $post_id, '_ccg_event_is_one_day', true );

        if ( $start ) {
            echo esc_html( $start );
            if ( $one === '1' ) {
                echo ' (' . esc_html__( '1 zi', 'ccg-events' ) . ')';
            } elseif ( $end ) {
                echo ' → ' . esc_html( $end );
            }
        } else {
            echo '<span class="ccg-col-muted">' . esc_html__( 'Nespecificat', 'ccg-events' ) . '</span>';
        }
    }

    if ( 'type' === $column ) {
        $terms = get_the_terms( $post_id, 'event_type' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
        } else {
            echo '<span class="ccg-col-muted">—</span>';
        }
    }

    if ( 'region' === $column ) {
        $terms = get_the_terms( $post_id, 'place_region' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
        } else {
            echo '<span class="ccg-col-muted">—</span>';
        }
    }

    if ( 'place' === $column ) {
        $place_id = (int) get_post_meta( $post_id, '_ccg_event_related_place', true );
        if ( $place_id ) {
            $title = get_the_title( $place_id );
            if ( $title ) {
                echo esc_html( $title );
            } else {
                echo '<span class="ccg-col-muted">#' . esc_html( $place_id ) . '</span>';
            }
        } else {
            echo '<span class="ccg-col-muted">—</span>';
        }
    }
}

/**
 * Înregistrare hooks.
 */
function ccg_events_register_admin_columns() {
    add_filter( 'manage_event_posts_columns', 'ccg_events_columns' );
    add_action( 'manage_event_posts_custom_column', 'ccg_events_columns_content', 10, 2 );
}
