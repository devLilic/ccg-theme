<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definim coloanele pentru listarea CPT-ului.
 */
function ccg_partners_columns( $columns ) {
    $new = [];

    $new['cb']         = $columns['cb'];
    $new['thumbnail']  = __( 'Banner', 'ccg-partners' );
    $new['title']      = __( 'Nume', 'ccg-partners' );
    $new['type']       = __( 'Tip', 'ccg-partners' );
    $new['published']  = __( 'Status', 'ccg-partners' );
    $new['date']       = $columns['date'];

    return $new;
}

/**
 * Conținutul coloanelor personalizate.
 */
function ccg_partners_columns_content( $column, $post_id ) {

    if ( 'thumbnail' === $column ) {
        $banner_id = get_post_meta( $post_id, '_ccg_banner_id', true );
        if ( $banner_id ) {
            echo wp_get_attachment_image( $banner_id, [ 80, 80 ] );
        } else {
            echo '<span style="color:#999;font-size:11px;">' . esc_html__( 'Fără banner', 'ccg-partners' ) . '</span>';
        }
    }

    if ( 'type' === $column ) {
        $type = get_post_meta( $post_id, '_ccg_type', true );
        echo ccg_partners_format_type_badge( $type );
    }

    if ( 'published' === $column ) {
        $status = get_post_meta( $post_id, '_ccg_published', true );
        echo ccg_partners_format_status_badge( $status );
    }
}

/**
 * Înregistrăm hooks pentru coloane.
 */
function ccg_partners_register_admin_columns() {
    add_filter( 'manage_partners_posts_columns', 'ccg_partners_columns' );
    add_action( 'manage_partners_posts_custom_column', 'ccg_partners_columns_content', 10, 2 );
}
