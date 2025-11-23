<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Coloanele pentru listare Locații.
 */
function ccg_places_columns( $columns ) {
    $new = [];

    $new['cb']          = $columns['cb'];
    $new['thumbnail']   = __( 'Imagine', 'ccg-places' );
    $new['title']       = __( 'Locație', 'ccg-places' );
    $new['region']      = __( 'Regiune', 'ccg-places' );
    $new['category']    = __( 'Categorie', 'ccg-places' );
    $new['zone']        = __( 'Zonă turistică', 'ccg-places' );
    $new['themes']      = __( 'Teme', 'ccg-places' );
    $new['coordinates'] = __( 'Coordonate', 'ccg-places' );
    $new['price_range'] = __( 'Preț', 'ccg-places' );
    $new['date']        = $columns['date'];

    return $new;
}

/**
 * Conținut pentru coloane custom.
 */
function ccg_places_columns_content( $column, $post_id ) {

    if ( 'thumbnail' === $column ) {
        if ( has_post_thumbnail( $post_id ) ) {
            echo get_the_post_thumbnail( $post_id, [ 60, 60 ] );
        } else {
            echo '<span class="ccg-col-muted">' . esc_html__( 'Fără imagine', 'ccg-places' ) . '</span>';
        }
    }

    if ( 'region' === $column ) {
        $terms = get_the_terms( $post_id, 'place_region' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
        } else {
            echo '<span class="ccg-col-muted">' . esc_html__( 'Nespecificat', 'ccg-places' ) . '</span>';
        }
    }

    if ( 'category' === $column ) {
        $terms = get_the_terms( $post_id, 'place_category' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
        } else {
            echo '<span class="ccg-col-muted">' . esc_html__( 'Nespecificat', 'ccg-places' ) . '</span>';
        }
    }

    if ( 'zone' === $column ) {
        $terms = get_the_terms( $post_id, 'tourism_zone' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
        } else {
            echo '<span class="ccg-col-muted">' . esc_html__( 'Nespecificat', 'ccg-places' ) . '</span>';
        }
    }

    if ( 'themes' === $column ) {
        $terms = get_the_terms( $post_id, 'place_theme' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
        } else {
            echo '<span class="ccg-col-muted">' . esc_html__( '—', 'ccg-places' ) . '</span>';
        }
    }

    if ( 'coordinates' === $column ) {
        $lat = get_post_meta( $post_id, '_ccg_place_lat', true );
        $lng = get_post_meta( $post_id, '_ccg_place_lng', true );

        if ( $lat && $lng ) {
            echo esc_html( $lat . ', ' . $lng );
        } else {
            echo '<span class="ccg-col-muted">' . esc_html__( 'Fără coordonate', 'ccg-places' ) . '</span>';
        }
    }

    if ( 'price_range' === $column ) {
        $price = get_post_meta( $post_id, '_ccg_place_price_range', true );
        if ( $price ) {
            echo esc_html( ucfirst( $price ) );
        } else {
            echo '<span class="ccg-col-muted">' . esc_html__( '—', 'ccg-places' ) . '</span>';
        }
    }
}

/**
 * Înregistrare hooks.
 */
function ccg_places_register_admin_columns() {
    add_filter( 'manage_place_posts_columns', 'ccg_places_columns' );
    add_action( 'manage_place_posts_custom_column', 'ccg_places_columns_content', 10, 2 );
}
